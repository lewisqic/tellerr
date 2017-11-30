<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class Theme extends BaseModel
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

    // companies
    public function company()
    {
        return $this->belongsTo('App\Company');
    }


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
     * return all themes for a company
     * @param  int $type
     * @return collection
     */
    public static function queryByCompany($company_id, $with_trashed = false)
    {
        $themes = Theme::where('company_id', $company_id)
            ->when($with_trashed, function($query) {
                return $query->withTrashed();
            })
            ->get();
        return $themes;
    }


}
