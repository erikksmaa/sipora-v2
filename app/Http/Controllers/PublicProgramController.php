<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Program;
use App\Services\Discovery\ProgramDiscoveryService;
use Illuminate\View\View;

final class PublicProgramController extends Controller
{
    public function show(Program $program, ProgramDiscoveryService $discovery): View
    {
        $program = $discovery->publicQuery()->whereKey($program->getKey())->firstOrFail();
        $activities = $program->activities()
            ->where('review_status', Activity::REVIEW_APPROVED)
            ->where('publication_status', Activity::PUBLICATION_PUBLISHED)
            ->with(['category', 'organization'])->orderBy('start_at')->get();

        return view('public.programs.show', compact('program', 'activities'));
    }
}
