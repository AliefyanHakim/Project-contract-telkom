<?php

use App\Models\User;
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
use App\Http\Controllers\BillingController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\ActivityLogController;

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
    | Activity Logs
    |--------------------------------------------------------------------------
    | Hanya Manager yang dapat memonitor aktivitas sistem.
    */
    Route::middleware('role:manager')->group(function () {
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])
            ->name('activity.logs');
    });
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

        Route::get('/contract-report/{type}', [ContractController::class, 'exportReport'])
            ->name('contracts.report');

        Route::get('/contracts/{contract}', [ContractController::class, 'show'])
            ->name('contracts.show');

        Route::get('/contract-files/{file}/download', [ContractFileController::class, 'download'])
            ->name('contract-files.download');

        Route::get('/contract-files/{file}/view', [ContractController::class, 'viewFile'])
            ->name('contracts.view');

        Route::get('/baso/{baso}/view', [BasoFileController::class, 'view'])
            ->name('baso.view');

        Route::get('/baso/{baso}/download', [BasoFileController::class, 'download'])
            ->name('baso.download');

        Route::get('/billing',
            [BillingController::class,'outstanding']);

        Route::get('/billing/payment-history',
            [BillingController::class,'paymentHistory']);   
    });

 /*
|--------------------------------------------------------------------------
| Contract Alerts
|--------------------------------------------------------------------------
| Account Manager melihat alert kontraknya sendiri.
| Support Inputter melihat semua alert kontrak seluruh AM.
| Manager dan Paycall tidak bisa akses.
*/
Route::middleware('role:account_manager,support_inputter')->group(function () {

    Route::get('/contract-alerts', [AlertController::class, 'index'])
        ->name('contract.alerts');

    Route::get('/contract-alerts/report', [AlertController::class, 'exportReport'])
        ->name('contract.alerts.report');

});

    /*
|--------------------------------------------------------------------------
| Billing Action
|--------------------------------------------------------------------------
| Semua role tertentu boleh lihat billing.
| Hanya Support Paycall yang boleh update status pembayaran.
*/
Route::middleware('role:support_paycall')->group(function () {
    Route::patch('/billing/{billing}/status', [BillingController::class, 'updateStatus'])
        ->name('billing.update-status');
});

   /*
    |--------------------------------------------------------------------------
    | Tambah Kontrak
    |--------------------------------------------------------------------------
    | Manager tidak boleh tambah kontrak.
    | Paycall tidak boleh tambah kontrak.
    */
    Route::middleware('role:account_manager,support_inputter')->group(function () {
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
| AM dan Inputter boleh edit kontrak.
| Paycall hanya boleh edit start/end date, dibatasi di ContractController.
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
| Hanya Manager dan Support Inputter yang bisa melihat ringkasan per AM.
*/
Route::middleware('role:manager')->group(function () {

    Route::get('/detailam', function () {
        $firstAm = User::where('role_id', User::ROLE_ACCOUNT_MANAGER)
            ->where('status', 'active')
            ->orderBy('name')
            ->first();

        if (!$firstAm) {
            abort(404, 'No active Account Manager found.');
        }

        return redirect()->route('account-managers.show', $firstAm->id);
    });

    Route::get('/account-manager/{user}', [AmController::class, 'show'])
        ->name('account-managers.show');

    Route::get('/account-manager/{user}/export', [AmController::class, 'export'])
        ->name('account-managers.export');

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
        
    Route::get('/transfer-report/{type}', [TransferController::class, 'exportReport'])
        ->name('transfer.report');
});

Route::middleware('role:manager,account_manager')->group(function () {
    Route::post('/transfer-contract', [TransferController::class, 'store'])
        ->name('transfer.store');
});

/*
|--------------------------------------------------------------------------
| Transfer Approval
|--------------------------------------------------------------------------
| Accept, Reject, dan Direct Transfer action khusus Manager.
*/

Route::middleware('role:manager')->group(function () {
    Route::get('/acceptreject-transfer/{transferRequest}', [TransferController::class, 'showApproval'])
        ->name('transfer.show-approval');

    Route::post('/transfer-requests/{transferRequest}/approve', [TransferController::class, 'approve'])
        ->name('transfer.approve');

    Route::post('/transfer-requests/{transferRequest}/reject', [TransferController::class, 'reject'])
        ->name('transfer.reject');

    Route::post('/direct-transfer', [TransferController::class, 'directStore'])
        ->name('transfer.direct-store');

    Route::get('/acceptreject-transfer', function () {
        return view('transfer.acceptreject-transfer');
    });

    Route::get('/accepted-transfer', [TransferController::class, 'acceptedRequests'])
        ->name('transfer.accepted');

    Route::get('/rejected-transfer', [TransferController::class, 'rejectedRequests'])
        ->name('transfer.rejected');
});

        /*
    |--------------------------------------------------------------------------
    | Email Notifications / Reminder
    |--------------------------------------------------------------------------
    | Account Manager mengatur reminder kontrak miliknya.
    | Support Inputter mengatur reminder kontrak yang dia input.
    | Manager dan Paycall tidak mengatur email notification di halaman ini.
    */
    Route::middleware('role:account_manager,support_inputter')->group(function () {

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