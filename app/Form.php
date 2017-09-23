<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class Form extends BaseModel
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
        'title'            => 'required'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'disable_date', 'recurring_start_fixed_date', 'created_at', 'updated_at', 'deleted_at'
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

    public function setDisableDateAttribute($value)
    {
        $this->attributes['disable_date'] = \Carbon::parse($value)->format('Y-m-d');
    }

    public function setRecurringStartFixedDateAttribute($value)
    {
        $this->attributes['recurring_start_fixed_date'] = \Carbon::parse($value)->format('Y-m-d');
    }

    /*public function getAmountAttribute($value) {
        return is_array($value) ? $value : json_decode($value, true);
    }
    public function getAmountDescriptionAttribute($value) {
        return is_array($value) ? $value : json_decode($value, true);
    }
    public function getAdditionalFieldsTitleAttribute($value) {
        return is_array($value) ? $value : json_decode($value, true);
    }
    public function getAdditionalFieldsDescriptionAttribute($value) {
        return is_array($value) ? $value : json_decode($value, true);
    }
    public function getAdditionalFieldsTypeAttribute($value) {
        return is_array($value) ? $value : json_decode($value, true);
    }
    public function getAdditionalFieldsMakeRequiredAttribute($value) {
        return is_array($value) ? $value : json_decode($value, true);
    }
    public function getAdditionalFieldsOptionsAttribute($value) {
        return is_array($value) ? $value : json_decode($value, true);
    }
    public function getComponentsSingleAttribute($value) {
        return is_array($value) ? $value : json_decode($value, true);
    }
    public function getComponentsDoubleAttribute($value) {
        return is_array($value) ? $value : json_decode($value, true);
    }*/


    /******************************************************************
     * CUSTOM  PROPERTIES
     ******************************************************************/

    public static $componentNameMap = [
        'left' => [
            'title' => 'Form Title',
            'description' => 'Form Description',
            'amount' => 'Amount / Fees',
            'frequency' => 'Payment Frequency',
        ],
        'right' => [
            'additional' => 'Additional Fields',
            'coupon' => 'Coupon Field',
            'payment' => 'Credit/Debit Card Input',
            'terms' => 'Terms & Conditions Checkbox',
        ],
    ];


    /******************************************************************
     * CUSTOM ORM ACTIONS
     ******************************************************************/

    /**
     * return all forms for a company
     * @param  int $type
     * @return collection
     */
    public static function queryByCompany($company_id, $with_trashed = false)
    {
        $forms = Form::where('company_id', $company_id)
            ->when($with_trashed, function($query) {
                return $query->withTrashed();
            })
            ->get();
        return $forms;
    }


}
