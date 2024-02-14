<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user, $verification_code;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $verification_code)
    {
        $this->user = $user;
        $this->verification_code = $verification_code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->from(get_option('MAIL_FROM_ADDRESS'), get_option('app_name'))
            ->subject('Forgot Password Verification '. get_option('app_name'))
            ->markdown('mail.forgot-verification')
            ->with([
                'user' => $this->user,
                'verification_code' => $this->verification_code,
            ]);
    }
}
