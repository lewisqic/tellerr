<?php

namespace App;

class PlanChangeRequest extends BaseModel
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
        'company_subscription_id' => 'required',
        'plan_privilege'          => 'required',
        'plan_name'               => 'required',
        'plan_price_month'        => 'required',
        'plan_price_year'         => 'required',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at'
    ];


    /******************************************************************
     * MODEL RELATIONSHIPS
     ******************************************************************/


    // company_payments
    public function subscription()
    {
        return $this->hasMany('App\CompanySubscription', 'company_subscription_id');
    }


    /******************************************************************
     * MODEL HOOKS
     ******************************************************************/


    /******************************************************************
     * CUSTOM  PROPERTIES
     ******************************************************************/


    /******************************************************************
     * CUSTOM ORM ACTIONS
     ******************************************************************/


}
