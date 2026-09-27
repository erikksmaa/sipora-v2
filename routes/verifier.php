<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:verifier'])->prefix('verifier')->name('verifier.')
    ->group(function (): void {
        Route::view('/dashboard', 'workspaces.verifier')->name('dashboard');
    });
