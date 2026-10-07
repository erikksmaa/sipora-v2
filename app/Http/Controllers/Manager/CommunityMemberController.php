<?php

namespace App\Http\Controllers\Manager;

use App\Actions\Membership\ChangeCommunityMemberRoleAction;
use App\Actions\Membership\RemoveCommunityMemberAction;
use App\Actions\Membership\ReviewCommunityJoinRequestAction;
use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrganizationMembership;
use App\Services\Community\ManagedCommunityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

final class CommunityMemberController extends Controller
{
    public function index(Request $request, Organization $organization, ManagedCommunityService $managed): View
    {
        Gate::authorize('manageMemberships', $organization);
        $search = trim((string) $request->query('q', ''));
        $search = mb_substr($search, 0, 80);
        $members = $organization->memberships()->with('user.profile')
            ->where('membership_status', OrganizationMembership::STATUS_ACTIVE)
            ->when($search !== '', fn ($query) => $query->whereHas('user', fn ($user) => $user->where('name', 'like', '%'.$search.'%')->orWhere('email', 'like', '%'.$search.'%')))
            ->orderByRaw("FIELD(access_role, 'leader', 'manager', 'member')")
            ->paginate(20)->withQueryString();
        $actorMembership = $organization->memberships()->where('user_id', $request->user()->getKey())->firstOrFail();

        return view('manager.members.index', [
            'organization' => $organization,
            'members' => $members,
            'actorMembership' => $actorMembership,
            'managedCommunities' => $managed->forUser($request->user()),
            'search' => $search,
        ]);
    }

    public function joinRequests(Request $request, Organization $organization, ManagedCommunityService $managed): View
    {
        Gate::authorize('manageMemberships', $organization);

        return view('manager.members.join-requests', [
            'organization' => $organization,
            'pending' => $organization->memberships()->with('user.profile')
                ->where('membership_status', OrganizationMembership::STATUS_PENDING)
                ->oldest('requested_at')->paginate(20),
            'managedCommunities' => $managed->forUser($request->user()),
        ]);
    }

    public function review(Request $request, Organization $organization, OrganizationMembership $membership, ReviewCommunityJoinRequestAction $action): RedirectResponse
    {
        abort_unless(hash_equals($organization->getKey(), $membership->organization_id), 404);
        Gate::authorize('review', $membership);
        $validated = $request->validate(['decision' => ['required', Rule::in(['accepted', 'rejected'])]]);
        $action->execute($request->user(), $membership, $validated['decision']);

        return to_route('manager.join-requests.index', $organization)->with('status', 'Permintaan bergabung telah diproses.');
    }

    public function updateRole(Request $request, Organization $organization, OrganizationMembership $membership, ChangeCommunityMemberRoleAction $action): RedirectResponse
    {
        abort_unless(hash_equals($organization->getKey(), $membership->organization_id), 404);
        Gate::authorize('changeRole', $membership);
        $validated = $request->validate(['access_role' => ['required', Rule::in(['manager', 'member'])]]);
        $action->execute($request->user(), $membership, $validated['access_role']);

        return to_route('manager.members.index', $organization)->with('status', 'Peran anggota berhasil diperbarui.');
    }

    public function destroy(Request $request, Organization $organization, OrganizationMembership $membership, RemoveCommunityMemberAction $action): RedirectResponse
    {
        abort_unless(hash_equals($organization->getKey(), $membership->organization_id), 404);
        Gate::authorize('remove', $membership);
        $action->execute($request->user(), $membership);

        return to_route('manager.members.index', $organization)->with('status', 'Anggota telah dikeluarkan dari komunitas.');
    }
}
