<?php

namespace App;


class RemarkSetting extends BaseModel
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
        'user_id'           => 'required',
        'sidebar_minimized' => 'required',
        'navbar_inverse'    => 'required',
        'sidebar_skin'      => 'required',
        'primary_color'     => 'required',
    ];


    /******************************************************************
     * MODEL RELATIONSHIPS
     ******************************************************************/


    public function user()
    {
        return $this->belongsTo('\App\User');
    }


    /******************************************************************
     * CUSTOM  PROPERTIES
     ******************************************************************/

    const DEFAULT_SIDEBAR_MINIMIZED = 0;
    const DEFAULT_NAVBAR_INVERSE = 0;
    const DEFAULT_SIDEBAR_SKIN = 'dark';
    const DEFAULT_PRIMARY_COLOR = 'blue';


    /******************************************************************
     * CUSTOM ORM ACTIONS
     ******************************************************************/


}
