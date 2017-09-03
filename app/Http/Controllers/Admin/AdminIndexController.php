<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Services\RemarkSettingService;
use App\Http\Controllers\Controller;


class AdminIndexController extends Controller
{

    /**
     * declare our services to be injected
     */
    protected $remarkSettingService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RemarkSettingService $remark_setting_service)
    {
        $this->remarkSettingService = $remark_setting_service;
    }

    /**
     * Show the login page
     *
     * @return view
     */
    public function showDashboard()
    {
        return view('content.admin.index.dashboard');
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
