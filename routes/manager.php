<?php

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
    });
