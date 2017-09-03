<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Services\UserService;
use App\Http\Controllers\Controller;


class AuthIndexController extends Controller
{

    /**
     * declare our services to be injected
     */
    protected $userService;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserService $user_service)
    {
        $this->userService = $user_service;
    }


    /**
     * Show the login page
     *
     * @return view
     */
    public function showLogin()
    {
        $data = [];
        return view('content.auth.index.login', $data);
    }


    /**
     * show the forgot password page
     * @return view
     */
    public function showForgot()
    {
        return view('content.auth.index.forgot');
    }


    /**
     * show the reset password page
     * @param string $code
     * @return view
     */
    public function showReset($code)
    {
        $user = $this->userService->getUserFromReminderCode($code);
        $data = [
            'id' => $user->id,
            'email' => $user->email,
            'code' => $code
        ];
        return view('content.auth.index.reset', $data);
    }


    /**
     * Handle our login request
     *
     * @return json
     */
    public function handleLogin()
    {
        $response = $this->userService->login(\Request::only('email', 'password', 'remember'));
        return response()->json(['success' => true, 'route' => $response['route']]);
    }


    /**
     * handle our logout request
     * @return redirect
     */
    public function handleLogout()
    {
        $user = $this->userService->logout();
        \Msg::success('You have been logged out successfully!');
        return redir('auth/login');
    }


    /**
     * handle our password reminder request
     * @return redirect
     */
    public function handleForgot()
    {
        $this->userService->sendReminder(\Request::get('email'));
        \Msg::success('We just send you an email containing a link that can be used to reset your password.');
        return response()->json(['success' => true, 'route' => 'auth/login']);
    }


    /**
     * handle our reset password request
     * @return redirect
     */
    public function handleReset()
    {
        $response = $this->userService->resetPassword(\Request::all());
        \Msg::success('Your password has been reset successfully!');
        return response()->json(['success' => true, 'route' => $response['login'] ? $response['route'] : 'auth/login']);
    }

}
