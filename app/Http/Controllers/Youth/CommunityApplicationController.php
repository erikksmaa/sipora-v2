<?php

namespace App\Http\Controllers\Youth;

use App\Actions\Youth\SaveCommunityDraftAction;
use App\Actions\Youth\SubmitCommunityForReviewAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Youth\StoreCommunityRequest;
use App\Http\Requests\Youth\SubmitCommunityRequest;
use App\Http\Requests\Youth\UpdateCommunityRequest;
use App\Models\AdministrativeArea;
use App\Models\Organization;
use App\Models\OrganizationCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class CommunityApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $communities = $request->user()->createdOrganizations()
            ->with(['category', 'latestVerificationRequest'])
            ->latest()
            ->paginate(9);

        return view('youth.communities.index', compact('communities'));
    }

    public function create(): View
    {
        return view('youth.communities.create', $this->formOptions());
    }

    public function store(StoreCommunityRequest $request, SaveCommunityDraftAction $action): RedirectResponse
    {
        $community = $action->execute(
            $request->user(),
            $request->safe()->except('logo'),
            logo: $request->file('logo'),
        );

        return redirect()->route('youth.communities.show', $community)
            ->with('status', 'Draft komunitas berhasil disimpan.');
    }

    public function show(Organization $organization): View
    {
        Gate::authorize('view', $organization);
        $organization->load(['category', 'administrativeArea', 'latestVerificationRequest']);

        return view('youth.communities.show', compact('organization'));
    }

    public function edit(Organization $organization): View
    {
        Gate::authorize('update', $organization);
        $organization->load(['category', 'administrativeArea', 'latestVerificationRequest']);

        return view('youth.communities.edit', ['organization' => $organization, ...$this->formOptions()]);
    }

    public function update(UpdateCommunityRequest $request, Organization $organization, SaveCommunityDraftAction $action): RedirectResponse
    {
        $action->execute(
            $request->user(),
            $request->safe()->except('logo'),
            $organization,
            $request->file('logo'),
        );

        return redirect()->route('youth.communities.show', $organization)
            ->with('status', 'Draft komunitas berhasil diperbarui.');
    }

    public function submit(SubmitCommunityRequest $request, Organization $organization, SubmitCommunityForReviewAction $action): RedirectResponse
    {
        $action->execute($request->user(), $organization, $request->validated('submission_notes'));

        return redirect()->route('youth.communities.show', $organization)
            ->with('status', 'Pengajuan komunitas telah dikirim untuk verifikasi.');
    }

    public function logo(Organization $organization): StreamedResponse
    {
        Gate::authorize('view', $organization);
        abort_unless($organization->logo_path && Storage::disk('community_media')->exists($organization->logo_path), 404);

        $mime = Storage::disk('community_media')->mimeType($organization->logo_path) ?: 'application/octet-stream';

        return response()->stream(function () use ($organization): void {
            $stream = Storage::disk('community_media')->readStream($organization->logo_path);
            fpassthru($stream);
            if (is_resource($stream)) {
                fclose($stream);
            }
        }, 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="community-logo"',
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function formOptions(): array
    {
        return [
            'categories' => OrganizationCategory::query()->orderBy('name')->get(),
            'administrativeAreas' => AdministrativeArea::query()->orderBy('name')->get(),
        ];
    }
}
