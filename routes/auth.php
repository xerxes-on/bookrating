<?php

use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout')
        ->middleware('auth:sanctum');
    Route::post('refresh', 'refresh')
        ->middleware('auth:sanctum');
});
Route::get('login', function () {
    return redirect()->intended(
        config('app.frontend_url').'/verify'
    );})->name('login');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');
