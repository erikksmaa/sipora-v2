<?php

use App\Http\Controllers\ActivityDiscoveryController;
use App\Http\Controllers\ActivityRegistrationController;
use App\Http\Controllers\CommunityDiscoveryController;
use App\Http\Controllers\GlobalSearchController;
use App\Http\Controllers\OpportunityDiscoveryController;
use App\Http\Controllers\ProgramDiscoveryController;
use App\Http\Controllers\PublicActivityController;
use App\Http\Controllers\PublicCertificateController;
use App\Http\Controllers\PublicCommunityController;
use App\Http\Controllers\PublicLandingController;
use App\Http\Controllers\PublicOpportunityController;
use App\Http\Controllers\PublicProgramController;
use App\Http\Controllers\PublicYouthPortfolioController;
use App\Http\Controllers\Youth\CommunityMembershipController;
use Illuminate\Support\Facades\Route;

Route::get('/', PublicLandingController::class)->name('home');
Route::get('/activities', ActivityDiscoveryController::class)->name('activities.index');
Route::get('/communities', CommunityDiscoveryController::class)->name('communities.index');
Route::get('/search', GlobalSearchController::class)->name('search.index');
Route::get('/opportunities', OpportunityDiscoveryController::class)->name('opportunities.index');
Route::get('/opportunities/{opportunity}', [PublicOpportunityController::class, 'show'])->name('opportunities.show');
Route::get('/programs', ProgramDiscoveryController::class)->name('programs.index');
Route::get('/programs/{program}', [PublicProgramController::class, 'show'])->name('programs.show');
Route::get('/communities/{organization}', [PublicCommunityController::class, 'show'])->name('communities.show');
Route::get('/communities/{organization}/logo', [PublicCommunityController::class, 'logo'])->name('communities.logo');
Route::get('/activities/{activity}', [PublicActivityController::class, 'show'])->name('activities.show');
Route::get('/activities/{activity}/poster', [PublicActivityController::class, 'poster'])->name('activities.poster');
Route::get('/certificates/verify/{code}', PublicCertificateController::class)->name('certificates.verify');
Route::get('/portfolio/{profile}/photo', [PublicYouthPortfolioController::class, 'photo'])->name('portfolio.photo');
Route::get('/portfolio/{profile}', [PublicYouthPortfolioController::class, 'show'])->name('portfolio.show');

Route::middleware(['auth', 'verified', 'role:youth'])->group(function (): void {
    Route::post('/activities/{activity}/register', [ActivityRegistrationController::class, 'store'])->name('activities.register');
    Route::delete('/activities/{activity}/registration', [ActivityRegistrationController::class, 'destroy'])->name('activities.registration.destroy');
    Route::post('/communities/{organization}/join', [CommunityMembershipController::class, 'store'])->name('communities.join');
    Route::delete('/communities/{organization}/membership', [CommunityMembershipController::class, 'destroy'])->name('communities.membership.destroy');
});
