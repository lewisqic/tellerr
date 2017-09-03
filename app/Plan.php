<?php

namespace App;

use App\Administrator;
use App\Member;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends BaseModel
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
        'name'            => 'required'
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



    /******************************************************************
     * MODEL HOOKS
     ******************************************************************/



    /******************************************************************
     * MODEL ACCESSORS/MUTATORS
     ******************************************************************/




    /******************************************************************
     * CUSTOM  PROPERTIES
     ******************************************************************/




    /******************************************************************
     * CUSTOM ORM ACTIONS
     ******************************************************************/

    /**
     * query for all plans for the pricing page
     * @return collection
     */
    public static function forPricingPage()
    {
        $plans = Plan::orderBy('privilege', 'asc')->get();
        return $plans;
    }


}
