<?php

use App\Http\Controllers\Verifier\ActivityVerificationController;
use App\Http\Controllers\Verifier\CommunityVerificationController;
use App\Http\Controllers\Verifier\FinancialReportVerificationController;
use App\Http\Controllers\Verifier\ProgramEvaluationController;
use App\Http\Controllers\Verifier\ProgramMonitoringController;
use App\Http\Controllers\Verifier\ProgramProposalVerificationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:verifier'])->prefix('verifier')->name('verifier.')
    ->group(function (): void {
        Route::view('/dashboard', 'workspaces.verifier')->name('dashboard');
        Route::get('/community-verifications', [CommunityVerificationController::class, 'index'])->name('community-verifications.index');
        Route::get('/community-verifications/{organization}', [CommunityVerificationController::class, 'show'])->name('community-verifications.show');
        Route::post('/community-verifications/{organization}/review', [CommunityVerificationController::class, 'review'])->name('community-verifications.review');
        Route::get('/community-verifications/{organization}/logo', [CommunityVerificationController::class, 'logo'])->name('community-verifications.logo');
        Route::get('/activity-verifications', [ActivityVerificationController::class, 'index'])->name('activity-verifications.index');
        Route::get('/activity-verifications/{activity}', [ActivityVerificationController::class, 'show'])->name('activity-verifications.show');
        Route::post('/activity-verifications/{activity}/review', [ActivityVerificationController::class, 'review'])->name('activity-verifications.review');
        Route::get('/activity-verifications/{activity}/poster', [ActivityVerificationController::class, 'poster'])->name('activity-verifications.poster');
        Route::get('/program-proposals', [ProgramProposalVerificationController::class, 'index'])->name('program-proposals.index');
        Route::get('/program-proposals/{proposal}', [ProgramProposalVerificationController::class, 'show'])->name('program-proposals.show');
        Route::post('/program-proposals/{proposal}/review', [ProgramProposalVerificationController::class, 'review'])->name('program-proposals.review');
        Route::get('/program-proposals/{proposal}/document', [ProgramProposalVerificationController::class, 'document'])->name('program-proposals.document');
        Route::get('/program-monitoring', [ProgramMonitoringController::class, 'index'])->name('program-monitoring.index');
        Route::get('/program-monitoring/logbooks/{logbook}', [ProgramMonitoringController::class, 'show'])->name('program-monitoring.show');
        Route::post('/program-monitoring/logbooks/{logbook}/review', [ProgramMonitoringController::class, 'review'])->name('program-monitoring.review');
        Route::get('/program-monitoring/logbooks/{logbook}/media/{media}', [ProgramMonitoringController::class, 'media'])->name('program-monitoring.media');
        Route::get('/financial-reports', [FinancialReportVerificationController::class, 'index'])->name('financial-reports.index');
        Route::get('/financial-reports/{report}', [FinancialReportVerificationController::class, 'show'])->name('financial-reports.show');
        Route::post('/financial-reports/{report}/review', [FinancialReportVerificationController::class, 'review'])->name('financial-reports.review');
        Route::get('/financial-reports/{report}/items/{item}/receipt', [FinancialReportVerificationController::class, 'receipt'])->name('financial-reports.items.receipt');
        Route::get('/program-evaluations', [ProgramEvaluationController::class, 'index'])->name('program-evaluations.index');
        Route::get('/program-evaluations/{program}', [ProgramEvaluationController::class, 'show'])->name('program-evaluations.show');
        Route::post('/program-evaluations/{program}', [ProgramEvaluationController::class, 'store'])->name('program-evaluations.store');
    });
