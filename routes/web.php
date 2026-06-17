<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContractController;


Route::get('/', function () {
    return view('auth.login');
});

Route::resource('contracts', ContractController::class);

Route::get('/dashboard', function () {
    return view('dashboard');
});