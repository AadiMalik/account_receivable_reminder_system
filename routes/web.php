<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route::get('company', [HomeController::class, 'adminHome'])->name('admin.home')->middleware('is_admin');
Route::get('/company', function () {
    return view('company.index');
});
Route::get('/dashboard', function () {
    return view('dashboard');
});
Route::get('/customer', function () {
    return view('customer.index');
});
Route::get('/customer/detail', function () {
    return view('customer.detail');
});

Route::get('/invoice', function () {
    return view('invoice.index');
});
Route::get('/invoice/detail', function () {
    return view('invoice.detail');
});

Route::get('/whatsapp', function () {
    return view('whatsapp.index');
});

Route::get('/erp-sync', function () {
    return view('erp_sync.index');
});

Route::get('/setting', function () {
    return view('setting');
});