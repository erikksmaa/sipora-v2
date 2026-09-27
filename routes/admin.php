<?php

use App\Http\Controllers\Admin\IdentityVerificationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')
    ->group(function (): void {
        Route::view('/dashboard', 'workspaces.admin')->name('dashboard');
        Route::get('/identity-verifications', [IdentityVerificationController::class, 'index'])->name('identity-verifications.index');
        Route::get('/identity-verifications/{submission}', [IdentityVerificationController::class, 'show'])->name('identity-verifications.show');
        Route::get('/identity-verifications/{submission}/document', [IdentityVerificationController::class, 'document'])->name('identity-verifications.document');
        Route::post('/identity-verifications/{submission}/review', [IdentityVerificationController::class, 'review'])->name('identity-verifications.review');
    });
