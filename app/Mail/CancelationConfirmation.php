<?php

namespace App\Mail;

class CancelationConfirmation extends BaseMailable
{

    /**
     * Declare our public properties
     */
    public $cancelation_date;

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
        return $this->view('emails.account.cancelation-confirmation')
                    ->subject('Your Tellerr Account Has Been Canceled.');
    }
}
