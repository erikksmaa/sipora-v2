<?php

namespace App\Http\Controllers;

use App\Actions\Youth\CancelActivityRegistrationAction;
use App\Actions\Youth\RegisterForActivityAction;
use App\Http\Requests\Youth\RegisterActivityRequest;
use App\Models\Activity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class ActivityRegistrationController extends Controller
{
    public function store(RegisterActivityRequest $request, Activity $activity, RegisterForActivityAction $action): RedirectResponse
    {
        $action->execute($request->user(), $activity, $request->validated('registration_notes'));

        return back()->with('status', 'Pendaftaran Activity berhasil disimpan.');
    }

    public function destroy(Request $request, Activity $activity, CancelActivityRegistrationAction $action): RedirectResponse
    {
        $participation = $activity->participations()->where('user_id', $request->user()->getKey())->firstOrFail();
        $action->execute($request->user(), $participation);

        return back()->with('status', 'Pendaftaran Activity berhasil dibatalkan.');
    }
}
