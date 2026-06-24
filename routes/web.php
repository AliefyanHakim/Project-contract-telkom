<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AmController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationSettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BasoFileController;
use App\Http\Controllers\ContractFileController;
use App\Http\Controllers\TransferController;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin']);

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.post');
});

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    | Semua role boleh masuk dashboard.
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Lihat Kontrak
    |--------------------------------------------------------------------------
    | Semua role boleh lihat kontrak.
    | Account Manager nanti difilter di ContractController.
    */
    Route::middleware('role:manager,account_manager,support_inputter,support_paycall')->group(function () {
        Route::get('/contract-list', [ContractController::class, 'index'])
            ->name('contract.list');

        Route::get('/contracts', [ContractController::class, 'index'])
            ->name('contracts.index');

        Route::get('/closed-contract', [ContractController::class, 'closedContracts'])
            ->name('contract.closed');

        Route::get('/contracts/{contract}', [ContractController::class, 'show'])
            ->name('contracts.show');

        Route::get('/contract-files/{file}/download', [ContractFileController::class, 'download'])
            ->name('contract-files.download');

        Route::get('/contract-files/{file}/view', [ContractController::class, 'viewFile'])
            ->name('contracts.view');

        Route::get('/baso/{baso}/download',[BasoFileController::class, 'download']
            )->name('baso.download');

        Route::get('/billing', function () {
            return view('billing.outstanding');
        });

        Route::get('/billing/payment-history', function () {
            return view('billing.payment-history');
        });

        Route::get('/contract-alerts', function () {
            return view('alerts.contract-alerts');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Tambah Kontrak
    |--------------------------------------------------------------------------
    | Manager tidak boleh tambah kontrak.
    */
    Route::middleware('role:account_manager,support_inputter,support_paycall')->group(function () {
        Route::get('/add-contract', [ContractController::class, 'create'])
            ->name('contracts.create');

        Route::post('/contracts', [ContractController::class, 'store'])
            ->name('contracts.store');
    });

    /*
    |--------------------------------------------------------------------------
    | Edit Kontrak
    |--------------------------------------------------------------------------
    | Manager tidak boleh edit kontrak.
    | Support Paycall tidak boleh edit kontrak.
    */
    Route::middleware('role:account_manager,support_inputter,support_paycall')->group(function () {
        Route::get('/contracts/{contract}/edit', [ContractController::class, 'edit'])
            ->name('contracts.edit');

        Route::put('/contracts/{contract}', [ContractController::class, 'update'])
            ->name('contracts.update');
    });

    /*
    |--------------------------------------------------------------------------
    | By Account Manager
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:manager,support_inputter,support_paycall')->group(function () {
        Route::get('/account-manager/{user}', [AmController::class, 'show'])
            ->name('account-managers.show');

        Route::get('/account-manager/{user}/export', [AmController::class, 'export'])
            ->name('account-managers.export');

        Route::get('/detailam', function () {
            return view('am.detail-am');
        });
    });

 /*
|--------------------------------------------------------------------------
| Transfer
|--------------------------------------------------------------------------
| Manager, AM, dan Support Inputter boleh melihat halaman transfer.
| Paycall tidak boleh akses transfer.
| Inputter hanya view-only karena POST hanya untuk Manager dan AM.
*/

Route::middleware('role:manager,account_manager,support_inputter')->group(function () {
    Route::get('/transfer-request', [TransferController::class, 'requests'])
        ->name('transfer.requests');

    Route::get('/direct-transfer', [TransferController::class, 'directHistory'])
        ->name('transfer.direct-history');

    Route::get('/transfer-contract', [TransferController::class, 'create'])
        ->name('transfer.create');
});

Route::middleware('role:manager,account_manager')->group(function () {
    Route::post('/transfer-contract', [TransferController::class, 'store'])
        ->name('transfer.store');
});

/*
|--------------------------------------------------------------------------
| Transfer Approval
|--------------------------------------------------------------------------
| Accept dan Reject khusus Manager.
| Logic database-nya kita buat di step berikutnya.
*/

Route::middleware('role:manager')->group(function () {
    Route::get('/acceptreject-transfer', function () {
        return view('transfer.acceptreject-transfer');
    });

    Route::get('/accepted-transfer', function () {
        return view('transfer.accepted-transfer');
    });

    Route::get('/rejected-transfer', function () {
        return view('transfer.rejected-transfer');
    });
});

    /*
    |--------------------------------------------------------------------------
    | Email Notifications / Reminder
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:manager,support_inputter,support_paycall')->group(function () {

        Route::get(
            '/email-notifications',
            [NotificationSettingController::class, 'index']
        )->name('email.notifications');

        Route::post(
            '/email-notifications',
            [NotificationSettingController::class, 'update']
        )->name('settings.email-notifications.update');
    });

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    | Semua role boleh akses profile.
    */
    Route::get('/profile', function () {
        return view('settings.profile');
    })->name('profile');

    Route::get('/profile/edit', function () {
        return view('settings.edit-profile');
    })->name('profile.edit');

    Route::put('/profile/update', [ProfileController::class, 'update'])
        ->name('profile.update');
});