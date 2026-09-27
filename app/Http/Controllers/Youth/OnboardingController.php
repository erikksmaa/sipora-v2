<?php

namespace App\Http\Controllers\Youth;

use App\Actions\Youth\SyncUserInterestsAction;
use App\Actions\Youth\UpdateDomicileAction;
use App\Actions\Youth\UpdateYouthProfileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Youth\SyncInterestsRequest;
use App\Http\Requests\Youth\UpdateDomicileRequest;
use App\Http\Requests\Youth\UpdateProfileRequest;
use App\Models\AdministrativeArea;
use App\Models\Interest;
use App\Services\Youth\ProfileCompletionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OnboardingController extends Controller
{
    public function show(Request $request, ProfileCompletionService $completion): View
    {
        $user = $request->user()->load(['profile', 'primaryDomicile', 'interests']);

        return view('youth.onboarding', [
            'user' => $user,
            'step' => $request->string('step')->toString() ?: $completion->nextStep($user),
            'completion' => $completion->calculate($user),
            'areas' => AdministrativeArea::where('area_level', 'district')->orderBy('name')->get(),
            'interests' => Interest::orderBy('name')->get(),
        ]);
    }

    public function profile(UpdateProfileRequest $request, UpdateYouthProfileAction $action): RedirectResponse
    {
        $action->execute($request->user(), $request->safe()->except('profile_photo'), $request->file('profile_photo'));

        return to_route('youth.onboarding', ['step' => 'domicile'])->with('status', 'Data dasar tersimpan. Lanjutkan domisili.');
    }

    public function domicile(UpdateDomicileRequest $request, UpdateDomicileAction $action): RedirectResponse
    {
        $action->execute($request->user(), $request->validated());

        return to_route('youth.onboarding', ['step' => 'interests'])->with('status', 'Domisili tersimpan. Pilih minatmu.');
    }

    public function interests(SyncInterestsRequest $request, SyncUserInterestsAction $action): RedirectResponse
    {
        $action->execute($request->user(), $request->validated('interests'));

        return to_route('youth.onboarding', ['step' => 'complete'])->with('status', 'Minat tersimpan. Profil dasarmu siap.');
    }
}
