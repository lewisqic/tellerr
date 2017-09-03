<?php

namespace App\Http\Controllers\Admin;

use App\Setting;
use App\Services\SettingService;
use App\Http\Controllers\Controller;


class AdminSettingController extends Controller
{

    /**
     * declare our services to be injected
     */
    protected $settingService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SettingService $setting_service)
    {
        $this->settingService = $setting_service;
    }

    /**
     * Show the login page
     *
     * @return view
     */
    public function index()
    {
        $settings = [];
        foreach ( Setting::all() as $setting ) {
            $settings[$setting->tab][] = $setting;
        }
        $data = [
            'tabs' => Setting::getTabs(),
            'settings' => $settings
        ];
        return view('content.admin.settings.index', $data);
    }

    /**
     * Save our remark setting
     *
     * @return json
     */
    public function update()
    {
        $this->settingService->update(\Request::input('settings'));
        \Msg::success('Settings have been updated successfully!');
        return redir('admin/settings');
    }


}
