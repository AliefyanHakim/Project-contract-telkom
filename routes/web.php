<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AmController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationSettingController;
use App\Http\Controllers\ProfileController;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin']);

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
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

        Route::get('/contract-files/{file}/download', [ContractController::class, 'downloadFile'])
            ->name('contract-files.download');

        Route::get('/contract-files/{file}/view', [ContractController::class, 'viewFile'])
            ->name('contracts.view');

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
| Account Manager membuat request transfer.
| Manager melihat request, approve/reject, dan bisa direct transfer.
*/

Route::middleware('role:manager,account_manager')->group(function () {
    Route::get('/transfer-contract', function () {
        return view('transfer.transfer-contract');
    });
});

Route::middleware('role:manager')->group(function () {
    Route::get('/transfer-request', function () {
        return view('transfer.transfer-request');
    });

    Route::get('/acceptreject-transfer', function () {
        return view('transfer.acceptreject-transfer');
    });

    Route::get('/accepted-transfer', function () {
        return view('transfer.accepted-transfer');
    });

    Route::get('/rejected-transfer', function () {
        return view('transfer.rejected-transfer');
    });

    Route::get('/direct-transfer', function () {
        return view('transfer.direct-transfer');
    });
});

    /*
    |--------------------------------------------------------------------------
    | Email Notifications / Reminder
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:manager,support_inputter,support_paycall')->group(function () {
        Route::get('/email-notifications', [NotificationSettingController::class, 'index'])
            ->name('email.notifications');

        Route::post('/email-notifications', [NotificationSettingController::class, 'update'])
            ->name('settings.email-notifications.update');
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