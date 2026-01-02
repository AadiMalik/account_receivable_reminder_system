<?php

use App\Http\Controllers\ErpSyncLogController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\UserController;
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
// Route::get('/dashboard', function () {
//     return view('dashboard');
// });
// Route::get('/customer', function () {
//     return view('customer.index');
// });
// Route::get('/customer/detail', function () {
//     return view('customer.detail');
// });

// Route::get('/invoice', function () {
//     return view('invoice.index');
// });
// Route::get('/invoice/detail', function () {
//     return view('invoice.detail');
// });

Route::get('/whatsapp', function () {
    return view('whatsapp.index');
});

Route::get('/erp-sync', function () {
    return view('erp_sync.index');
});


//dynamic
Route::middleware(['auth'])->group(function () {
    //ery syn
    Route::get('/erp-sync', [ErpSyncLogController::class, 'index'])->name('erp.sync');
    Route::post('/erp-sync/run', [ErpSyncLogController::class, 'sync'])->name('erp.sync.run');
    //dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    // Customers
    Route::get('/customer', [CustomerController::class, 'index']);
    Route::get('/customer/detail/{customer_code}', [CustomerController::class, 'detail']);

    // Invoices
    Route::get('/invoice', [InvoiceController::class, 'index']);
    Route::get('/invoice/detail/{invoice_id}', [InvoiceController::class, 'detail']);

    //company
    Route::get('/company', [CompanyController::class, 'index']);
    Route::post('/company', [CompanyController::class, 'store']);
    Route::get('/company/{id}/edit', [CompanyController::class, 'edit']);
    Route::put('/company/{id}', [CompanyController::class, 'update']);
    Route::delete('/company/{id}', [CompanyController::class, 'destroy']);

    Route::get('/company/{company}/login', [CompanyController::class, 'loginAsCompany']);
    Route::get('/company/restore', [CompanyController::class, 'restoreAdmin'])->name('company.restore');

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    Route::get('/setting', [CompanyController::class, 'settings'])->name('settings');
    Route::post('/setting/company', [CompanyController::class, 'updateCompany'])->name('settings.update.company');
    Route::post('/setting/whatsapp', [CompanyController::class, 'updateWhatsApp'])->name('settings.update.whatsapp');
    Route::post('/setting/erp', [CompanyController::class, 'updateERP'])->name('settings.update.erp');
    Route::post('/setting/reminders', [CompanyController::class, 'updateReminders'])->name('settings.update.reminders');
    Route::post('/setting/toggle-whatsapp/{company}', [CompanyController::class, 'toggleWhatsApp'])->name('settings.toggle.whatsapp');
    Route::post('/setting/toggle-erp/{company}', [CompanyController::class, 'toggleERP'])->name('settings.toggle.erp');
});
