<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailSessionCreated extends Mailable
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
        if ($this->receiver == 'admin') return $this->view('emails.session.created-admin');
        if ($this->receiver == 'client') return $this->view('emails.session.created-client');
    }
}
