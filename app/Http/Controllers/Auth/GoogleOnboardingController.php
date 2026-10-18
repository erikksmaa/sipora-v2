<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CompleteGoogleRegistrationRequest;
use App\Services\Youth\CompleteYouthRegistration;
use App\Services\Youth\GoogleOnboardingService;
use App\Services\Youth\RegistrationReferenceService;
use App\Support\BinaryUuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class GoogleOnboardingController extends Controller
{
    public function show(Request $request, GoogleOnboardingService $onboarding, RegistrationReferenceService $reference)
    {
        if (! $onboarding->pending($request->user())) {
            return to_route('youth.home');
        }
        $user = $request->user();
        $profile = $user->profile()->first();
        $address = $user->primaryDomicile()->first();
        $initial = [
            'name' => $profile?->full_name ?: $user->name,
            'email' => $user->email, 'birth_place' => $profile?->birth_place, 'birth_date' => $profile?->birth_date?->toDateString(),
            'gender' => in_array($profile?->gender, ['male', 'female']) ? $profile->gender : '',
            'administrative_area_id' => $address ? BinaryUuid::text($address->administrative_area_id) : '',
            'village_id' => $address?->village_id ? BinaryUuid::text($address->village_id) : '',
            'interests' => $user->interests()->get()->map(fn ($item) => $item->uuid())->all(),
        ];

        return view('auth.register', ['registrationReference' => $reference->data(), 'googleOnboarding' => true,
            'registrationInitial' => $initial, 'savedAddress' => $address]);
    }

    public function store(CompleteGoogleRegistrationRequest $request, GoogleOnboardingService $onboarding, CompleteYouthRegistration $complete)
    {
        DB::transaction(function () use ($request, $onboarding, $complete): void {
            $user = $request->user()->newQuery()->whereKey($request->user()->getKey())->lockForUpdate()->firstOrFail();
            // Serialize retries and leave already-completed registrations untouched.
            if ($onboarding->pending($user)) {
                $complete->execute($user, $request->validated());
            }
        });

        return to_route('youth.identity-verification')->with('status', 'Data pendaftaran tersimpan. Ajukan dokumen untuk verifikasi identitas SIPORA.');
    }
}
