<?php

namespace App\Observers\Client;

use App\Events\Session\SessionCreated;
use App\Events\Session\SessionUpdate;
use App\Mail\MailSessionCreated;
use App\Models\Client\Session;
use Illuminate\Support\Facades\Mail;

class SessionObserver
{
    public function created(Session $session)
    {
        // send email to ithelpdesk@xmcbpo.com
        Mail::to('ithelpdesk@xmcbpo.com')->send(new MailSessionCreated($session, 'admin'));

        // send email to the client
        Mail::to($session->client->email)->send(new MailSessionCreated($session, 'client'));

        // fire an event for real-time purposes
        SessionCreated::dispatch($session);
    }

    public function updated(Session $session)
    {
        // fire an event for real-time purposes
        SessionUpdate::dispatch($session);
    }

    // public function deleted(Session $session) {}
    // public function restored(Session $session) {}
    // public function forceDeleted(Session $session) {}
}
