<?php

use App\Models\Widget\Widget;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/iframe', function () {
    $key  = request()->key;
    $path = config('app.env') == 'local'
        ? 'http://xerodesk-widget.test/dist/'
        : 'widget/';

    return view('iframe', compact('key', 'path'));
});

Route::get('/{any}', function () {
    return view('xerodesk');
})->where('any', '.*');
