<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Program;
use App\Services\Discovery\ProgramDiscoveryService;
use App\Services\Program\ProgramIntakeService;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class PublicProgramController extends Controller
{
    public function show(Program $program, Request $request, ProgramDiscoveryService $discovery, ProgramIntakeService $intake): View
    {
        $program = $discovery->publicQuery()->whereKey($program->getKey())->firstOrFail();
        $activities = $program->activities()
            ->where('review_status', Activity::REVIEW_APPROVED)
            ->where('publication_status', Activity::PUBLICATION_PUBLISHED)
            ->with(['category', 'organization'])->orderBy('start_at')->get();

        $application = $request->user() ? $program->applications()->where('user_id', $request->user()->getKey())->first() : null;
        $registrationReason = $intake->blockingReason($request->user(), $program);
        $intakeSummary = $intake->summary($program);

        return view('public.programs.show', compact('program', 'activities', 'application', 'registrationReason', 'intakeSummary'));
    }
}
