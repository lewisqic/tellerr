<?php

namespace App\Http\Controllers\Index;

use App\Plan;
use App\Member;
use App\Services\CompanyService;
use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Mail\SignUpConfirmation;

class IndexIndexController extends Controller
{

    /**
     * declare our services to be injected
     */
    protected $companyService;
    protected $userService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CompanyService $company_service, UserService $user_service)
    {
        $this->companyService = $company_service;
        $this->userService = $user_service;
    }

    /**
     * Show the home page
     *
     * @return view
     */
    public function showHome()
    {
        return view('content.index.index.home');
    }

    /**
     * show our pricing page
     * @return view
     */
    public function showPricing()
    {
        $data = [
        ];
        return view('content.index.index.pricing', $data);
    }

    /**
     * show our sign up page
     * @return view
     */
    public function showSignUp($id)
    {
        $data = [
            'plan' => Plan::findOrFail($id)
        ];
        return view('content.index.index.sign-up', $data);
    }

    /**
     * handle a member sign up request
     * @return redirect
     */
    public function handleSignUp()
    {
        $data = \Request::all();
        $result = \DB::transaction(function() use($data) {
            $data['type'] = Member::USER_TYPE_ID;
            // honeypot checking for valid submission
            $this->companyService->honeypotCheck($data);
            // get the plan
            $plan = Plan::findOrFail($data['plan_id']);
            // create the company
            list($company, $role) = $this->companyService->create($data);
            // create the user
            $data['company_id'] = $company->id;
            $data['roles'] = [$role->id];
            $data['is_owner'] = true;
            $user = $this->userService->create($data);
            // send confirmation email
            $mail_data = [
                'plan' => $plan->name,
                'price_month' => \Format::currency($plan->price_month),
                'price_year' => \Format::currency($plan->price_year),
                'trial_end_date' => \Carbon::now()->addDays(14)->toFormattedDateString()
            ];
            \Mail::to($company->email)->send(new SignUpConfirmation($mail_data));
            // find the user and log them in
            $auth_user = \Auth::findById($user->id);
            \Auth::login($auth_user, true);
            // set message and redirect
            \Msg::success('Thank you for signing up with Tellerr!');
            return [
                'route' => 'account'
            ];
        });
        return redir($result['route']);
    }

    /**
     * check a subdomain to see if it's valid or not
     * @return json
     */
    public function handleValidateSubdomain()
    {
        $this->companyService->validateSubdomain(\Request::input('subdomain'));
        return response()->json(['success' => true]);
    }


}
