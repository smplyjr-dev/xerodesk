<?php

namespace App\Observers\Client;

use App\Events\Message\MessageToAdmin;
use App\Events\Message\MessageToClient;
use App\Models\Client\Message;

class MessageObserver
{
    public function created(Message $message)
    {
        if ($message->message_from == 'client') {
            MessageToAdmin::dispatch($message, $message->session()->first()->session);
        }

        if ($message->message_from == 'admin' || $message->message_from == 'session') {
            MessageToClient::dispatch($message, $message->session->session, $message->session()->first()->client()->first()->token);
        }
    }

    // public function updated(Message $message) {}
    // public function deleted(Message $message) {}
    // public function restored(Message $message) {}
    // public function forceDeleted(Message $message) {}
}
