<?php

namespace App\Http\Controllers\Youth;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityPassportService;
use App\Services\Youth\ProfileCompletionService;
use App\Services\Youth\YouthEligibilityService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class YouthHomeController extends Controller
{
    public function __invoke(Request $request, ProfileCompletionService $completion, YouthEligibilityService $eligibility, ActivityPassportService $passport): View
    {
        $user = $request->user()->load(['profile', 'primaryDomicile.administrativeArea', 'interests', 'identity']);

        $passportQuery = $passport->queryFor($user);

        return view('youth.home', [
            'user' => $user,
            'completion' => $completion->calculate($user),
            'eligibility' => $eligibility->for($user),
            'passportCount' => (clone $passportQuery)->count(),
            'recentPassportEntries' => $passportQuery->limit(3)->get(),
        ]);
    }
}
