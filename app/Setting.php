<?php

namespace App;


class Setting extends BaseModel
{


    /******************************************************************
     * MODEL PROPERTIES
     ******************************************************************/


    /**
     * Declare rules for validation
     *
     * @var array
     */
    protected $rules = [
        'tab'           => 'required',
        'key'           => 'required|unique:settings,key',
        'label'         => 'required',
        'description'   => 'required',
        'is_required'   => 'required'
    ];


    /******************************************************************
     * CUSTOM  PROPERTIES
     ******************************************************************/



    /******************************************************************
     * CUSTOM ORM ACTIONS
     ******************************************************************/


    /**
     * return list of available setting tabs
     * @return array
     */
    public static function getTabs()
    {
        $settings = \DB::table('settings')->select('tab', 'tab_order')->groupBy('tab', 'tab_order')->orderBy('tab_order')->get();
        $settings = $settings->pluck('tab')->toArray();
        return $settings;
    }


}
