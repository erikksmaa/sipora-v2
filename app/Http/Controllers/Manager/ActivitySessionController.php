<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\StoreActivitySessionRequest;
use App\Http\Requests\Manager\UpdateActivitySessionRequest;
use App\Models\Activity;
use App\Models\ActivitySession;
use App\Models\Organization;
use App\Services\Community\ManagedCommunityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

final class ActivitySessionController extends Controller
{
    public function index(Request $request, Organization $organization, Activity $activity, ManagedCommunityService $managed): View
    {
        $this->ensureNested($organization, $activity);
        Gate::authorize('view', $activity);

        return view('manager.activity-sessions.index', [
            'organization' => $organization,
            'activity' => $activity,
            'sessions' => $activity->sessions()->get(),
            'managedCommunities' => $managed->forUser($request->user()),
        ]);
    }

    public function create(Request $request, Organization $organization, Activity $activity, ManagedCommunityService $managed): View
    {
        $this->ensureNested($organization, $activity);
        Gate::authorize('update', $activity);
        $session = new ActivitySession(['session_number' => ((int) $activity->sessions()->withTrashed()->max('session_number')) + 1]);

        return $this->formView($request, $organization, $activity, $session, $managed);
    }

    public function store(StoreActivitySessionRequest $request, Organization $organization, Activity $activity): RedirectResponse
    {
        $this->ensureNested($organization, $activity);
        $activity->sessions()->create($request->validated());

        return to_route('manager.activities.sessions.index', [$organization, $activity])->with('status', 'Sesi Activity berhasil ditambahkan.');
    }

    public function edit(Request $request, Organization $organization, Activity $activity, ActivitySession $session, ManagedCommunityService $managed): View
    {
        $this->ensureNested($organization, $activity, $session);
        Gate::authorize('update', $activity);

        return $this->formView($request, $organization, $activity, $session, $managed);
    }

    public function update(UpdateActivitySessionRequest $request, Organization $organization, Activity $activity, ActivitySession $session): RedirectResponse
    {
        $this->ensureNested($organization, $activity, $session);
        $session->update($request->validated());

        return to_route('manager.activities.sessions.index', [$organization, $activity])->with('status', 'Sesi Activity berhasil diperbarui.');
    }

    public function destroy(Request $request, Organization $organization, Activity $activity, ActivitySession $session): RedirectResponse
    {
        $this->ensureNested($organization, $activity, $session);
        Gate::authorize('update', $activity);
        $session->delete();

        return back()->with('status', 'Sesi Activity berhasil dihapus.');
    }

    public function move(Request $request, Organization $organization, Activity $activity, ActivitySession $session): RedirectResponse
    {
        $this->ensureNested($organization, $activity, $session);
        Gate::authorize('update', $activity);
        $direction = validator($request->all(), ['direction' => ['required', Rule::in(['up', 'down'])]])->validate()['direction'];

        DB::transaction(function () use ($activity, $session, $direction): void {
            $session = ActivitySession::query()->whereKey($session->getKey())->lockForUpdate()->firstOrFail();
            $other = ActivitySession::query()->where('activity_id', $activity->getKey())
                ->when($direction === 'up', fn ($query) => $query->where('session_number', '<', $session->session_number)->orderByDesc('session_number'))
                ->when($direction === 'down', fn ($query) => $query->where('session_number', '>', $session->session_number)->orderBy('session_number'))
                ->lockForUpdate()->first();
            if (! $other) {
                return;
            }

            $original = $session->session_number;
            $temporary = ((int) $activity->sessions()->withTrashed()->max('session_number')) + 1;
            $session->forceFill(['session_number' => $temporary])->save();
            $session->forceFill(['session_number' => $other->session_number]);
            $other->forceFill(['session_number' => $original])->save();
            $session->save();
        });

        return back()->with('status', 'Urutan sesi berhasil diperbarui.');
    }

    private function formView(Request $request, Organization $organization, Activity $activity, ActivitySession $session, ManagedCommunityService $managed): View
    {
        return view($session->exists ? 'manager.activity-sessions.edit' : 'manager.activity-sessions.create', [
            'organization' => $organization,
            'activity' => $activity,
            'session' => $session,
            'managedCommunities' => $managed->forUser($request->user()),
        ]);
    }

    private function ensureNested(Organization $organization, Activity $activity, ?ActivitySession $session = null): void
    {
        abort_unless($activity->organization_id === $organization->getKey(), 404);
        if ($session) {
            abort_unless($session->activity_id === $activity->getKey(), 404);
        }
    }
}
