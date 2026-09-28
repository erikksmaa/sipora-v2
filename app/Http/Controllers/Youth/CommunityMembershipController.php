<?php

namespace App\Http\Controllers\Youth;

use App\Actions\Membership\LeaveCommunityAction;
use App\Actions\Membership\RequestCommunityMembershipAction;
use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class CommunityMembershipController extends Controller
{
    public function store(Request $request, Organization $organization, RequestCommunityMembershipAction $action): RedirectResponse
    {
        $action->execute($request->user(), $organization);

        return to_route('communities.show', $organization)->with('status', 'Permintaan bergabung berhasil dikirim.');
    }

    public function destroy(Request $request, Organization $organization, LeaveCommunityAction $action): RedirectResponse
    {
        $action->execute($request->user(), $organization);

        return to_route('communities.show', $organization)->with('status', 'Anda telah meninggalkan komunitas.');
    }
}
