<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\EcosystemMapController;
use App\Http\Controllers\Admin\IdentityVerificationController;
use App\Http\Controllers\Admin\OpportunityController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')
    ->group(function (): void {
        Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
        Route::get('/ecosystem-map', EcosystemMapController::class)->name('ecosystem-map.index');
        Route::get('/identity-verifications', [IdentityVerificationController::class, 'index'])->name('identity-verifications.index');
        Route::get('/identity-verifications/{submission}', [IdentityVerificationController::class, 'show'])->name('identity-verifications.show');
        Route::get('/identity-verifications/{submission}/document', [IdentityVerificationController::class, 'document'])->name('identity-verifications.document');
        Route::post('/identity-verifications/{submission}/review', [IdentityVerificationController::class, 'review'])->name('identity-verifications.review');
        Route::get('/opportunities', [OpportunityController::class, 'index'])->name('opportunities.index');
        Route::get('/opportunities/create', [OpportunityController::class, 'create'])->name('opportunities.create');
        Route::post('/opportunities', [OpportunityController::class, 'store'])->name('opportunities.store');
        Route::get('/opportunities/{opportunity}', [OpportunityController::class, 'show'])->name('opportunities.show');
        Route::get('/opportunities/{opportunity}/edit', [OpportunityController::class, 'edit'])->name('opportunities.edit');
        Route::patch('/opportunities/{opportunity}', [OpportunityController::class, 'update'])->name('opportunities.update');
        Route::post('/opportunities/{opportunity}/publish', [OpportunityController::class, 'publish'])->name('opportunities.publish');
        Route::post('/opportunities/{opportunity}/archive', [OpportunityController::class, 'archive'])->name('opportunities.archive');
    });
