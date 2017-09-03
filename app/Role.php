<?php

namespace App;

class Role extends BaseModel
{


    /******************************************************************
     * MODEL PROPERTIES
     ******************************************************************/


    /**
     * Declare rules for validation
     * @var array
     */
    protected $rules = [
        'type' => 'required',
        'name' => 'required',
    ];


    /******************************************************************
     * MODEL RELATIONSHIPS
     ******************************************************************/


    public function users()
    {
        return $this->belongsToMany('App\User', 'role_users');
    }


    /******************************************************************
     * MODEL HOOKS
     ******************************************************************/


    /******************************************************************
     * CUSTOM  PROPERTIES
     ******************************************************************/


    const DEFAULT_ACCOUNT_ROLE_NAME = 'Administrator';


    /******************************************************************
     * CUSTOM ORM ACTIONS
     ******************************************************************/


    /**
     * return all roles for a specific user type
     * @param  int $type
     * @return collection
     */
    public static function queryByType($type, $company_id = null)
    {
        $roles = Role::where('type', $type)->where('company_id', $company_id)->orderBy('name', 'asc')->get();
        return $roles;
    }


}
