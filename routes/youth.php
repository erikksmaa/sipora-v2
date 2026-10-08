<?php

use App\Http\Controllers\Youth\AccountController;
use App\Http\Controllers\Youth\AccountSecurityController;
use App\Http\Controllers\Youth\ActivityCertificateController;
use App\Http\Controllers\Youth\ActivityPassportController;
use App\Http\Controllers\Youth\BiodataController;
use App\Http\Controllers\Youth\CommunityApplicationController;
use App\Http\Controllers\Youth\CustomPortfolioTagController;
use App\Http\Controllers\Youth\DevelopmentPathwayController;
use App\Http\Controllers\Youth\ExternalCertificateController;
use App\Http\Controllers\Youth\IdentityVerificationController;
use App\Http\Controllers\Youth\InterestController;
use App\Http\Controllers\Youth\OnboardingController;
use App\Http\Controllers\Youth\OpportunityBookmarkController;
use App\Http\Controllers\Youth\PortfolioEvidenceController;
use App\Http\Controllers\Youth\ProfileController;
use App\Http\Controllers\Youth\VisibilityController;
use App\Http\Controllers\Youth\YouthAchievementController;
use App\Http\Controllers\Youth\YouthActivityController;
use App\Http\Controllers\Youth\YouthEducationController;
use App\Http\Controllers\Youth\YouthHomeController;
use App\Http\Controllers\Youth\YouthOrganizationExperienceController;
use App\Http\Controllers\Youth\YouthPortfolioController;
use App\Http\Controllers\Youth\YouthPortfolioSectionController;
use App\Http\Controllers\Youth\YouthSkillController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:youth'])->prefix('youth')->name('youth.')
    ->group(function (): void {
        Route::get('/home', YouthHomeController::class)->name('home');
        Route::get('/onboarding', [OnboardingController::class, 'show'])->name('onboarding');
        Route::get('/biodata', [BiodataController::class, 'edit'])->name('biodata.edit');
        Route::put('/biodata', [BiodataController::class, 'update'])->name('biodata.update');
        Route::get('/account', [AccountController::class, 'show'])->name('account.show');
        Route::get('/account/security', [AccountSecurityController::class, 'edit'])->name('account.security');
        Route::put('/account/security', [AccountSecurityController::class, 'update'])->name('account.security.update');

        Route::get('/activities', YouthActivityController::class)->middleware('youth.stage:complete')->name('activities.index');
        Route::get('/development-pathway', DevelopmentPathwayController::class)->middleware('youth.stage:portfolio')->name('development-pathway.index');
        Route::get('/opportunities/bookmarks', [OpportunityBookmarkController::class, 'index'])->middleware('youth.stage:complete')->name('opportunities.bookmarks');
        Route::post('/opportunities/{opportunity}/bookmark', [OpportunityBookmarkController::class, 'store'])->middleware('youth.stage:complete')->name('opportunities.bookmark');
        Route::delete('/opportunities/{opportunity}/bookmark', [OpportunityBookmarkController::class, 'destroy'])->name('opportunities.bookmark.destroy');
        Route::get('/portfolio', YouthPortfolioController::class)->middleware('youth.stage:portfolio')->name('portfolio.show');
        Route::get('/portfolio/sections/{section}', [YouthPortfolioSectionController::class, 'show'])->middleware('youth.stage:portfolio')->name('portfolio.sections.show');
        Route::delete('/portfolio/custom-tags/{tag}', [CustomPortfolioTagController::class, 'destroy'])->middleware('youth.stage:portfolio')->name('custom-tags.destroy');
        Route::get('/passport', [ActivityPassportController::class, 'index'])->middleware('youth.stage:complete')->name('passport.index');
        Route::get('/passport/{participation}', [ActivityPassportController::class, 'show'])->middleware('youth.stage:complete')->name('passport.show');
        Route::get('/certificates', [ActivityCertificateController::class, 'index'])->middleware('youth.stage:complete')->name('certificates.index');
        Route::get('/certificates/{certificate}', [ActivityCertificateController::class, 'show'])->middleware('youth.stage:complete')->name('certificates.show');
        Route::get('/certificates/{certificate}/download', [ActivityCertificateController::class, 'download'])->middleware('youth.stage:complete')->name('certificates.download');
        Route::get('/profile', [ProfileController::class, 'show'])->middleware('youth.stage:profile')->name('profile.show');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->middleware('youth.stage:profile')->name('profile.edit');
        Route::get('/profile/privacy', [VisibilityController::class, 'edit'])->middleware('youth.stage:portfolio')->name('profile.privacy');
        Route::get('/profile/photo', [ProfileController::class, 'photo'])->middleware('youth.stage:profile')->name('profile.photo');
        Route::put('/profile', [ProfileController::class, 'update'])->middleware('youth.stage:profile')->name('profile.update');

        Route::get('/interests', [InterestController::class, 'edit'])->middleware('youth.stage:portfolio')->name('interests.edit');
        Route::put('/interests', [InterestController::class, 'update'])->middleware('youth.stage:portfolio')->name('interests.update');

        Route::get('/skills', [YouthSkillController::class, 'edit'])->middleware('youth.stage:portfolio')->name('skills.edit');
        Route::put('/skills', [YouthSkillController::class, 'update'])->middleware('youth.stage:portfolio')->name('skills.update');

        Route::middleware('youth.stage:portfolio')->group(function (): void {
            Route::get('/portfolio/evidence/{type}/{record}', [PortfolioEvidenceController::class, 'show'])->name('portfolio.evidence.show');
            Route::post('/portfolio/evidence/{type}/{record}', [PortfolioEvidenceController::class, 'store'])->name('portfolio.evidence.store');
            Route::get('/portfolio/evidence/{type}/{record}/file', [PortfolioEvidenceController::class, 'file'])->name('portfolio.evidence.file');
            Route::delete('/portfolio/evidence/{type}/{record}', [PortfolioEvidenceController::class, 'destroy'])->name('portfolio.evidence.destroy');
            Route::post('/portfolio/external-certificates', [ExternalCertificateController::class, 'store'])->name('portfolio.external-certificates.store');
            Route::get('/portfolio/external-certificates/{certificate}/file', [ExternalCertificateController::class, 'file'])->name('portfolio.external-certificates.file');
            Route::delete('/portfolio/external-certificates/{certificate}', [ExternalCertificateController::class, 'destroy'])->name('portfolio.external-certificates.destroy');
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
        });

        Route::get('/identity-verification', [IdentityVerificationController::class, 'show'])->middleware('youth.stage:identity')->name('identity-verification');
        Route::post('/identity-verification', [IdentityVerificationController::class, 'store'])->middleware('youth.stage:identity')->name('identity-verification.store');

        Route::middleware('youth.stage:complete')->group(function (): void {
            Route::get('/communities', [CommunityApplicationController::class, 'index'])->name('communities.index');
            Route::get('/communities/create', [CommunityApplicationController::class, 'create'])->name('communities.create');
            Route::post('/communities', [CommunityApplicationController::class, 'store'])->name('communities.store');
            Route::get('/communities/{organization}', [CommunityApplicationController::class, 'show'])->name('communities.show');
            Route::get('/communities/{organization}/edit', [CommunityApplicationController::class, 'edit'])->name('communities.edit');
            Route::put('/communities/{organization}', [CommunityApplicationController::class, 'update'])->name('communities.update');
            Route::post('/communities/{organization}/submit', [CommunityApplicationController::class, 'submit'])->name('communities.submit');
            Route::get('/communities/{organization}/logo', [CommunityApplicationController::class, 'logo'])->name('communities.logo');
        });
    });
