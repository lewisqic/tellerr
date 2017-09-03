<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Services\UserService;
use App\Http\Controllers\Controller;


class ApiIndexController extends Controller
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
    public function serveIndex()
    {
        $user = \Api::getUser();
        sd($user);
    }

}
