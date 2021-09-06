<?php

use App\Models\Client\Client;
use App\Models\Client\Session;
use App\Models\Company\Company;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/client/{token}', function ($token) {
    $model = Client::where('token', $token)->firstOrFail();

    return $model;
});

Route::post('/client', function () {
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
});

Route::get('/client/{token}/sessions', function ($token) {
    $model = Client::with('sessions.messages')->where('token', $token)->firstOrFail();

    return $model->sessions;
});

Route::post('/session', function () {
    $abbr  = Company::first()->abbr;
    $count = Session::whereDate('created_at', Carbon::today()->toDateString())->count();
    $model = Session::create([
        'client_id' => request()->client_id,
        'session'   => 'XD' . $abbr . date('ymd') . ($count + 1)
    ]);

    return $model->fresh();
});

Route::get('/session/{session}/messages', function ($session) {
    $model = Session::with('messages', 'messages.attachments');
    $model = $model->where('session', $session);
    $model = $model->firstOrFail();

    return $model;
});

Route::put('/session/{session}/seen', function ($session) {
    $model = Session::with(['messages' => function ($query) {
        $query->where('is_read', false)
            ->whereIn('sender', ['admin', 'session']);
    }]);
    $model = $model->where('session', $session);
    $model = $model->first();

    foreach ($model->messages as $message) {
        $message->update([
            'is_read' => true
        ]);
    }

    return $model;
});
