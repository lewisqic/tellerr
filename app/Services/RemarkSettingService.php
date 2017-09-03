<?php

namespace App\Services;

use App\RemarkSetting;

class RemarkSettingService extends BaseService
{


    /**
     * controller construct
     */
    public function __construct()
    {
    }

    /**
     * create a new remark setting record
     * @param  array  $data
     * @return array
     */
    public function create($user_id)
    {
        $data = [
            'user_id' => $user_id,
            'sidebar_minimized' => RemarkSetting::DEFAULT_SIDEBAR_MINIMIZED,
            'navbar_inverse' => RemarkSetting::DEFAULT_NAVBAR_INVERSE,
            'sidebar_skin' => RemarkSetting::DEFAULT_SIDEBAR_SKIN,
            'primary_color' => RemarkSetting::DEFAULT_PRIMARY_COLOR,
        ];
        $setting = RemarkSetting::create($data);
        return $setting;
    }

    /**
     * Save a remark setting
     * @param int $user_id
     * @param array $data
     * @return null
     */
    public function update($user_id, $data) {
        RemarkSetting::where('user_id', $user_id)->update($data);
    }


}