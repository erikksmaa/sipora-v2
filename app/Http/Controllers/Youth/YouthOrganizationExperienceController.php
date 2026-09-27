<?php

namespace App\Http\Controllers\Youth;

use App\Actions\Youth\DeleteOrganizationExperienceAction;
use App\Actions\Youth\UpsertOrganizationExperienceAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Youth\UpsertOrganizationExperienceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class YouthOrganizationExperienceController extends Controller
{
    public function store(UpsertOrganizationExperienceRequest $request, UpsertOrganizationExperienceAction $action): RedirectResponse
    {
        $action->execute($request->user(), $request->validated());

        return back()->with('status', 'Pengalaman organisasi berhasil ditambahkan.');
    }

    public function update(UpsertOrganizationExperienceRequest $request, UpsertOrganizationExperienceAction $action, string $experience): RedirectResponse
    {
        $action->execute($request->user(), $request->validated(), $experience);

        return back()->with('status', 'Pengalaman organisasi berhasil diperbarui.');
    }

    public function destroy(Request $request, DeleteOrganizationExperienceAction $action, string $experience): RedirectResponse
    {
        $action->execute($request->user(), $experience);

        return back()->with('status', 'Pengalaman organisasi berhasil dihapus.');
    }
}
