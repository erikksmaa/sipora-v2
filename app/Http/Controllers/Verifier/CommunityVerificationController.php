<?php

namespace App\Http\Controllers\Verifier;

use App\Actions\Verifier\ReviewCommunityApplicationAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Verifier\ReviewCommunityRequest;
use App\Models\Organization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class CommunityVerificationController extends Controller
{
    public function index(Request $request): View
    {
        $validated = validator($request->query(), [
            'status' => ['nullable', Rule::in(['all', 'pending_review', 'revision', 'rejected', 'approved'])],
            'search' => ['nullable', 'string', 'max:120'],
        ])->validate();
        $status = $validated['status'] ?? Organization::REVIEW_PENDING;
        $search = trim($validated['search'] ?? '');

        $communities = Organization::query()
            ->whereHas('verificationRequests')
            ->with(['creator.profile', 'category', 'latestVerificationRequest'])
            ->when($status !== 'all', fn ($query) => $query->where('review_status', $status))
            ->when($search !== '', fn ($query) => $query->where(function ($match) use ($search): void {
                $match->where('name', 'like', "%{$search}%")
                    ->orWhereHas('creator', fn ($users) => $users->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%"));
            }))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $counts = Organization::query()
            ->whereHas('verificationRequests')
            ->selectRaw('review_status, COUNT(*) AS total')
            ->groupBy('review_status')
            ->pluck('total', 'review_status');

        return view('verifier.communities.index', compact('communities', 'counts', 'status', 'search'));
    }

    public function show(Organization $organization): View
    {
        abort_unless($organization->verificationRequests()->exists(), 404);
        $organization->load(['creator.profile', 'category', 'administrativeArea', 'latestVerificationRequest.submitter']);
        $previousReview = $organization->verificationRequests()
            ->whereIn('status', ['revision', 'rejected'])
            ->whereNotNull('review_notes')
            ->latest('reviewed_at')
            ->first();

        return view('verifier.communities.show', compact('organization', 'previousReview'));
    }

    public function review(ReviewCommunityRequest $request, Organization $organization, ReviewCommunityApplicationAction $action): RedirectResponse
    {
        $action->execute(
            $request->user(),
            $organization,
            $request->validated('decision'),
            $request->validated('review_notes'),
        );

        return to_route('verifier.community-verifications.show', $organization)
            ->with('status', 'Keputusan verifikasi komunitas berhasil disimpan.');
    }

    public function logo(Organization $organization): StreamedResponse
    {
        abort_unless($organization->verificationRequests()->exists(), 404);
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
            'Cache-Control' => 'private, no-store, max-age=0',
            'Pragma' => 'no-cache',
            'X-Content-Type-Options' => 'nosniff',
            'Content-Security-Policy' => "default-src 'none'; frame-ancestors 'self'",
        ]);
    }
}
