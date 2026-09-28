<?php

namespace App\Http\Controllers\Youth;

use App\Http\Controllers\Controller;
use App\Models\ActivityParticipation;
use App\Services\Activity\ActivityPassportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ActivityPassportController extends Controller
{
    public function index(Request $request, ActivityPassportService $passport): View
    {
        $entries = $passport->queryFor($request->user())->paginate(12);

        return view('youth.passport.index', [
            'entries' => $entries,
            'completedCount' => $entries->total(),
        ]);
    }

    public function show(Request $request, ActivityParticipation $participation, ActivityPassportService $passport): View
    {
        abort_unless($passport->isEligible($participation, $request->user()), 404);
        $participation->load([
            'activity.organization',
            'activity.category',
            'activity.sessions.attendances' => fn ($query) => $query->where('participation_id', $participation->getKey()),
        ]);

        return view('youth.passport.show', ['entry' => $participation]);
    }
}
