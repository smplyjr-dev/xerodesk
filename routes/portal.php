<?php

use App\Http\Controllers\Portal\ClientController;
use App\Http\Controllers\Portal\GroupController;
use App\Http\Controllers\Portal\MessageController;
use App\Http\Controllers\Portal\RoleController;
use App\Http\Controllers\Portal\SessionController;
use App\Http\Controllers\Portal\SLAController;
use App\Http\Controllers\Portal\UserController;
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

Route::group(['middleware' => 'auth:api'], function () {
    // Client Routes
    Route::get('/client/datatable',        [ClientController::class, 'datatable']);
    Route::get('/client/recent',           [ClientController::class, 'recent']);
    Route::get('/client/{token}/sessions', [ClientController::class, 'sessions']);

    // Group Routes
    Route::get('/group/datatable', [GroupController::class, 'datatable']);

    // Message Routes
    Route::post('/message/{hash}/attachment', [MessageController::class, 'attachment']);

    // Role Routes
    Route::get('/role/datatable', [RoleController::class, 'datatable']);

    // Session Routes
    Route::get('/session/datatable',            [SessionController::class, 'datatable']);
    Route::get('/session/export',               [SessionController::class, 'export']);
    Route::get('/session/{session}/tag',        [SessionController::class, 'tags']);
    Route::post('/session/{session}/tag',       [SessionController::class, 'attach']);
    Route::delete('/session/{session}/tag',     [SessionController::class, 'detach']);
    Route::get('/session/{session}/transcript', [SessionController::class, 'transcript']);
    Route::put('/session/{session}/transfer',   [SessionController::class, 'transfer']);
    Route::put('/session/{session}/status',     [SessionController::class, 'status']);
    Route::put('/session/{session}/seen',       [SessionController::class, 'seen']);
    Route::put('/session/{session}/lock',       [SessionController::class, 'lock']);
    Route::put('/session/{session}/field',      [SessionController::class, 'field']);

    // SLA Policy Routes
    Route::get('/sla/datatable', [SLAController::class, 'datatable']);

    // User Routes
    Route::get('/user/datatable', [UserController::class, 'datatable']);
    Route::get('/user/me',        [UserController::class, 'me']);
    Route::post('/user/picture',  [UserController::class, 'picture']);

    // All Resources Routes
    Route::namespace('Portal')->group(function () {
        Route::resource('company',    'CompanyController');
        Route::resource('group',      'GroupController');
        Route::resource('group-user', 'GroupUserController');
        Route::resource('message',    'MessageController');
        Route::resource('role',       'RoleController');
        Route::resource('session',    'SessionController');
        Route::resource('sla',        'SLAController');
        Route::resource('tag',        'TaggableController');
        Route::resource('user',       'UserController');
    });
});
