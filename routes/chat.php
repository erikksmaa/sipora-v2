<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:youth|admin|verifier'])->prefix('chat')->name('chat.')->group(function () {
    Route::get('/', [ChatController::class, 'global'])->name('global');
    Route::get('/rooms', [ChatController::class, 'rooms'])->middleware('throttle:chat-poll')->name('rooms');
    Route::get('/communities/{organization}', [ChatController::class, 'community'])->name('community');
    Route::get('/rooms/{room}', [ChatController::class, 'room'])->name('room');
    Route::get('/rooms/{room}/messages', [ChatController::class, 'messages'])->middleware('throttle:chat-poll')->name('messages');
    Route::post('/rooms/{room}/messages', [ChatController::class, 'send'])->middleware('throttle:chat-send')->name('send');
    Route::delete('/rooms/{room}/messages/{message}', [ChatController::class, 'remove'])->withTrashed()->middleware('throttle:chat-moderate')->name('remove');
    Route::post('/rooms/{room}/messages/{message}/reports', [ChatController::class, 'report'])->middleware('throttle:chat-report')->name('report');
});
