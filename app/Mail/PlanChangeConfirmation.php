<?php

namespace App\Mail;

class PlanChangeConfirmation extends BaseMailable
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
        $subject = $this->type == 'upgrade' ? 'Your Plan Change Has Been Completed Successfully!' : 'Your Plan Change Has Been Scheduled Successfully!';
        return $this->view('emails.account.plan-change-confirmation')
                    ->subject($subject);
    }
}
