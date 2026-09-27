<?php

namespace App\Http\Controllers\Youth;

use App\Actions\Youth\SyncUserSkillsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Youth\SyncSkillsRequest;
use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class YouthSkillController extends Controller
{
    public function edit(Request $request): View
    {
        return view('youth.skills', [
            'skills' => Skill::orderBy('name')->get(),
            'selectedSkills' => $request->user()->skills->pluck('uuid')->all(),
        ]);
    }

    public function update(SyncSkillsRequest $request, SyncUserSkillsAction $action): RedirectResponse
    {
        $action->execute($request->user(), $request->validated('skills', []));

        return back()->with('status', 'Keahlian berhasil diperbarui.');
    }
}
