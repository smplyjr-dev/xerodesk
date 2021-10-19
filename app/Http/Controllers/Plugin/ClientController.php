<?php

namespace App\Http\Controllers\Plugin;

use App\Http\Controllers\Controller;
use App\Models\Client\Client;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    public function show($token)
    {
        $model = Client::where('token', $token)->firstOrFail();

        return $model;
    }

    public function store()
    {
        request()->validate([
            'name'  => 'required|alpha_space',
            'email' => 'required|email|unique:clients'
        ]);

        $model = Client::create([
            'company_id' => 1,
            'token'      => Str::random(10),
            'name'       => request()->name,
            'email'      => request()->email,
            'phone'      => request()->phone
        ]);

        $model->sendWelcomeMessageNotification();

        return $model->fresh();
    }

    public function sessions($token)
    {
        $model = Client::with('sessions.messages')->where('token', $token)->firstOrFail();

        return $model->sessions;
    }
}
