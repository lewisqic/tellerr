<?php

namespace App\Http\Controllers\Account;

use App\User;
use App\Services\RemarkSettingService;
use App\Services\CompanyService;
use App\Http\Controllers\Controller;


class AccountIndexController extends Controller
{

    /**
     * declare our services to be injected
     */
    protected $remarkSettingService;
    protected $companyService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RemarkSettingService $remark_setting_service, CompanyService $company_service)
    {
        $this->remarkSettingService = $remark_setting_service;
        $this->companyService = $company_service;
    }

    /**
     * Show the dashboard page
     *
     * @return view
     */
    public function showDashboard()
    {
        return view('content.account.index.dashboard');
    }

    /**
     * Show the setup wizard page
     *
     * @return view
     */
    public function showSetupWizard()
    {
        $company = app('company');
        $data = [
            'stripe_account_created' => $company->stripe_account_id ? true : false
        ];
        return view('content.account.index.setup-wizard', $data);
    }

    /**
     * Save our setup wizard data
     *
     * @return json
     */
    public function saveSetupData()
    {
        $data = \Request::all();
        $data['setup_completed'] = true;
        $user = $this->companyService->update(app('company')->id, $data);
        \Msg::success('Setup wizard has been completed successfully!');
        return response()->json(['success' => true, 'route' => url('account')]);
    }

    /**
     * Create a new stripe account
     *
     * @return json
     */
    public function createStripeAccount()
    {
        $company = $this->companyService->createStripeAccount(app('company')->id, \Request::all());
        return response()->json(['success' => true]);
    }

    /**
     * Connect an existing stripe account
     *
     * @return json
     */
    public function connectStripeAccount()
    {
        $response = $this->companyService->connectStripeAccount(app('company')->id, \Request::all());
        if ( $response['success'] ) {
            \Msg::success('Your Stripe account has been connected successfully! You\'re now ready to start accepting live payments.');
            return redirect('account');
        } else {
            \Msg::danger($response['message']);
            return redirect('account/setup');
        }
    }

    /**
     * Save our remark setting
     *
     * @return json
     */
    public function saveRemarkSetting()
    {
        $this->remarkSettingService->update(app('app_user')->id, [\Request::get('key') => \Request::get('value')]);
        return response()->json(['success' => true]);
    }


}
