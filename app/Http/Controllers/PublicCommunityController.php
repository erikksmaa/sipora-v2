<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\OrganizationMembership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class PublicCommunityController extends Controller
{
    public function show(Request $request, Organization $organization): View
    {
        $this->ensurePublic($organization);
        $organization->load(['category', 'administrativeArea'])->loadCount([
            'memberships as active_members_count' => fn ($query) => $query->where('membership_status', OrganizationMembership::STATUS_ACTIVE),
        ]);
        $membership = $request->user()?->organizationMemberships()
            ->where('organization_id', $organization->getKey())
            ->first();

        return view('public.communities.show', compact('organization', 'membership'));
    }

    public function logo(Organization $organization): StreamedResponse
    {
        $this->ensurePublic($organization);
        abort_unless($organization->logo_path && Storage::disk('community_media')->exists($organization->logo_path), 404);
        $mime = Storage::disk('community_media')->mimeType($organization->logo_path) ?: 'application/octet-stream';

        return response()->stream(function () use ($organization): void {
            $stream = Storage::disk('community_media')->readStream($organization->logo_path);
            abort_unless(is_resource($stream), 404);
            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="community-logo"',
            'Cache-Control' => 'public, max-age=3600',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function ensurePublic(Organization $organization): void
    {
        abort_unless(
            $organization->review_status === Organization::REVIEW_APPROVED
            && $organization->operational_status === Organization::OPERATIONAL_ACTIVE,
            404,
        );
    }
}
