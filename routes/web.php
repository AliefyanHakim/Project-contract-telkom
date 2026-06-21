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

    Route::get(
        '/add-contract',
        [ContractController::class, 'create']
    )->name('contracts.create');

    Route::get(
        '/contracts/{contract}',
        [ContractController::class, 'show']
    )->name('contracts.show');

    Route::delete(
    '/contracts/{contract}',
    [ContractController::class, 'destroy']
    )->name('contracts.destroy');

    Route::get(
        '/account-manager-detail',function () {
    return view('am.detail-am');
    })->name('am.detail-am');

    Route::get(
    '/contracts/{contract}/edit',
    [ContractController::class, 'edit']
    )->name('contracts.edit');

    Route::put(
        '/contracts/{contract}',
        [ContractController::class, 'update']
    )->name('contracts.update');

    Route::get('/billing/outstanding', function () {
        return view('billing.outstanding');
    });

    Route::get('/billing/payment-history', function () {
        return view('billing.payment-history');
    });

    Route::get('/contract-alerts', function () {
        return view('alerts.contract-alerts');
    });

    Route::get('/transfer-request', function () {
        return view('transfer.transfer-request');
    });

    Route::get('/direct-transfer', function () {
        return view('transfer.direct-transfer');
    });

    Route::get('/transfer-contract', function () {
        return view('transfer.transfer-contract');
    });

    Route::get('acceptreject-transfer', function () {
        return view('transfer.acceptreject-transfer');
    });

    Route::get('accepted-transfer', function () {
        return view('transfer.accepted-transfer');
    });

    Route::get('rejected-transfer', function () {
        return view('transfer.rejected-transfer');
    });

    Route::get('profile', function () {
        return view('settings.profile');
    });

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});