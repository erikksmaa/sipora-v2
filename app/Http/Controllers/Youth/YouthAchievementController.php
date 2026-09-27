<?php

namespace App\Http\Controllers\Youth;

use App\Actions\Youth\DeleteAchievementAction;
use App\Actions\Youth\UpsertAchievementAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Youth\UpsertAchievementRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class YouthAchievementController extends Controller
{
    public function store(UpsertAchievementRequest $request, UpsertAchievementAction $action): RedirectResponse
    {
        $action->execute($request->user(), $request->validated());

        return back()->with('status', 'Penghargaan/prestasi berhasil ditambahkan.');
    }

    public function update(UpsertAchievementRequest $request, UpsertAchievementAction $action, string $achievement): RedirectResponse
    {
        $action->execute($request->user(), $request->validated(), $achievement);

        return back()->with('status', 'Penghargaan/prestasi berhasil diperbarui.');
    }

    public function destroy(Request $request, DeleteAchievementAction $action, string $achievement): RedirectResponse
    {
        $action->execute($request->user(), $achievement);

        return back()->with('status', 'Penghargaan/prestasi berhasil dihapus.');
    }
}
