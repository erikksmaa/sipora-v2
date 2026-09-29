<?php

namespace App\Http\Controllers\Manager;

use App\Actions\Manager\CreateProgramProposalRevisionAction;
use App\Actions\Manager\SaveProgramProposalAction;
use App\Actions\Manager\SubmitProgramProposalAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\SaveProgramProposalRequest;
use App\Models\Organization;
use App\Models\Program;
use App\Models\ProgramProposal;
use App\Services\Community\ManagedCommunityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ProgramProposalController extends Controller
{
    public function create(Request $request, Organization $organization, Program $program, ManagedCommunityService $managed): View
    {
        $this->ensureProgram($organization, $program);
        Gate::authorize('update', $program);
        abort_if($program->latestProposal()->exists(), 409, 'Proposal Program sudah tersedia.');

        return view('manager.program-proposals.form', ['organization' => $organization, 'program' => $program,
            'proposal' => new ProgramProposal, 'managedCommunities' => $managed->forUser($request->user())]);
    }

    public function store(SaveProgramProposalRequest $request, Organization $organization, Program $program, SaveProgramProposalAction $action): RedirectResponse
    {
        $this->ensureProgram($organization, $program);
        $action->execute($request->user(), $program, $request->validated(), $request->file('proposal_document'));

        return to_route('manager.programs.show', [$organization, $program])->with('status', 'Draft Proposal berhasil dibuat.');
    }

    public function edit(Request $request, Organization $organization, Program $program, ProgramProposal $proposal, ManagedCommunityService $managed): View
    {
        $this->ensureProposal($organization, $program, $proposal);
        Gate::authorize('update', $proposal);

        return view('manager.program-proposals.form', compact('organization', 'program', 'proposal') + ['managedCommunities' => $managed->forUser($request->user())]);
    }

    public function update(SaveProgramProposalRequest $request, Organization $organization, Program $program, ProgramProposal $proposal, SaveProgramProposalAction $action): RedirectResponse
    {
        $this->ensureProposal($organization, $program, $proposal);
        $action->execute($request->user(), $program, $request->validated(), $request->file('proposal_document'), $proposal);

        return to_route('manager.programs.show', [$organization, $program])->with('status', 'Draft Proposal berhasil diperbarui.');
    }

    public function submit(Request $request, Organization $organization, Program $program, ProgramProposal $proposal, SubmitProgramProposalAction $action): RedirectResponse
    {
        $this->ensureProposal($organization, $program, $proposal);
        Gate::authorize('submit', $proposal);
        $action->execute($request->user(), $proposal);

        return back()->with('status', 'Proposal dikirim kepada Verifier.');
    }

    public function revise(Request $request, Organization $organization, Program $program, ProgramProposal $proposal, CreateProgramProposalRevisionAction $action): RedirectResponse
    {
        $this->ensureProposal($organization, $program, $proposal);
        Gate::authorize('revise', $proposal);
        $revision = $action->execute($request->user(), $proposal);

        return to_route('manager.program-proposals.edit', [$organization, $program, $revision])->with('status', 'Draft revisi versi '.$revision->version.' dibuat.');
    }

    public function document(Organization $organization, Program $program, ProgramProposal $proposal): StreamedResponse
    {
        $this->ensureProposal($organization, $program, $proposal);
        Gate::authorize('view', $proposal);

        return $this->stream($proposal);
    }

    private function ensureProgram(Organization $organization, Program $program): void
    {
        abort_unless($program->organization_id === $organization->getKey(), 404);
    }

    private function ensureProposal(Organization $organization, Program $program, ProgramProposal $proposal): void
    {
        $this->ensureProgram($organization, $program);
        abort_unless($proposal->program_id === $program->getKey(), 404);
    }

    private function stream(ProgramProposal $proposal): StreamedResponse
    {
        abort_unless($proposal->proposal_document_path && Storage::disk('proposal_documents')->exists($proposal->proposal_document_path), 404);
        $mime = Storage::disk('proposal_documents')->mimeType($proposal->proposal_document_path) ?: 'application/octet-stream';

        return response()->stream(function () use ($proposal): void {
            $stream = Storage::disk('proposal_documents')->readStream($proposal->proposal_document_path);
            abort_unless(is_resource($stream), 404);
            fpassthru($stream);
            fclose($stream);
        }, 200, ['Content-Type' => $mime, 'Content-Disposition' => 'inline; filename="proposal-program-v'.$proposal->version.'.'.pathinfo($proposal->proposal_document_path, PATHINFO_EXTENSION).'"',
            'Cache-Control' => 'private, no-store, max-age=0', 'X-Content-Type-Options' => 'nosniff']);
    }
}
