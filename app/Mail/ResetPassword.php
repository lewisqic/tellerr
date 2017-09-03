<?php

namespace App\Mail;

class ResetPassword extends BaseMailable
{

    /**
     * Declare our public properties
     */
    public $url;

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
        return $this->view('emails.auth.reset')
                    ->subject('Reset Your Account Password');
    }
}
