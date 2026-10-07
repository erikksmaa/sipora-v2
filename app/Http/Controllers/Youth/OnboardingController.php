<?php

namespace App\Http\Controllers\Youth;

use App\Http\Controllers\Controller;
use App\Services\Youth\YouthOnboardingService;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class OnboardingController extends Controller
{
    public function show(Request $request, YouthOnboardingService $onboarding): View
    {
        return view('youth.onboarding', [
            'user' => $request->user(),
            'onboarding' => $onboarding->state($request->user()),
        ]);
    }
}
