<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class RedirectIfAuthenticated
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
        $user = \Auth::check();
        if ( $user instanceof \Cartalyst\Sentinel\Users\EloquentUser ) {
            return redirect(User::$types[$user->type]['route']);
        }
        return $next($request);
    }
}
