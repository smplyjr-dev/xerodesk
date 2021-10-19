<?php

use App\Http\Controllers\Plugin\ClientController;
use App\Http\Controllers\Plugin\SessionController;
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

Route::get('/quickemailverification', function () {
    $apikey = env('QEV_API_KEY');
    $email  = request()->email;

    $curl_init = curl_init();
    curl_setopt($curl_init, CURLOPT_URL, "https://api.quickemailverification.com/v1/verify?email=$email&apikey=$apikey");
    curl_setopt($curl_init, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_init, CURLOPT_ENCODING, "");
    curl_setopt($curl_init, CURLOPT_TIMEOUT, 30000);
    curl_setopt($curl_init, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($curl_init, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($curl_init, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $err = curl_error($curl_init);
    $res = json_decode(curl_exec($curl_init));
    curl_close($curl_init);

    if (!$err) return response()->json($res, 200);
});

Route::get('/client/{token}/sessions', [ClientController::class, 'sessions']);
Route::get('/client/{token}',          [ClientController::class, 'show']);
Route::post('/client',                 [ClientController::class, 'store']);

Route::get('/session/{session}/messages', [SessionController::class, 'messages']);
Route::put('/session/{session}/seen',     [SessionController::class, 'seen']);
Route::post('/session',                   [SessionController::class, 'store']);
