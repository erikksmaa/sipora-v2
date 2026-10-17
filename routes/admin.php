<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\EcosystemMapController;
use App\Http\Controllers\Admin\IdentityVerificationController;
use App\Http\Controllers\Admin\ForumModerationController;
use App\Http\Controllers\Admin\OpportunityController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')
    ->group(function (): void {
        Route::get('/chat/reports', [\App\Http\Controllers\ChatController::class, 'moderation'])->name('chat.index');
        Route::post('/chat/reports/{report}', [\App\Http\Controllers\ChatController::class, 'resolve'])->middleware('throttle:chat-moderate')->name('chat.resolve');
        Route::patch('/chat/rooms/{room}', [\App\Http\Controllers\ChatController::class, 'active'])->middleware('throttle:chat-moderate')->name('chat.active');
        Route::get('/account/password', [\App\Http\Controllers\InternalPasswordController::class, 'edit'])->name('account.password.edit');
        Route::put('/account/password', [\App\Http\Controllers\InternalPasswordController::class, 'update'])->middleware('throttle:6,1')->name('account.password.update');
        Route::get('/internal-users', [\App\Http\Controllers\Admin\InternalUserController::class, 'index'])->name('internal-users.index');
        Route::get('/internal-users/create', [\App\Http\Controllers\Admin\InternalUserController::class, 'create'])->name('internal-users.create');
        Route::get('/internal-users/activity-log', [\App\Http\Controllers\Admin\InternalUserController::class, 'activityLog'])->name('internal-users.activity-log');
        Route::post('/internal-users', [\App\Http\Controllers\Admin\InternalUserController::class, 'store'])->middleware('throttle:10,1')->name('internal-users.store');
        Route::get('/internal-users/{user}', [\App\Http\Controllers\Admin\InternalUserController::class, 'show'])->name('internal-users.show');
        Route::get('/internal-users/{user}/edit', [\App\Http\Controllers\Admin\InternalUserController::class, 'edit'])->name('internal-users.edit');
        Route::patch('/internal-users/{user}', [\App\Http\Controllers\Admin\InternalUserController::class, 'update'])->name('internal-users.update');
        Route::post('/internal-users/{user}/active', [\App\Http\Controllers\Admin\InternalUserController::class, 'active'])->middleware('throttle:10,1')->name('internal-users.active');
        Route::post('/internal-users/{user}/temporary-password', [\App\Http\Controllers\Admin\InternalUserController::class, 'temporary'])->middleware('throttle:6,1')->name('internal-users.temporary');
        Route::post('/internal-users/{user}/reset-link', [\App\Http\Controllers\Admin\InternalUserController::class, 'resetLink'])->middleware('throttle:6,1')->name('internal-users.reset-link');
        Route::post('/internal-users/{user}/verification', [\App\Http\Controllers\Admin\InternalUserController::class, 'verification'])->middleware('throttle:6,1')->name('internal-users.verification');
        foreach (['youth', 'communities', 'activities', 'programs'] as $kind) {
            Route::get('/directory/'.$kind, [\App\Http\Controllers\GovernmentDirectoryController::class, 'index'])->defaults('directory_kind', $kind)->name('directory.'.$kind.'.index');
            Route::get('/directory/'.$kind.'/{record}', [\App\Http\Controllers\GovernmentDirectoryController::class, 'show'])->defaults('directory_kind', $kind)->name('directory.'.$kind.'.show');
        }
        Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
        Route::get('/feed/posts/{post}/image', [\App\Http\Controllers\SocialFeedController::class, 'image'])->name('social.image');
        Route::get('/feed/reports', [\App\Http\Controllers\Admin\SocialModerationController::class, 'index'])->name('social.index');
        Route::patch('/feed/posts/{post}', [\App\Http\Controllers\Admin\SocialModerationController::class, 'post'])->name('social.posts.update');
        Route::patch('/feed/comments/{comment}', [\App\Http\Controllers\Admin\SocialModerationController::class, 'comment'])->name('social.comments.update');
        Route::patch('/feed/reports/{report}/resolve', [\App\Http\Controllers\Admin\SocialModerationController::class, 'resolve'])->name('social.reports.resolve');
        Route::get('/forum/reports', [ForumModerationController::class, 'index'])->name('forum.index');
        Route::patch('/forum/threads/{thread}', [ForumModerationController::class, 'thread'])->name('forum.threads.update');
        Route::patch('/forum/replies/{reply}', [ForumModerationController::class, 'reply'])->name('forum.replies.update');
        Route::patch('/forum/reports/{report}/resolve', [ForumModerationController::class, 'resolve'])->name('forum.reports.resolve');
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
