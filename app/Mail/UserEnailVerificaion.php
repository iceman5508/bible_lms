<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserEnailVerificaion extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(get_option('MAIL_FROM_ADDRESS'), get_option('app_name'))
            ->subject('Account Verification '. get_option('app_name'))
            ->markdown('mail.email-verification')
            ->with([
                'user' => $this->user,
                'link' => route('user.email.verification',$this->user->remember_token)
            ]);
    }
}
