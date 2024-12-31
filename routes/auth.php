<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->middleware('guest')->group(function () {

    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('auth.register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('auth.login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('password/forgot', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('password/forgot', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('password/reset/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('password/reset', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::prefix('api')->middleware('auth')->group(function () {

    // Swagger Documentation Route
    Route::get('/documentation', function () {
        return view('swagger.index');
    })->name('api.documentation');

    Route::get('email/verify', EmailVerificationPromptController::class)
        ->name('verification.notice');
    Route::get('email/verify/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('password/confirm', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');
    Route::post('password/confirm', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])
        ->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('auth.logout');
});
