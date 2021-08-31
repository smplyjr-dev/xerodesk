<?php

use App\Models\Client\Session;
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

Route::get('/session/{session}/messages', function () {
    $model = Session::with('messages', 'messages.attachments');
    $model = $model->where('session', request()->session);
    $model = $model->firstOrFail();

    return $model;
});

Route::put('/session/{session}/seen', function ($session) {
    $model = Session::with(['messages' => function ($query) {
        $query->where('is_read', false)
            ->where('message_from', ['admin', 'session']);
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
