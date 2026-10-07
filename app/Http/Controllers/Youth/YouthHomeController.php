<?php

namespace App\Http\Controllers\Youth;

use App\Http\Controllers\Controller;
use App\Models\ActivityParticipation;
use App\Models\OrganizationMembership;
use App\Services\Activity\ActivityPassportService;
use App\Services\Youth\ProfileCompletionService;
use App\Services\Youth\YouthEligibilityService;
use App\Services\Youth\YouthOnboardingService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class YouthHomeController extends Controller
{
    public function __invoke(Request $request, ProfileCompletionService $completion, YouthEligibilityService $eligibility, ActivityPassportService $passport, YouthOnboardingService $onboardingService): View
    {
        $user = $request->user()->load(['profile', 'primaryDomicile.administrativeArea', 'interests', 'identity']);
        $onboarding = $onboardingService->state($user);

        if (! $onboarding['onboardingComplete']) {
            return view('youth.home', compact('user', 'onboarding'));
        }

        $passportQuery = $passport->queryFor($user);

        return view('youth.home', [
            'user' => $user,
            'onboarding' => $onboarding,
            'completion' => $completion->calculate($user),
            'eligibility' => $eligibility->for($user),
            'passportCount' => (clone $passportQuery)->count(),
            'recentPassportEntries' => (clone $passportQuery)->limit(3)->get(),
            'activeRegistrationCount' => $user->activityParticipations()->whereIn('registration_status', [ActivityParticipation::REGISTRATION_PENDING, ActivityParticipation::REGISTRATION_ACCEPTED])->where('completion_status', ActivityParticipation::COMPLETION_PENDING)->count(),
            'recentParticipations' => $user->activityParticipations()->with(['activity.organization'])->latest('requested_at')->limit(4)->get(),
            'certificateCount' => $user->certificates()->count(),
            'communityCount' => $user->organizationMemberships()->where('membership_status', OrganizationMembership::STATUS_ACTIVE)->count(),
            'bookmarkCount' => $user->opportunityBookmarks()->count(),
            'recentNotifications' => $user->siporaNotifications()->latest()->limit(4)->get(),
        ]);
    }
}
