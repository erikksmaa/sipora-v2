<?php

use App\Http\Controllers\Verifier\CommunityVerificationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:verifier'])->prefix('verifier')->name('verifier.')
    ->group(function (): void {
        Route::view('/dashboard', 'workspaces.verifier')->name('dashboard');
        Route::get('/community-verifications', [CommunityVerificationController::class, 'index'])->name('community-verifications.index');
        Route::get('/community-verifications/{organization}', [CommunityVerificationController::class, 'show'])->name('community-verifications.show');
        Route::post('/community-verifications/{organization}/review', [CommunityVerificationController::class, 'review'])->name('community-verifications.review');
        Route::get('/community-verifications/{organization}/logo', [CommunityVerificationController::class, 'logo'])->name('community-verifications.logo');
    });
