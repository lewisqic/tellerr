<?php

namespace App\Mail;

class PaymentFailed extends BaseMailable
{

    /**
     * Declare our public properties
     */
    public $amount;
    public $date;

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
        return $this->view('emails.account.payment-failed')
                    ->subject('Your Subscription Payment Failed');
    }
}
