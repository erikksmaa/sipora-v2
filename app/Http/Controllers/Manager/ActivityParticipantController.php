<?php

namespace App\Http\Controllers\Manager;

use App\Actions\Manager\CompleteActivityParticipationAction;
use App\Actions\Manager\IssueActivityCertificateAction;
use App\Actions\Manager\ReviewActivityRegistrationAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\CompleteActivityParticipationRequest;
use App\Http\Requests\Manager\ReviewActivityRegistrationRequest;
use App\Models\Activity;
use App\Models\ActivityParticipation;
use App\Models\Organization;
use App\Services\Community\ManagedCommunityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

final class ActivityParticipantController extends Controller
{
    public function index(Request $request, Organization $organization, Activity $activity, ManagedCommunityService $managed): View
    {
        $this->ensureNested($organization, $activity);
        Gate::authorize('view', $activity);
        $input = validator($request->query(), [
            'status' => ['nullable', Rule::in(['all', 'pending', 'accepted', 'rejected', 'cancelled'])],
            'completion' => ['nullable', Rule::in(['all', 'pending', 'completed', 'no_show'])],
        ])->validate();
        $status = $input['status'] ?? 'all';
        $completion = $input['completion'] ?? 'all';
        $participants = $activity->participations()->with(['user.profile', 'user.identity', 'certificate'])
            ->withCount([
                'attendances as present_count' => fn ($query) => $query->where('attendance_status', 'present'),
                'attendances as absent_count' => fn ($query) => $query->where('attendance_status', 'absent'),
                'attendances as excused_count' => fn ($query) => $query->where('attendance_status', 'excused'),
            ])
            ->when($status !== 'all', fn ($query) => $query->where('registration_status', $status))
            ->when($completion !== 'all', fn ($query) => $query->where('completion_status', $completion))
            ->latest('requested_at')->paginate(20)->withQueryString();
        $counts = $activity->participations()->selectRaw('registration_status, COUNT(*) total')
            ->groupBy('registration_status')->pluck('total', 'registration_status');

        return view('manager.participants.index', [
            'organization' => $organization,
            'activity' => $activity,
            'participants' => $participants,
            'counts' => $counts,
            'status' => $status,
            'completion' => $completion,
            'sessionCount' => $activity->sessions()->count(),
            'managedCommunities' => $managed->forUser($request->user()),
        ]);
    }

    public function complete(CompleteActivityParticipationRequest $request, Organization $organization, Activity $activity, ActivityParticipation $participation, CompleteActivityParticipationAction $action): RedirectResponse
    {
        $this->ensureNested($organization, $activity, $participation);
        Gate::authorize('completeParticipation', $activity);
        $action->execute($request->user(), $activity, $participation, $request->validated('decision'));

        return back()->with('status', 'Status penyelesaian peserta berhasil disimpan.');
    }

    public function review(ReviewActivityRegistrationRequest $request, Organization $organization, Activity $activity, ActivityParticipation $participation, ReviewActivityRegistrationAction $action): RedirectResponse
    {
        $this->ensureNested($organization, $activity, $participation);
        $action->execute($request->user(), $activity, $participation, $request->validated('decision'), $request->validated('notes'));

        return back()->with('status', 'Keputusan peserta berhasil disimpan.');
    }

    public function issueCertificate(Request $request, Organization $organization, Activity $activity, ActivityParticipation $participation, IssueActivityCertificateAction $action): RedirectResponse
    {
        $this->ensureNested($organization, $activity, $participation);
        Gate::authorize('completeParticipation', $activity);
        $action->execute($request->user(), $activity, $participation);

        return back()->with('status', 'Sertifikat Activity berhasil diterbitkan.');
    }

    private function ensureNested(Organization $organization, Activity $activity, ?ActivityParticipation $participation = null): void
    {
        abort_unless($activity->organization_id === $organization->getKey(), 404);
        if ($participation) {
            abort_unless($participation->activity_id === $activity->getKey(), 404);
        }
    }
}
