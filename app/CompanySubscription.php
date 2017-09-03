<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class CompanySubscription extends BaseModel
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
        'company_id'       => 'required',
        'plan_privilege'   => 'required',
        'plan_name'        => 'required',
        'plan_price_month' => 'required',
        'plan_price_year'  => 'required',
        'status'           => 'required'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'trial_ends_at', 'canceled_at', 'next_billing_at', 'created_at', 'updated_at', 'deleted_at'
    ];


    /******************************************************************
     * MODEL RELATIONSHIPS
     ******************************************************************/


    // members
    public function company()
    {
        return $this->belongsTo('App\Company');
    }
    // company_payments
    public function payments()
    {
        return $this->hasMany('App\CompanyPayment');
    }
    // company_payment_methods
    public function paymentMethods()
    {
        return $this->hasMany('App\CompanyPaymentMethod');
    }
    // plan_change_requests
    public function planChangeRequest()
    {
        return $this->hasOne('App\PlanChangeRequest');
    }
    // status_history
    public function statusHistory()
    {
        return $this->morphMany('App\StatusHistory', 'statusable');
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
