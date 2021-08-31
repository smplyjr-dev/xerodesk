<?php

namespace App\Traits\Client;

use App\Models\Client\Session;

trait SessionTagTrait
{
    public function tags($session)
    {
        $response = Session::whereSession($session)->first()->taggables;

        return response()->json($response, 200);
    }

    public function attach($session)
    {
        $session = Session::whereSession($session)->firstOrFail();
        $session->taggables()->attach(request()->taggable_id);

        return response()->json(request()->taggable_id, 200);
    }

    public function detach($session)
    {
        $session = Session::whereSession($session)->firstOrFail();
        $session->taggables()->detach(request()->taggable_id);

        return response()->json(request()->taggable_id, 200);
    }
}
