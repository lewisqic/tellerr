<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyPaymentMethod extends BaseModel
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
        'company_id'          => 'required',
        'stripe_source_id'    => 'required',
        'cc_last4'            => 'required',
        'cc_expiration_month' => 'required',
        'cc_expiration_year'  => 'required',
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];


    /******************************************************************
     * MODEL RELATIONSHIPS
     ******************************************************************/


    // companies
    public function company()
    {
        return $this->belongsTo('\App\Company');
    }

    // companies
    public function payments()
    {
        return $this->hasMany('\App\CompanyPayment');
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
