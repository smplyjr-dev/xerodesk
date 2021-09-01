<?php

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

Route::get('/user/{user}/company', function ($user) {
    $model = User::with('company')->findOrFail($user);

    return $model->company;
});

Route::put('/user/{user}/company', function ($user) {
    $model = User::with('company')->findOrFail($user);
    $model->company()->update(request()->only(['name', 'address', 'url']));

    return $model->company;
});

Route::get('/session/{session}', function ($session) {
    $model = Session::with(['taggables', 'client.company', 'messages.attachments'])
        ->where('session', $session)
        ->first();

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
