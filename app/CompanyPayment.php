<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyPayment extends BaseModel
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
        'company_id'                => 'required',
        'company_subscription_id'   => 'required',
        'company_payment_method_id' => 'required',
        'stripe_charge_id'          => 'required',
        'amount'                    => 'required',
        'currency'                  => 'required',
        'status'                    => 'required',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'refunded_at', 'created_at', 'updated_at', 'deleted_at'
    ];


    /******************************************************************
     * MODEL RELATIONSHIPS
     ******************************************************************/


    // companies
    public function company()
    {
        return $this->belongsTo('\App\Company');
    }

    // subscriptions
    public function subscription()
    {
        return $this->belongsTo('\App\CompanySubscription', 'company_subscription_id');
    }

    // company_payment_methods
    public function paymentMethod()
    {
        return $this->belongsTo('\App\CompanyPaymentMethod', 'company_payment_method_id')->withTrashed();
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
