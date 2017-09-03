<?php

namespace App;

use App\Administrator;
use App\Member;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends BaseModel
{
    use SoftDeletes;


    /******************************************************************
     * MODEL PROPERTIES
     ******************************************************************/


    /**
     * Declare rules for validation
     *
     * @var array
     */
    protected $rules = [
        'first_name'            => 'required|between:2,80',
        'last_name'             => 'required|between:2,80',
        'email'                 => 'required|between:5,64|email|unique:users,email',
        'password'              => 'required|min:6'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'last_login', 'created_at', 'updated_at', 'deleted_at'
    ];


    /******************************************************************
     * MODEL RELATIONSHIPS
     ******************************************************************/


    // remark_settings
    public function remarkSetting()
    {
        return $this->hasOne('\App\RemarkSetting');
    }
    // administrators
    public function administrator()
    {
        return $this->hasOne('\App\Administrator');
    }
    // members
    public function member()
    {
        return $this->hasOne('\App\Member');
    }
    // roles
    public function roles()
    {
        return $this->belongsToMany('App\Role', 'role_users');
    }


    /******************************************************************
     * MODEL HOOKS
     ******************************************************************/



    /******************************************************************
     * MODEL ACCESSORS/MUTATORS
     ******************************************************************/

    /**
     * Declare custom property
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Hash password if needed
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        if ( is_null($value) ) {
            $this->attributes['password'] = $this->attributes['password'];
        } else {
            $this->attributes['password'] = \Hash::make($value);
        }
    }


    /******************************************************************
     * CUSTOM  PROPERTIES
     ******************************************************************/


    /**
     * Declare our user types and their default route and database table
     * @var array
     */
    public static $types = [
        Administrator::USER_TYPE_ID => [
            'route' => 'admin',
            'table' => 'administrator'
        ],
        Member::USER_TYPE_ID => [
            'route' => 'account',
            'table' => 'member'
        ],
    ];


    /******************************************************************
     * CUSTOM ORM ACTIONS
     ******************************************************************/


    /**
     * return all users for a specific user type
     * @param  int $type
     * @return collection
     */
    public static function queryByType($type, $with_trashed = false, $company_id = null)
    {
        $query = User::with(User::$types[$type]['table'])
                ->where('type', $type)
                ->when($with_trashed, function($query) {
                    return $query->withTrashed();
                });
        if ( !is_null($company_id) ) {
            $query->whereHas('member', function($query) use($company_id) {
                $query->where('company_id', $company_id);
            });
        }
        $users = $query->get();
        return $users;
    }

}
