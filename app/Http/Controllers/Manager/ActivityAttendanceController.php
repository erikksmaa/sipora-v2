<?php

namespace App\Http\Controllers\Manager;

use App\Actions\Manager\RecordActivityAttendanceAction;
use App\Actions\Manager\BulkRecordActivityAttendanceAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\BulkRecordActivityAttendanceRequest;
use App\Http\Requests\Manager\RecordActivityAttendanceRequest;
use App\Models\Activity;
use App\Models\ActivityParticipation;
use App\Models\ActivitySession;
use App\Models\Organization;
use App\Services\Community\ManagedCommunityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

final class ActivityAttendanceController extends Controller
{
    public function index(Request $request, Organization $organization, Activity $activity, ActivitySession $session, ManagedCommunityService $managed): View
    {
        $this->ensureNested($organization, $activity, $session);
        Gate::authorize('view', $activity);
        $participants = $activity->participations()
            ->where('registration_status', ActivityParticipation::REGISTRATION_ACCEPTED)
            ->with(['user.profile', 'attendances' => fn ($query) => $query->where('activity_session_id', $session->getKey())])
            ->orderBy('requested_at')->get();
        $counts = $session->attendances()
            ->whereHas('participation', fn ($query) => $query->where('registration_status', ActivityParticipation::REGISTRATION_ACCEPTED))
            ->selectRaw('attendance_status, COUNT(*) total')
            ->groupBy('attendance_status')->pluck('total', 'attendance_status');
        $acceptedCount = $participants->count();

        return view('manager.attendance.index', [
            'organization' => $organization,
            'activity' => $activity,
            'session' => $session,
            'sessions' => $activity->sessions()->get(),
            'participants' => $participants,
            'counts' => $counts,
            'acceptedCount' => $acceptedCount,
            'unmarkedCount' => $acceptedCount - (int) $counts->sum(),
            'managedCommunities' => $managed->forUser($request->user()),
        ]);
    }

    public function bulkUpdate(BulkRecordActivityAttendanceRequest $request, Organization $organization, Activity $activity, ActivitySession $session, BulkRecordActivityAttendanceAction $action): RedirectResponse
    {
        $this->ensureNested($organization, $activity, $session);
        $action->execute($request->user(), $activity, $session, $request->validated('attendance'));

        return back()->with('status', 'Presensi seluruh peserta berhasil disimpan.');
    }

    public function update(RecordActivityAttendanceRequest $request, Organization $organization, Activity $activity, ActivitySession $session, ActivityParticipation $participation, RecordActivityAttendanceAction $action): RedirectResponse
    {
        $this->ensureNested($organization, $activity, $session, $participation);
        $action->execute($request->user(), $activity, $session, $participation, $request->validated());

        return back()->with('status', 'Presensi peserta berhasil disimpan.');
    }

    private function ensureNested(Organization $organization, Activity $activity, ActivitySession $session, ?ActivityParticipation $participation = null): void
    {
        abort_unless($activity->organization_id === $organization->getKey(), 404);
        abort_unless($session->activity_id === $activity->getKey(), 404);
        if ($participation) {
            abort_unless($participation->activity_id === $activity->getKey(), 404);
        }
    }
}
