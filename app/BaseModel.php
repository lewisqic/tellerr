<?php

namespace App;

use Watson\Validating\ValidatingTrait;

class BaseModel extends \Eloquent
{
    use ValidatingTrait;

    /**
     * Validation rules array
     *
     * @var array
     */
    protected $rules = [];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['password_confirmation'];

    /**
     * Setting to have model validation failure throw exception by default
     *
     * @var boolean
     */
    protected $throwValidationExceptions = true;


}
