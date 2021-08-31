<?php

namespace App\Observers\Client;

use App\Events\Session\SessionCreated;
use App\Events\Session\SessionEnded;
use App\Events\Session\SessionUpdate;
use App\Models\Client\Session;

class SessionObserver
{
    public function created(Session $session)
    {
        // get client details
        $client = $session->client()->with(['company', 'sessions', 'sessions.messages'])->first();

        // dispatch an event
        SessionCreated::dispatch($client);
    }

    public function updated(Session $session)
    {
        // for resolve or closed status
        if (request()->status == 3 || request()->status == 4) {
            SessionEnded::dispatch($session);
            $session->sendSessionMessagesNotification();
        }

        SessionUpdate::dispatch($session);
    }

    // public function deleted(Session $session) {}
    // public function restored(Session $session) {}
    // public function forceDeleted(Session $session) {}
}
