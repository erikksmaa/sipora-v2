<?php

namespace App\Http\Controllers\Youth;

use App\Actions\Youth\SyncUserInterestsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Youth\SyncInterestsRequest;
use App\Models\Interest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InterestController extends Controller
{
    public function edit(Request $request): View
    {
        return view('youth.interests', ['user' => $request->user()->load('interests'), 'interests' => Interest::orderBy('name')->get()]);
    }

    public function update(SyncInterestsRequest $request, SyncUserInterestsAction $action): RedirectResponse
    {
        $action->execute($request->user(), $request->validated('interests'));

        return to_route('youth.profile.show')->with('status', 'Minat berhasil diperbarui.');
    }
}
