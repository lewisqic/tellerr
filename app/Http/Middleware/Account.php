<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class Account
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $app_user = app('app_user');
        $app_user->load('member.company.subscription');
        $company = $app_user->member->company;
        $subscription = $company->subscription;

        view()->share('company', $company);
        view()->share('subscription', $subscription);
        view()->share('is_trial', is_trial($subscription));
        view()->share('is_trial_expired', is_trial_expired($subscription));
        view()->share('trial_days_left', trial_days_left($subscription));
        view()->share('is_canceled', $subscription->status == 'canceled' ? true : false);
        view()->share('is_sandbox', false);

        app()->singleton('subscription', function($app) use($subscription) {
            return $subscription;
        });
        app()->singleton('company', function($app) use($company) {
            return $company;
        });

        // check for canceled subscription or expired trial, redirect if needed
        if ( ($subscription->status == 'canceled' || is_trial_expired($subscription)) && !preg_match('/^(account\/billing|account\/profile)/', $request->path()) ) {
            return redirect('account/billing/subscription');
        }

        return $next($request);
    }
}
