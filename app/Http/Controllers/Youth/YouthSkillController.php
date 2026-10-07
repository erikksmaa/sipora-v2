<?php

namespace App\Http\Controllers\Youth;

use App\Actions\Youth\AddCustomPortfolioTagAction;
use App\Actions\Youth\SyncUserSkillsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Youth\SyncSkillsRequest;
use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

final class YouthSkillController extends Controller
{
    public function edit(Request $request): View
    {
        return view('youth.skills', [
            'skills' => Skill::orderBy('name')->get(),
            'selectedSkills' => $request->user()->skills->map->uuid()->all(),
            'customSkills' => $request->user()->customPortfolioTags()->where('kind', 'skill')->orderBy('name')->get(),
        ]);
    }

    public function update(SyncSkillsRequest $request, SyncUserSkillsAction $action, AddCustomPortfolioTagAction $customTags): RedirectResponse
    {
        DB::transaction(function () use ($request, $action, $customTags): void {
            $customTags->execute($request->user(), 'skill', $request->validated('custom_skill'));
            $action->execute($request->user(), $request->validated('skills', []));
        });

        return to_route('youth.skills.edit')->with('status', 'Keahlian berhasil diperbarui.');
    }
}
