<?php

namespace App\Observers\Client;

use App\Events\Message\MessageAttachment;
use App\Models\Client\Attachment;

class AttachmentObserver
{
    public function created(Attachment $attachment)
    {
        $message    = $attachment->message()->first();
        $attachment = $attachment;
        $session    = $attachment->message()->first()->session()->first();
        $client     = $attachment->message()->first()->session()->first()->client()->first();

        MessageAttachment::dispatch($message, $attachment, $session, $client);
    }

    // public function updated(Attachment $attachment) {}
    // public function deleted(Attachment $attachment) {}
    // public function restored(Attachment $attachment) {}
    // public function forceDeleted(Attachment $attachment) {}
}
