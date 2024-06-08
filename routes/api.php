<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\User\GetUserController;
use App\Http\Controllers\Voucher\ClaimVoucherController;
use App\Http\Controllers\Voucher\VouchersController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'Welcome to vouchers API']);
});

Route::prefix('auth')->group(function () {
    Route::post('/login', LoginController::class);
    Route::post('/register', RegisterController::class);
    Route::post('/logout', LogoutController::class)->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', GetUserController::class);
    Route::apiResource('vouchers', VouchersController::class);
    Route::post('/vouchers/claim', ClaimVoucherController::class);
});
