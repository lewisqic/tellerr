<?php

namespace App\Mail;

class UpgradeConfirmation extends BaseMailable
{

    /**
     * Declare our public properties
     */
    public $plan;
    public $amount;
    public $installment;
    public $next_billing_date;
    public $type;

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
        $subject = $this->type == 'new' ? 'You Have Successfully Upgraded to a Paid Account!' : 'You Have Successfully Resumed Your Subscription!';
        return $this->view('emails.account.upgrade-confirmation')
                    ->subject($subject);
    }
}
