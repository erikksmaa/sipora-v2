<?php

namespace App\Http\Controllers\Manager;

use App\Actions\Manager\AddProgramLogbookMediaAction;
use App\Actions\Manager\DeleteProgramLogbookAction;
use App\Actions\Manager\RemoveProgramLogbookMediaAction;
use App\Actions\Manager\SaveProgramLogbookAction;
use App\Actions\Manager\SubmitProgramLogbookAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\SaveProgramLogbookRequest;
use App\Http\Requests\Manager\StoreProgramLogbookMediaRequest;
use App\Models\Organization;
use App\Models\Program;
use App\Models\ProgramLogbook;
use App\Models\ProgramLogbookMedia;
use App\Services\Community\ManagedCommunityService;
use App\Services\ProgramProgressService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ProgramLogbookController extends Controller
{
    public function index(Request $request, Organization $organization, Program $program, ManagedCommunityService $managed, ProgramProgressService $progress): View
    {
        $this->program($organization, $program);
        Gate::authorize('view', $program);
        $program->load(['activities', 'logbooks.creator', 'logbooks.activity', 'logbooks.media']);

        return view('manager.program-logbooks.index', ['organization' => $organization, 'program' => $program, 'metrics' => $progress->summarize($program), 'managedCommunities' => $managed->forUser($request->user())]);
    }

    public function create(Request $request, Organization $organization, Program $program, ManagedCommunityService $managed): View
    {
        $this->program($organization, $program);
        Gate::authorize('create', [ProgramLogbook::class, $program]);

        return $this->form($request, $organization, $program, new ProgramLogbook, $managed);
    }

    public function store(SaveProgramLogbookRequest $request, Organization $organization, Program $program, SaveProgramLogbookAction $action): RedirectResponse
    {
        $this->program($organization, $program);
        $logbook = $action->execute($request->user(), $program, $request->validated());

        return to_route('manager.program-logbooks.show', [$organization, $program, $logbook])->with('status', 'Draft Logbook disimpan.');
    }

    public function show(Request $request, Organization $organization, Program $program, ProgramLogbook $logbook, ManagedCommunityService $managed): View
    {
        $this->logbook($organization, $program, $logbook);
        Gate::authorize('view', $logbook);
        $logbook->load(['creator', 'activity', 'media.uploader', 'reviewer']);

        return view('manager.program-logbooks.show', ['organization' => $organization, 'program' => $program, 'logbook' => $logbook, 'managedCommunities' => $managed->forUser($request->user())]);
    }

    public function edit(Request $request, Organization $organization, Program $program, ProgramLogbook $logbook, ManagedCommunityService $managed): View
    {
        $this->logbook($organization, $program, $logbook);
        Gate::authorize('update', $logbook);

        return $this->form($request, $organization, $program, $logbook, $managed);
    }

    public function update(SaveProgramLogbookRequest $request, Organization $organization, Program $program, ProgramLogbook $logbook, SaveProgramLogbookAction $action): RedirectResponse
    {
        $this->logbook($organization, $program, $logbook);
        $action->execute($request->user(), $program, $request->validated(), $logbook);

        return to_route('manager.program-logbooks.show', [$organization, $program, $logbook])->with('status', 'Logbook diperbarui.');
    }

    public function destroy(Request $request, Organization $organization, Program $program, ProgramLogbook $logbook, DeleteProgramLogbookAction $action): RedirectResponse
    {
        $this->logbook($organization, $program, $logbook);
        Gate::authorize('delete', $logbook);
        $action->execute($request->user(), $logbook);

        return to_route('manager.program-logbooks.index', [$organization, $program])->with('status', 'Logbook diarsipkan.');
    }

    public function submit(Request $request, Organization $organization, Program $program, ProgramLogbook $logbook, SubmitProgramLogbookAction $action): RedirectResponse
    {
        $this->logbook($organization, $program, $logbook);
        Gate::authorize('submit', $logbook);
        $action->execute($request->user(), $logbook);

        return back()->with('status', 'Logbook diajukan kepada Verifier.');
    }

    public function storeMedia(StoreProgramLogbookMediaRequest $request, Organization $organization, Program $program, ProgramLogbook $logbook, AddProgramLogbookMediaAction $action): RedirectResponse
    {
        $this->logbook($organization, $program, $logbook);
        $action->execute($request->user(), $logbook, $request->file('file'), $request->validated('caption'));

        return back()->with('status', 'Media ditambahkan.');
    }

    public function destroyMedia(Request $request, Organization $organization, Program $program, ProgramLogbook $logbook, ProgramLogbookMedia $media, RemoveProgramLogbookMediaAction $action): RedirectResponse
    {
        $this->ensureMedia($organization, $program, $logbook, $media);
        Gate::authorize('update', $logbook);
        $action->execute($request->user(), $media);

        return back()->with('status', 'Media diarsipkan.');
    }

    public function media(Organization $organization, Program $program, ProgramLogbook $logbook, ProgramLogbookMedia $media): StreamedResponse
    {
        $this->ensureMedia($organization, $program, $logbook, $media);
        Gate::authorize('view', $logbook);

        return $this->stream($media);
    }

    private function form(Request $request, Organization $organization, Program $program, ProgramLogbook $logbook, ManagedCommunityService $managed): View
    {
        return view('manager.program-logbooks.form', ['organization' => $organization, 'program' => $program, 'logbook' => $logbook, 'activities' => $program->activities()->orderBy('title')->get(), 'managedCommunities' => $managed->forUser($request->user())]);
    }

    private function program(Organization $organization, Program $program): void
    {
        abort_unless($program->organization_id === $organization->getKey(), 404);
    }

    private function logbook(Organization $organization, Program $program, ProgramLogbook $logbook): void
    {
        $this->program($organization, $program);
        abort_unless($logbook->program_id === $program->getKey(), 404);
    }

    private function ensureMedia(Organization $organization, Program $program, ProgramLogbook $logbook, ProgramLogbookMedia $media): void
    {
        $this->logbook($organization, $program, $logbook);
        abort_unless($media->logbook_id === $logbook->getKey(), 404);
    }

    private function stream(ProgramLogbookMedia $media): StreamedResponse
    {
        abort_unless(Storage::disk('program_logbook_media')->exists($media->file_path), 404);
        $mime = Storage::disk('program_logbook_media')->mimeType($media->file_path) ?: 'application/octet-stream';

        return response()->stream(function () use ($media): void {
            $stream = Storage::disk('program_logbook_media')->readStream($media->file_path);
            abort_unless(is_resource($stream),404);
            fpassthru($stream);
            fclose($stream);
        }, 200, ['Content-Type' => $mime, 'Content-Disposition' => 'inline; filename="logbook-media.'.pathinfo($media->file_path,PATHINFO_EXTENSION).'"', 'Cache-Control' => 'private, no-store, max-age=0', 'X-Content-Type-Options' => 'nosniff']);
    }
}
