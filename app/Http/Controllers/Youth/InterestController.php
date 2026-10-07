<?php

namespace App\Http\Controllers\Youth;

use App\Actions\Youth\AddCustomPortfolioTagAction;
use App\Actions\Youth\SyncUserInterestsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Youth\SyncInterestsRequest;
use App\Models\Interest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InterestController extends Controller
{
    public function edit(Request $request): View
    {
        return view('youth.interests', ['user' => $request->user()->load('interests'), 'interests' => Interest::orderBy('name')->get(), 'customInterests' => $request->user()->customPortfolioTags()->where('kind', 'interest')->orderBy('name')->get()]);
    }

    public function update(SyncInterestsRequest $request, SyncUserInterestsAction $action, AddCustomPortfolioTagAction $customTags): RedirectResponse
    {
        DB::transaction(function () use ($request, $action, $customTags): void {
            $customTags->execute($request->user(), 'interest', $request->validated('custom_interest'));
            $action->execute($request->user(), $request->validated('interests', []));
        });

        return to_route('youth.interests.edit')->with('status', 'Minat berhasil diperbarui.');
    }
}
