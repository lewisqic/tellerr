<?php

namespace App\Mail;

class SignUpConfirmation extends BaseMailable
{

    /**
     * Declare our public properties
     */
    public $plan;
    public $price_month;
    public $price_year;
    public $trial_end_date;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data = [])
    {
        foreach ( $data as $key => $value ) {
            $this->{$key} = $value;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.index.sign-up-confirmation')
                    ->subject('Thank You For Signing Up With Tellerr!');
    }
}
