<?php

use App\Http\Controllers\Youth\IdentityVerificationController;
use App\Http\Controllers\Youth\InterestController;
use App\Http\Controllers\Youth\OnboardingController;
use App\Http\Controllers\Youth\ProfileController;
use App\Http\Controllers\Youth\VisibilityController;
use App\Http\Controllers\Youth\YouthHomeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:youth'])->prefix('youth')->name('youth.')
    ->group(function (): void {
        Route::get('/home', YouthHomeController::class)->name('home');
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::get('/profile/photo', [ProfileController::class, 'photo'])->name('profile.photo');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/onboarding', [OnboardingController::class, 'show'])->name('onboarding');
        Route::put('/onboarding/profile', [OnboardingController::class, 'profile'])->name('onboarding.profile');
        Route::put('/onboarding/domicile', [OnboardingController::class, 'domicile'])->name('onboarding.domicile');
        Route::put('/onboarding/interests', [OnboardingController::class, 'interests'])->name('onboarding.interests');
        Route::get('/interests', [InterestController::class, 'edit'])->name('interests.edit');
        Route::put('/interests', [InterestController::class, 'update'])->name('interests.update');
        Route::put('/profile/visibility', [VisibilityController::class, 'update'])->name('profile.visibility.update');
        Route::get('/identity-verification', IdentityVerificationController::class)->name('identity-verification');
    });
