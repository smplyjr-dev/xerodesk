<?php

use App\Http\Controllers\Portal\ClientController;
use App\Http\Controllers\Portal\CompanyController;
use App\Http\Controllers\Portal\SessionController;
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

Route::get('/client/recent', [ClientController::class, 'recent']);

Route::get('/session/export',           [SessionController::class, 'export']);
Route::get('/session/{session}',        [SessionController::class, 'show']);
Route::put('/session/{session}/status', [SessionController::class, 'status']);
Route::put('/session/{session}/seen',   [SessionController::class, 'seen']);

Route::get('/company/{company}', [CompanyController::class, 'show']);
Route::put('/company/{company}', [CompanyController::class, 'update']);
