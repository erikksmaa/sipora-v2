<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\SessionController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::view('/register', 'auth.register')->name('register');
    Route::view('/login', 'auth.login')->name('login');
    Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');
    Route::get('/reset-password/{token}', fn (string $token) => view('auth.reset-password', ['token' => $token]))->name('password.reset');

    Route::middleware('throttle:public-auth')->group(function (): void {
        Route::post('/register', [RegistrationController::class, 'store'])->name('register.store');
        Route::post('/login', [SessionController::class, 'store'])->name('login.store');
        Route::post('/forgot-password', [PasswordResetController::class, 'send'])->name('password.email');
        Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.update');
    });
    Route::middleware('throttle:oauth')->group(function (): void {
        Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
        Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');
    });
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [SessionController::class, 'destroy'])->name('logout');
    Route::view('/verify-email', 'auth.verify-email')->name('verification.notice');
    Route::get('/verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'send'])
        ->middleware('throttle:6,1')->name('verification.send');
});
