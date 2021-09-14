<?php

use App\Models\Client\Client;
use App\Models\Client\Session;
use App\Models\User\User;
use Illuminate\Support\Facades\Route;

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

Route::get('/session/{session}', function ($session) {
    $model = Session::with(['taggables', 'client.company', 'messages.attachments', 'user.bio'])
        ->where('session', $session)
        ->first();

    return $model;
});

Route::put('/session/{session}/status', function ($session) {
    $model = Session::where('session', $session)->firstOrFail();

    if ($model->status != request()->status) {
        $model->update([
            'status' => request()->status
        ]);

        $model->messages()->create([
            'hash'    => request()->hash,
            'sender'  => request()->sender,
            'message' => request()->message
        ]);
    }

    return $model;
});

Route::put('/session/{session}/seen', function ($session) {
    $model = Session::with(['messages' => function ($query) {
        $query->where('is_read', false)
            ->whereIn('sender', ['client']);
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

Route::get('/user/{user}/company', function ($user) {
    $model = User::with('company')->findOrFail($user);

    return $model->company;
});

Route::put('/user/{user}/company', function ($user) {
    $model = User::with('company')->findOrFail($user);
    $model->company()->update(request()->only(['name', 'address', 'url']));

    return $model->company;
});
