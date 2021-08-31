<?php

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

// $decrypted = [
//     "company_name" => "Xerosoft Local",
//     "company_url"  => "http://dev.payroll-hr-leave-app.com",
//     "employee_id"  => 1,
//     "user_id"      => 1,
//     "user_email"   => "alfredo@xerosoft.com",
//     "user_name"    => "Alfredo Flores",
//     "user_phone"   => "+639352254687",
//     "user_picture" => "path/to/picture"
// ];

Route::get('/iframe', function () {
    $path = config('app.env') == 'local'
        ? 'http://filichat-widget.test/dist/'
        : 'widget/';

    return view('iframe', compact('path'));
});

Route::get('/implementation', function () {
    return view('implementation');
});

Route::get('/{any}', function () {
    return view('filichat');
})->where('any', '.*');
