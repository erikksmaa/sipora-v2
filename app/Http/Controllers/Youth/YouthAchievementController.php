<?php

namespace App\Http\Controllers\Youth;

use App\Actions\Youth\DeleteAchievementAction;
use App\Actions\Youth\SavePortfolioEvidenceAction;
use App\Actions\Youth\UpsertAchievementAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Youth\UpsertAchievementRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class YouthAchievementController extends Controller
{
    public function store(UpsertAchievementRequest $request, UpsertAchievementAction $action, SavePortfolioEvidenceAction $saveEvidence): RedirectResponse
    {
        DB::transaction(function () use ($request, $action, $saveEvidence): void {
            $achievement = $action->execute($request->user(), $request->validated());
            if ($request->hasFile('evidence')) {
                $saveEvidence->execute($request->user(), 'achievement', $achievement, $request->file('evidence'));
            }
        });

        return back()->with('status', 'Penghargaan/prestasi berhasil ditambahkan.');
    }

    public function update(UpsertAchievementRequest $request, UpsertAchievementAction $action, SavePortfolioEvidenceAction $saveEvidence, string $achievement): RedirectResponse
    {
        DB::transaction(function () use ($request, $action, $saveEvidence, $achievement): void {
            $record = $action->execute($request->user(), $request->validated(), $achievement);
            if ($request->hasFile('evidence')) {
                $saveEvidence->execute($request->user(), 'achievement', $record, $request->file('evidence'));
            }
        });

        return back()->with('status', 'Penghargaan/prestasi berhasil diperbarui.');
    }

    public function destroy(Request $request, DeleteAchievementAction $action, string $achievement): RedirectResponse
    {
        $action->execute($request->user(), $achievement);

        return back()->with('status', 'Penghargaan/prestasi berhasil dihapus.');
    }
}
