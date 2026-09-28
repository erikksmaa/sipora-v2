<?php

use App\Http\Controllers\PublicCommunityController;
use App\Http\Controllers\PublicLandingController;
use App\Http\Controllers\Youth\CommunityMembershipController;
use Illuminate\Support\Facades\Route;

Route::get('/', PublicLandingController::class)->name('home');
Route::get('/communities/{organization}', [PublicCommunityController::class, 'show'])->name('communities.show');
Route::get('/communities/{organization}/logo', [PublicCommunityController::class, 'logo'])->name('communities.logo');

Route::middleware(['auth', 'verified', 'role:youth'])->group(function (): void {
    Route::post('/communities/{organization}/join', [CommunityMembershipController::class, 'store'])->name('communities.join');
    Route::delete('/communities/{organization}/membership', [CommunityMembershipController::class, 'destroy'])->name('communities.membership.destroy');
});
