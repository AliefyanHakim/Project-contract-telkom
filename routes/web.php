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

    Route::resource('contracts', ContractController::class);

    Route::get(
            '/email-notifications',
            [NotificationSettingController::class, 'index']
        )->name('settings.email-notifications');

    Route::post(
            '/email-notifications',
            [NotificationSettingController::class, 'update']
        )->name('settings.email-notifications.update');

    Route::get(
        '/contract-list',
        [ContractController::class, 'index']
    )->name('contract.list');

    Route::get(
        '/closed-contract',
        [ContractController::class, 'closedContracts']
    )->name('contract.closed');

    Route::get('/add-contract', function () {
    return view('contracts.add-contract');
    });

    Route::get('/detail-contract', function () {
    return view('contracts.add-contract');
    });

    Route::get('/account-manager-detail', function () {
    return view('am.detail-am');
    });

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});