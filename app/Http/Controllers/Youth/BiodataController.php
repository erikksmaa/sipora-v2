<?php

namespace App\Http\Controllers\Youth;

use App\Actions\Youth\SyncUserInterestsAction;
use App\Actions\Youth\UpdateDomicileAction;
use App\Actions\Youth\UpdateYouthProfileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Youth\UpdateBiodataRequest;
use App\Models\AdministrativeArea;
use App\Services\Youth\IdentityAnchorService;
use App\Services\Youth\RegistrationReferenceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

final class BiodataController extends Controller
{
    public function edit(Request $request): View
    {
        return view('youth.biodata', [
            'user' => $request->user()->load(['profile', 'identity', 'interests', 'primaryDomicile.administrativeArea', 'primaryDomicile.village', 'contactLinks']),
            'registrationReference' => app(RegistrationReferenceService::class)->data(),
            'areas' => AdministrativeArea::where('area_level', 'district')->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateBiodataRequest $request, UpdateYouthProfileAction $profile, UpdateDomicileAction $domicile): RedirectResponse
    {
        $data = $request->validated();
        DB::transaction(function () use ($request, $profile, $domicile, $data): void {
            if (filled($data['national_id'] ?? null)) {
                app(IdentityAnchorService::class)->assign($request->user(), $data['national_id']);
            }
            $hadVisibility = $request->user()->profileVisibility()->exists();
            $profile->execute($request->user(), array_intersect_key($data, array_flip([
                'full_name', 'birth_place', 'birth_date', 'gender', 'phone',
            ])));
            // Administrative biodata must not publish a new Portfolio by default.
            if (! $hadVisibility) {
                $request->user()->profileVisibility()->update(['is_profile_public' => false]);
            }
            $domicile->execute($request->user(), array_intersect_key($data, array_flip([
                'administrative_area_id', 'village_id', 'address_line', 'rt', 'rw', 'postal_code',
            ])));
            if (array_key_exists('interests', $data)) {
                app(SyncUserInterestsAction::class)->execute($request->user(), $data['interests']);
            }
            $request->user()->contactLinks()->updateOrCreate([], array_intersect_key($data, array_flip([
                'instagram', 'facebook', 'linkedin',
            ])));
        });

        return to_route('youth.identity-verification')->with('status', 'Biodata tersimpan. Lanjutkan verifikasi identitas.');
    }
}
