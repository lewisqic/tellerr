<?php

namespace App;

class StatusHistory extends BaseModel
{


    /******************************************************************
     * MODEL PROPERTIES
     ******************************************************************/

    /**
     * Declare custom table name
     */
    protected $table = 'status_history';

    /**
     * Declare rules for validation
     * 
     * @var array
     */
    protected $rules = [
        'statusable_id'   => 'required',
        'statusable_type' => 'required',
        'status'          => 'required'
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


    // status_history
    public function statusable()
    {
        return $this->morphTo();
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

    /**
     * save a status change record
     * @param  array $data 
     * @return array
     */
    public static function store($model)
    {
        $status_history = new StatusHistory;
        $status_history->statusable_id = $model->id;
        $status_history->statusable_type = get_class($model);
        $status_history->status = $model->status;
        $status_history->save();
        return $status_history;
    }

}
