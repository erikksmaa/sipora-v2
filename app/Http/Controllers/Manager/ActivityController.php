<?php

namespace App\Http\Controllers\Manager;

use App\Actions\Manager\ArchiveActivityAction;
use App\Actions\Manager\CompleteActivityExecutionAction;
use App\Actions\Manager\PublishActivityAction;
use App\Actions\Manager\SaveActivityDraftAction;
use App\Actions\Manager\SubmitActivityForReviewAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\StoreActivityRequest;
use App\Http\Requests\Manager\UpdateActivityRequest;
use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\AdministrativeArea;
use App\Models\Organization;
use App\Services\Community\ManagedCommunityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ActivityController extends Controller
{
    public function index(Request $request, Organization $organization, ManagedCommunityService $managed): View
    {
        Gate::authorize('manageMemberships', $organization);
        $activities = $organization->activities()->with('category')->latest()->paginate(15);

        return view('manager.activities.index', ['organization' => $organization, 'activities' => $activities,
            'managedCommunities' => $managed->forUser($request->user())]);
    }

    public function create(Request $request, Organization $organization, ManagedCommunityService $managed): View
    {
        Gate::authorize('manageMemberships', $organization);

        return $this->formView($request, $organization, $managed, new Activity);
    }

    public function store(StoreActivityRequest $request, Organization $organization, SaveActivityDraftAction $action): RedirectResponse
    {
        $activity = $action->execute($request->user(), $organization, $request->validated(), $request->file('poster'));

        return to_route('manager.activities.show', [$organization, $activity])->with('status', 'Draft Activity berhasil disimpan.');
    }

    public function show(Request $request, Organization $organization, Activity $activity, ManagedCommunityService $managed): View
    {
        $this->ensureBelongs($organization, $activity);
        Gate::authorize('view', $activity);
        $activity->load(['category', 'administrativeArea', 'latestReview']);

        return view('manager.activities.show', ['organization' => $organization, 'activity' => $activity,
            'managedCommunities' => $managed->forUser($request->user())]);
    }

    public function edit(Request $request, Organization $organization, Activity $activity, ManagedCommunityService $managed): View
    {
        $this->ensureBelongs($organization, $activity);
        Gate::authorize('update', $activity);

        return $this->formView($request, $organization, $managed, $activity);
    }

    public function update(UpdateActivityRequest $request, Organization $organization, Activity $activity, SaveActivityDraftAction $action): RedirectResponse
    {
        $action->execute($request->user(), $organization, $request->validated(), $request->file('poster'), $activity);

        return to_route('manager.activities.show', [$organization, $activity])->with('status', 'Draft Activity berhasil diperbarui.');
    }

    public function submit(Request $request, Organization $organization, Activity $activity, SubmitActivityForReviewAction $action): RedirectResponse
    {
        $this->ensureBelongs($organization, $activity);
        Gate::authorize('submit', $activity);
        $action->execute($request->user(), $activity);

        return back()->with('status', 'Activity dikirim untuk verifikasi.');
    }

    public function publish(Request $request, Organization $organization, Activity $activity, PublishActivityAction $action): RedirectResponse
    {
        $this->ensureBelongs($organization, $activity);
        Gate::authorize('publish', $activity);
        $action->execute($request->user(), $activity);

        return back()->with('status', 'Activity berhasil dipublikasikan.');
    }

    public function archive(Request $request, Organization $organization, Activity $activity, ArchiveActivityAction $action): RedirectResponse
    {
        $this->ensureBelongs($organization, $activity);
        Gate::authorize('archive', $activity);
        $action->execute($request->user(), $activity);

        return back()->with('status', 'Activity berhasil diarsipkan.');
    }

    public function completeExecution(Request $request, Organization $organization, Activity $activity, CompleteActivityExecutionAction $action): RedirectResponse
    {
        $this->ensureBelongs($organization, $activity);
        Gate::authorize('completeExecution', $activity);
        $action->execute($request->user(), $activity);

        return back()->with('status', 'Eksekusi Activity berhasil diselesaikan. Hasil peserta kini dapat ditetapkan.');
    }

    public function poster(Organization $organization, Activity $activity): StreamedResponse
    {
        $this->ensureBelongs($organization, $activity);
        Gate::authorize('view', $activity);

        return $this->streamPoster($activity);
    }

    private function formView(Request $request, Organization $organization, ManagedCommunityService $managed, Activity $activity): View
    {
        return view($activity->exists ? 'manager.activities.edit' : 'manager.activities.create', [
            'organization' => $organization, 'activity' => $activity,
            'managedCommunities' => $managed->forUser($request->user()),
            'categories' => ActivityCategory::query()->orderBy('name')->get(),
            'areas' => AdministrativeArea::query()->orderBy('name')->get(),
        ]);
    }

    private function ensureBelongs(Organization $organization, Activity $activity): void
    {
        abort_unless($activity->organization_id === $organization->getKey(), 404);
    }

    private function streamPoster(Activity $activity): StreamedResponse
    {
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
