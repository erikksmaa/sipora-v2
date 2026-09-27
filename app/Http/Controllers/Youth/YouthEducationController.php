<?php

namespace App\Http\Controllers\Youth;

use App\Actions\Youth\DeleteEducationAction;
use App\Actions\Youth\UpsertEducationAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Youth\UpsertEducationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class YouthEducationController extends Controller
{
    public function store(UpsertEducationRequest $request, UpsertEducationAction $action): RedirectResponse
    {
        $action->execute($request->user(), $request->validated());

        return back()->with('status', 'Riwayat pendidikan berhasil ditambahkan.');
    }

    public function update(UpsertEducationRequest $request, UpsertEducationAction $action, string $education): RedirectResponse
    {
        $action->execute($request->user(), $request->validated(), $education);

        return back()->with('status', 'Riwayat pendidikan berhasil diperbarui.');
    }

    public function destroy(Request $request, DeleteEducationAction $action, string $education): RedirectResponse
    {
        $action->execute($request->user(), $education);

        return back()->with('status', 'Riwayat pendidikan berhasil dihapus.');
    }
}
