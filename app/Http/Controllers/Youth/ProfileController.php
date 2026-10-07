<?php

namespace App\Http\Controllers\Youth;

use App\Actions\Youth\UpdateYouthProfileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Youth\UpdateProfileRequest;
use App\Services\Youth\ProfileCompletionService;
use App\Services\Youth\YouthEligibilityService;
use App\Services\Youth\YouthOnboardingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProfileController extends Controller
{
    public function show(Request $request, ProfileCompletionService $completion, YouthEligibilityService $eligibility, YouthOnboardingService $onboardingService): View
    {
        $user = $request->user()->load([
            'profile', 'primaryDomicile.administrativeArea', 'interests', 'identity', 'profileVisibility',
        ]);

        return view('youth.profile.show', compact('user') + [
            'completion' => $completion->calculate($user),
            'eligibility' => $eligibility->for($user),
            'onboarding' => $onboardingService->state($user),
        ]);
    }

    public function edit(Request $request): View
    {
        return view('youth.profile.edit', ['user' => $request->user()->load('profile')]);
    }

    public function photo(Request $request): StreamedResponse
    {
        $path = $request->user()->profile?->profile_photo_path;
        abort_unless($path, 404);

        return response()->streamDownload(
            fn () => print Storage::disk('public')->get($path),
            basename($path),
            ['Content-Type' => Storage::disk('public')->mimeType($path), 'Content-Disposition' => 'inline']
        );
    }

    public function update(UpdateProfileRequest $request, UpdateYouthProfileAction $action): RedirectResponse
    {
        $data = $request->safe()->except('profile_photo');
        $action->execute($request->user(), $data, $request->file('profile_photo'));

        return to_route('youth.profile.show')->with('status', 'Profil berhasil diperbarui.');
    }
}
