<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')
    ->group(function (): void {
        Route::view('/dashboard', 'workspaces.admin')->name('dashboard');
    });
