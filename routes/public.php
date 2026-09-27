<?php

use App\Http\Controllers\PublicLandingController;
use Illuminate\Support\Facades\Route;

Route::get('/', PublicLandingController::class)->name('home');
