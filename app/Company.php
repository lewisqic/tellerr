<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends BaseModel
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
        'subdomain' => 'required|unique:companies,subdomain',
    ];

    /**
     * Declare custom validation error messages
     * @var array
     */
    protected $validationMessages = [
        'subdomain.unique' => 'The requested domain name has already been taken, please try a different one.',
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


    // members
    public function members()
    {
        return $this->hasMany('App\Member');
    }
    // company_subscriptions
    public function subscription()
    {
        return $this->hasOne('App\CompanySubscription');
    }
    // company_payment_methods
    public function paymentMethods()
    {
        return $this->hasMany('App\CompanyPaymentMethod')->orderBy('is_default', 'desc');
    }
    // company_payments
    public function payments()
    {
        return $this->hasMany('App\CompanyPayment');
    }
    // forms
    public function forms()
    {
        return $this->hasMany('App\Form');
    }


    /******************************************************************
     * MODEL HOOKS
     ******************************************************************/


    /******************************************************************
     * CUSTOM  PROPERTIES
     ******************************************************************/

    const DEFAULT_CURRENCY = 'USD';
    const DEFAULT_LANGUAGE = 'English';

    /******************************************************************
     * CUSTOM ORM ACTIONS
     ******************************************************************/

    public static function findBySubdomain($subdomain)
    {
        $company = Company::where('subdomain', $subdomain)->first();
        return $company;
    }


}
