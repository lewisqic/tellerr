<?php

namespace App\Services;

use App\Setting;

class SettingService extends BaseService
{


    /**
     * controller construct
     */
    public function __construct()
    {
    }

    /**
     * Save a remark setting
     * @param int $user_id
     * @param array $data
     * @return null
     */
    public function update($data) {
        foreach ( $data as $key => $value ) {
            Setting::where('key', $key)->update(['value' => $value]);
        }
    }


}