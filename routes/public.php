<?php

use App\Http\Controllers\ActivityRegistrationController;
use App\Http\Controllers\PublicActivityController;
use App\Http\Controllers\PublicCommunityController;
use App\Http\Controllers\PublicLandingController;
use App\Http\Controllers\Youth\CommunityMembershipController;
use Illuminate\Support\Facades\Route;

Route::get('/', PublicLandingController::class)->name('home');
Route::get('/communities/{organization}', [PublicCommunityController::class, 'show'])->name('communities.show');
Route::get('/communities/{organization}/logo', [PublicCommunityController::class, 'logo'])->name('communities.logo');
Route::get('/activities/{activity}', [PublicActivityController::class, 'show'])->name('activities.show');
Route::get('/activities/{activity}/poster', [PublicActivityController::class, 'poster'])->name('activities.poster');

Route::middleware(['auth', 'verified', 'role:youth'])->group(function (): void {
    Route::post('/activities/{activity}/register', [ActivityRegistrationController::class, 'store'])->name('activities.register');
    Route::delete('/activities/{activity}/registration', [ActivityRegistrationController::class, 'destroy'])->name('activities.registration.destroy');
    Route::post('/communities/{organization}/join', [CommunityMembershipController::class, 'store'])->name('communities.join');
    Route::delete('/communities/{organization}/membership', [CommunityMembershipController::class, 'destroy'])->name('communities.membership.destroy');
});
