<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContractController;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    Route::resource('contracts', ContractController::class);

});