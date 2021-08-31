<?php

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

Route::namespace('Auth')->group(function () {
    Route::post('logout',   'LoginController@logout');

    Route::post('login',    'LoginController@login');
    Route::post('register', 'RegisterController@register');

    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'ResetPasswordController@reset');

    Route::post('email/verify/{user}', 'VerificationController@verify')->name('verification.verify');
    Route::post('email/resend',        'VerificationController@resend');
});

Route::namespace('Chatbot')->group(function () {
    Route::get('/chatbot/{id}/intents',     'ChatbotController@intents');
    Route::get('/chatbot/{id}/message',     'ChatbotController@message');
    Route::get('/chatbot/{id}/suggestions', 'ChatbotController@suggestions');
});

Route::namespace('Client')->group(function () {
    Route::get('/client/datatable',        'ClientController@datatable');
    Route::get('/client/{token}/verify',   'ClientController@verify');
    Route::get('/client/{token}/sessions', 'ClientController@sessions');
    Route::resource('/client',             'ClientController');

    Route::get('/message/session/{session}',  'MessageController@session');
    Route::post('/message/{hash}/attachment', 'MessageController@attachment');
    Route::resource('/message',               'MessageController');

    Route::get('/session/clients',            'SessionController@clients');
    Route::get('/session/{session}/tag',      'SessionController@tags');
    Route::post('/session/{session}/tag',     'SessionController@attach');
    Route::delete('/session/{session}/tag',   'SessionController@detach');
    Route::get('/session/{session}/verify',   'SessionController@verify');
    Route::get('/session/{session}/messages', 'SessionController@messages');
    Route::put('/session/{session}/transfer', 'SessionController@transfer');
    Route::resource('/session',               'SessionController');
});

Route::namespace('Company')->group(function () {
    Route::get('/company/verify', 'CompanyController@verify');
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::namespace('Chatbot')->group(function () {
        Route::resource('/chatbot',    'ChatbotController');
        Route::resource('/intent',     'IntentController');
        Route::resource('/suggestion', 'SuggestionController');
    });

    Route::namespace('Client')->group(function () {
        Route::get('/ticket/datatable', 'TicketController@datatable');
    });

    Route::namespace('Company')->group(function () {
        Route::get('/company/datatable', 'CompanyController@datatable');
        Route::get('/company/users',     'CompanyController@users');
        Route::resource('/company',      'CompanyController');
    });

    Route::namespace('Group')->group(function () {
        Route::get('/groups/datatable', 'GroupController@datatable');
        Route::resource('/groups',      'GroupController');
        Route::resource('/group-users', 'GroupUserController');
    });

    Route::namespace('Role')->group(function () {
        Route::get('/roles/datatable', 'RoleController@datatable');
        Route::resource('/roles',      'RoleController');
    });

    Route::namespace('SLA')->group(function () {
        Route::get('/slas/datatable', 'SLAController@datatable');
        Route::resource('/slas',      'SLAController');
    });

    Route::namespace('User')->group(function () {
        Route::get('/users/me',        'UserController@me');
        Route::get('/users/datatable', 'UserController@datatable');
        Route::post('/users/picture',  'UserController@picture');
        Route::resource('/users',      'UserController');
    });

    Route::namespace('Taggable')->group(function () {
        Route::resource('/tag', 'TaggableController');
    });
});
