<?php

use App\Http\Controllers\Manager\ActivityAttendanceController;
use App\Http\Controllers\Manager\ActivityController;
use App\Http\Controllers\Manager\ActivityParticipantController;
use App\Http\Controllers\Manager\ActivitySessionController;
use App\Http\Controllers\Manager\CommunityMemberController;
use App\Http\Controllers\Manager\CommunityWorkspaceController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:youth'])->prefix('manage')->name('manager.')
    ->group(function (): void {
        Route::get('/{organization}', [CommunityWorkspaceController::class, 'show'])->name('dashboard');
        Route::get('/{organization}/profile', [CommunityWorkspaceController::class, 'edit'])->name('profile.edit');
        Route::patch('/{organization}/profile', [CommunityWorkspaceController::class, 'update'])->name('profile.update');
        Route::get('/{organization}/members', [CommunityMemberController::class, 'index'])->name('members.index');
        Route::post('/{organization}/join-requests/{membership}/review', [CommunityMemberController::class, 'review'])->name('join-requests.review');
        Route::patch('/{organization}/members/{membership}/role', [CommunityMemberController::class, 'updateRole'])->name('members.role.update');
        Route::delete('/{organization}/members/{membership}', [CommunityMemberController::class, 'destroy'])->name('members.destroy');
        Route::get('/{organization}/activities', [ActivityController::class, 'index'])->name('activities.index');
        Route::get('/{organization}/activities/create', [ActivityController::class, 'create'])->name('activities.create');
        Route::post('/{organization}/activities', [ActivityController::class, 'store'])->name('activities.store');
        Route::get('/{organization}/activities/{activity}', [ActivityController::class, 'show'])->name('activities.show');
        Route::get('/{organization}/activities/{activity}/edit', [ActivityController::class, 'edit'])->name('activities.edit');
        Route::patch('/{organization}/activities/{activity}', [ActivityController::class, 'update'])->name('activities.update');
        Route::post('/{organization}/activities/{activity}/submit', [ActivityController::class, 'submit'])->name('activities.submit');
        Route::post('/{organization}/activities/{activity}/publish', [ActivityController::class, 'publish'])->name('activities.publish');
        Route::post('/{organization}/activities/{activity}/archive', [ActivityController::class, 'archive'])->name('activities.archive');
        Route::post('/{organization}/activities/{activity}/complete-execution', [ActivityController::class, 'completeExecution'])->name('activities.complete-execution');
        Route::get('/{organization}/activities/{activity}/poster', [ActivityController::class, 'poster'])->name('activities.poster');
        Route::get('/{organization}/activities/{activity}/sessions', [ActivitySessionController::class, 'index'])->name('activities.sessions.index');
        Route::get('/{organization}/activities/{activity}/sessions/create', [ActivitySessionController::class, 'create'])->name('activities.sessions.create');
        Route::post('/{organization}/activities/{activity}/sessions', [ActivitySessionController::class, 'store'])->name('activities.sessions.store');
        Route::get('/{organization}/activities/{activity}/sessions/{session}/edit', [ActivitySessionController::class, 'edit'])->name('activities.sessions.edit');
        Route::patch('/{organization}/activities/{activity}/sessions/{session}', [ActivitySessionController::class, 'update'])->name('activities.sessions.update');
        Route::patch('/{organization}/activities/{activity}/sessions/{session}/move', [ActivitySessionController::class, 'move'])->name('activities.sessions.move');
        Route::delete('/{organization}/activities/{activity}/sessions/{session}', [ActivitySessionController::class, 'destroy'])->name('activities.sessions.destroy');
        Route::get('/{organization}/activities/{activity}/participants', [ActivityParticipantController::class, 'index'])->name('activities.participants.index');
        Route::post('/{organization}/activities/{activity}/participants/{participation}/review', [ActivityParticipantController::class, 'review'])->name('activities.participants.review');
        Route::post('/{organization}/activities/{activity}/participants/{participation}/completion', [ActivityParticipantController::class, 'complete'])->name('activities.participants.complete');
        Route::get('/{organization}/activities/{activity}/sessions/{session}/attendance', [ActivityAttendanceController::class, 'index'])->name('activities.attendance.index');
        Route::put('/{organization}/activities/{activity}/sessions/{session}/attendance/{participation}', [ActivityAttendanceController::class, 'update'])->name('activities.attendance.update');
    });
