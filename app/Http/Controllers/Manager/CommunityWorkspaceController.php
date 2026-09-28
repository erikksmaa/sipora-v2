<?php

namespace App\Http\Controllers\Manager;

use App\Actions\Manager\UpdateCommunityProfileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\UpdateCommunityProfileRequest;
use App\Models\Organization;
use App\Models\OrganizationMembership;
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
        ];

        return view('manager.dashboard', [
            'organization' => $organization,
            'managedCommunities' => $managed->forUser($request->user()),
            'counts' => $counts,
            'completion' => $completion->calculate($organization),
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
