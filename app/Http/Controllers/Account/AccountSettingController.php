<?php

namespace App\Http\Controllers\Account;

use App\User;
use App\Services\CompanyService;
use App\Http\Controllers\Controller;

class AccountSettingController extends Controller {

    /**
     * declare our services to be injected
     */
    protected $companyService;


	/**
     * controller construct
     * @param CompanyService $company_service
     */
    public function __construct(CompanyService $company_service)
    {
        $this->companyService = $company_service;
    }

    /**
     * show our edit administrator form page
     * @return view
     */
    public function index()
    {
        $data = [];
        return view('content.account.settings.index', $data);
    }

    /**
     * handle our administration creation request
     * @return redir
     */
    public function update()
    {
        $user = $this->companyService->update(app('company')->id, \Request::all());
        \Msg::success('Your account settings has been updated successfully!');
        return redir('account/settings');
    }

}
