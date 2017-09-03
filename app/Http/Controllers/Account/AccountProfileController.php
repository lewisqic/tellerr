<?php

namespace App\Http\Controllers\Account;

use App\User;
use App\Services\UserService;
use App\Http\Controllers\Controller;

class AccountProfileController extends Controller {

    /**
     * declare our services to be injected
     */
    protected $userService;


	/**
     * controller construct
     * @param UserService $user_service
     */
    public function __construct(UserService $user_service)
    {
        $this->userService = $user_service;
    }

    /**
     * show our edit administrator form page
     * @return view
     */
    public function index()
    {
        $data = [
            'user' => app('app_user'),
        ];
        return view('content.account.profile.index', $data);
    }

    /**
     * handle our administration creation request
     * @return redirect
     */
    public function update()
    {
        $user = $this->userService->update(app('app_user')->id, \Request::all());
        \Msg::success('Your profile has been updated successfully!');
        return redir('account/profile');
    }

}
