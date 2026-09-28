<?php

use App\Http\Controllers\Youth\CommunityApplicationController;
use App\Http\Controllers\Youth\IdentityVerificationController;
use App\Http\Controllers\Youth\InterestController;
use App\Http\Controllers\Youth\OnboardingController;
use App\Http\Controllers\Youth\ProfileController;
use App\Http\Controllers\Youth\VisibilityController;
use App\Http\Controllers\Youth\YouthAchievementController;
use App\Http\Controllers\Youth\YouthEducationController;
use App\Http\Controllers\Youth\YouthHomeController;
use App\Http\Controllers\Youth\YouthOrganizationExperienceController;
use App\Http\Controllers\Youth\YouthSkillController;
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

        Route::get('/skills', [YouthSkillController::class, 'edit'])->name('skills.edit');
        Route::put('/skills', [YouthSkillController::class, 'update'])->name('skills.update');

        Route::post('/educations', [YouthEducationController::class, 'store'])->name('educations.store');
        Route::put('/educations/{education}', [YouthEducationController::class, 'update'])->name('educations.update');
        Route::delete('/educations/{education}', [YouthEducationController::class, 'destroy'])->name('educations.destroy');

        Route::post('/organization-experiences', [YouthOrganizationExperienceController::class, 'store'])->name('organization-experiences.store');
        Route::put('/organization-experiences/{experience}', [YouthOrganizationExperienceController::class, 'update'])->name('organization-experiences.update');
        Route::delete('/organization-experiences/{experience}', [YouthOrganizationExperienceController::class, 'destroy'])->name('organization-experiences.destroy');

        Route::post('/achievements', [YouthAchievementController::class, 'store'])->name('achievements.store');
        Route::put('/achievements/{achievement}', [YouthAchievementController::class, 'update'])->name('achievements.update');
        Route::delete('/achievements/{achievement}', [YouthAchievementController::class, 'destroy'])->name('achievements.destroy');

        Route::put('/profile/visibility', [VisibilityController::class, 'update'])->name('profile.visibility.update');

        Route::get('/identity-verification', [IdentityVerificationController::class, 'show'])->name('identity-verification');
        Route::post('/identity-verification', [IdentityVerificationController::class, 'store'])->name('identity-verification.store');

        Route::get('/communities', [CommunityApplicationController::class, 'index'])->name('communities.index');
        Route::get('/communities/create', [CommunityApplicationController::class, 'create'])->name('communities.create');
        Route::post('/communities', [CommunityApplicationController::class, 'store'])->name('communities.store');
        Route::get('/communities/{organization}', [CommunityApplicationController::class, 'show'])->name('communities.show');
        Route::get('/communities/{organization}/edit', [CommunityApplicationController::class, 'edit'])->name('communities.edit');
        Route::put('/communities/{organization}', [CommunityApplicationController::class, 'update'])->name('communities.update');
        Route::post('/communities/{organization}/submit', [CommunityApplicationController::class, 'submit'])->name('communities.submit');
        Route::get('/communities/{organization}/logo', [CommunityApplicationController::class, 'logo'])->name('communities.logo');
    });
