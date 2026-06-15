<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContractController;


Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth'])->group(function () {

    Route::resource('contracts', ContractController::class);

    Route::get('/dashboard', function () {

        return 'Dashboard';
    });
});