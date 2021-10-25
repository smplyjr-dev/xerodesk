<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailSessionLock extends Mailable
{
    use Queueable, SerializesModels;

    public $session;
    public $receiver;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($session, $receiver)
    {
        $this->session = $session;
        $this->receiver = $receiver;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.session.lock');
    }
}
