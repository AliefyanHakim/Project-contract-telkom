<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\NotificationSettingController;

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.post');
});

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    Route::get(
        '/contracts-list',
        [ContractController::class, 'index']
    )->name('contracts.list');

    Route::resource('contracts', ContractController::class);

    Route::get(
        '/email-notifications',
        [NotificationSettingController::class, 'index']
    )->name('settings.email-notifications');

    Route::post(
        '/email-notifications',
        [NotificationSettingController::class, 'update']
    )->name('settings.email-notifications.update');

    Route::get('/contract-list', function () {
    return view('contracts.contract-list');
});

    Route::get('/closed-contract', function () {
    return view('contracts.closed-contract');
});

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});