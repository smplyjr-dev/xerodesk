<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMessages extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $client;
    public $messages;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $client, $messages)
    {
        $this->user = $user;
        $this->client = $client;
        $this->messages = $messages;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.send-messages');
    }
}
