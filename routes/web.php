<?php

use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

foreach (['public', 'auth', 'youth', 'manager', 'verifier', 'admin'] as $workspace) {
    require __DIR__.'/'.$workspace.'.php';
}

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'read'])->name('notifications.read');
});
