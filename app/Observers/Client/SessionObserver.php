<?php

namespace App\Observers\Client;

use App\Events\Session\SessionCreated;
use App\Events\Session\SessionUpdate;
use App\Models\Client\Session;

class SessionObserver
{
    public function created(Session $session)
    {
        $client = $session->client()->with(['company', 'sessions', 'sessions.messages'])->first();
        SessionCreated::dispatch($client);
    }

    public function updated(Session $session)
    {
        if (request()->status == 3 || request()->status == 4) $session->sendSessionMessagesNotification();
        SessionUpdate::dispatch($session);
    }

    // public function deleted(Session $session) {}
    // public function restored(Session $session) {}
    // public function forceDeleted(Session $session) {}
}
