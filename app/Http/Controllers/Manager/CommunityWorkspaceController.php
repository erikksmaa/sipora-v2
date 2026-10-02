<?php

namespace App\Http\Controllers\Manager;

use App\Actions\Manager\UpdateCommunityProfileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\UpdateCommunityProfileRequest;
use App\Models\Activity;
use App\Models\ActivityParticipation;
use App\Models\FinancialReport;
use App\Models\Organization;
use App\Models\OrganizationMembership;
use App\Models\Program;
use App\Models\ProgramLogbook;
use App\Models\ProgramProposal;
use App\Services\Community\CommunityProfileCompletionService;
use App\Services\Community\ManagedCommunityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

final class CommunityWorkspaceController extends Controller
{
    public function show(
        Request $request,
        Organization $organization,
        ManagedCommunityService $managed,
        CommunityProfileCompletionService $completion,
    ): View {
        Gate::authorize('manageMemberships', $organization);
        $organization->load('category');
        $counts = [
            'members' => $organization->memberships()->where('membership_status', OrganizationMembership::STATUS_ACTIVE)->count(),
            'managers' => $organization->memberships()->where('membership_status', OrganizationMembership::STATUS_ACTIVE)
                ->whereIn('access_role', [OrganizationMembership::ROLE_LEADER, OrganizationMembership::ROLE_MANAGER])->count(),
            'pending' => $organization->memberships()->where('membership_status', OrganizationMembership::STATUS_PENDING)->count(),
            'activities' => $organization->activities()->count(),
            'published_activities' => $organization->activities()->where('publication_status', Activity::PUBLICATION_PUBLISHED)->count(),
            'pending_participants' => ActivityParticipation::query()->whereHas('activity', fn ($query) => $query->where('organization_id', $organization->getKey()))->where('registration_status', ActivityParticipation::REGISTRATION_PENDING)->count(),
            'running_programs' => $organization->programs()->where('execution_status', Program::STATUS_RUNNING)->count(),
        ];

        $activitiesNeedingParticipants = $organization->activities()->whereHas('participations', fn ($query) => $query->where('registration_status', ActivityParticipation::REGISTRATION_PENDING))->withCount(['participations as pending_count' => fn ($query) => $query->where('registration_status', ActivityParticipation::REGISTRATION_PENDING)])->orderBy('start_at')->limit(3)->get();
        $programsNeedingRevision = $organization->programs()->with(['latestProposal', 'latestFinancialReport'])->where(function ($query) {
            $query->whereHas('latestProposal', fn ($proposal) => $proposal->where('status', ProgramProposal::STATUS_REVISION))
                ->orWhereHas('latestFinancialReport', fn ($report) => $report->where('status', FinancialReport::STATUS_REVISION))
                ->orWhereHas('logbooks', fn ($logbook) => $logbook->where('status', ProgramLogbook::STATUS_REVISION));
        })->limit(3)->get();

        return view('manager.dashboard', [
            'organization' => $organization,
            'managedCommunities' => $managed->forUser($request->user()),
            'counts' => $counts,
            'completion' => $completion->calculate($organization),
            'activitiesNeedingParticipants' => $activitiesNeedingParticipants,
            'programsNeedingRevision' => $programsNeedingRevision,
            'upcomingActivities' => $organization->activities()->where('end_at', '>=', now())->orderBy('start_at')->limit(4)->get(),
            'runningPrograms' => $organization->programs()->where('execution_status', Program::STATUS_RUNNING)->with(['latestProposal', 'latestFinancialReport'])->limit(3)->get(),
        ]);
    }

    public function edit(Request $request, Organization $organization, ManagedCommunityService $managed): View
    {
        Gate::authorize('manageMemberships', $organization);

        return view('manager.profile.edit', [
            'organization' => $organization,
            'managedCommunities' => $managed->forUser($request->user()),
        ]);
    }

    public function update(UpdateCommunityProfileRequest $request, Organization $organization, UpdateCommunityProfileAction $action): RedirectResponse
    {
        $action->execute($organization, $request->safe()->except('logo'), $request->file('logo'));

        return to_route('manager.profile.edit', $organization)->with('status', 'Profil komunitas berhasil diperbarui.');
    }
}
