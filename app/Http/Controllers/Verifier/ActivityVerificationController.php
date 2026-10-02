<?php

namespace App\Http\Controllers\Verifier;

use App\Actions\Verifier\ReviewActivityAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Verifier\ReviewActivityRequest;
use App\Models\Activity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ActivityVerificationController extends Controller
{
    public function index(Request $request): View
    {
        $input = validator($request->query(), ['status' => ['nullable', Rule::in(['all', 'pending_review', 'revision', 'rejected', 'approved'])], 'search' => ['nullable', 'string', 'max:120']])->validate();
        $status = $input['status'] ?? Activity::REVIEW_PENDING;
        $search = trim($input['search'] ?? '');
        $activities = Activity::query()->with(['organization', 'category', 'creator'])
            ->whereNot('review_status', Activity::REVIEW_DRAFT)
            ->whereDoesntHave('organization.memberships', fn ($memberships) => $memberships
                ->where('user_id', $request->user()->getKey())
                ->where('membership_status', 'active')
                ->whereIn('access_role', ['leader', 'manager']))
            ->when($status !== 'all', fn ($query) => $query->where('review_status', $status))
            ->when($search !== '', fn ($query) => $query->where(fn ($match) => $match->where('title', 'like', "%{$search}%")
                ->orWhereHas('organization', fn ($organizations) => $organizations->where('name', 'like', "%{$search}%"))))
            ->latest()->paginate(15)->withQueryString();

        return view('verifier.activities.index', compact('activities', 'status', 'search'));
    }

    public function show(Activity $activity): View
    {
        Gate::authorize('viewForVerification', $activity);
        $activity->load(['organization', 'category', 'creator.profile', 'administrativeArea', 'latestReview']);

        return view('verifier.activities.show', compact('activity'));
    }

    public function review(ReviewActivityRequest $request, Activity $activity, ReviewActivityAction $action): RedirectResponse
    {
        $action->execute($request->user(), $activity, $request->validated('decision'), $request->validated('notes'));

        return back()->with('status', 'Keputusan verifikasi Activity berhasil disimpan.');
    }

    public function poster(Activity $activity): StreamedResponse
    {
        Gate::authorize('viewForVerification', $activity);
        abort_unless($activity->poster_path && Storage::disk('activity_media')->exists($activity->poster_path), 404);
        $mime = Storage::disk('activity_media')->mimeType($activity->poster_path) ?: 'application/octet-stream';

        return response()->stream(function () use ($activity): void {
            $stream = Storage::disk('activity_media')->readStream($activity->poster_path);
            abort_unless(is_resource($stream), 404);
            fpassthru($stream);
            fclose($stream);
        }, 200, ['Content-Type' => $mime, 'Content-Disposition' => 'inline; filename="activity-poster"',
            'Cache-Control' => 'private, no-store, max-age=0', 'X-Content-Type-Options' => 'nosniff']);
    }
}
