<?php

namespace App\Http\Controllers\Verifier;

use App\Actions\Verifier\ReviewProgramProposalAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Verifier\ReviewProgramProposalRequest;
use App\Models\ProgramProposal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ProgramProposalVerificationController extends Controller
{
    public function index(Request $request): View
    {
        $input = validator($request->query(), ['status' => ['nullable', Rule::in(['all', 'submitted', 'under_review', 'revision', 'rejected', 'approved'])], 'search' => ['nullable', 'string', 'max:120']])->validate();
        $status = $input['status'] ?? ProgramProposal::STATUS_SUBMITTED;
        $search = trim($input['search'] ?? '');
        $proposals = ProgramProposal::query()->with(['program.organization', 'program.category', 'submissionActivity.causer'])
            ->whereNot('status', ProgramProposal::STATUS_DRAFT)
            ->when($status !== 'all', fn ($query) => $query->where('status', $status))
            ->when($search !== '', fn ($query) => $query->whereHas('program', fn ($programs) => $programs->where('title', 'like', "%{$search}%")->orWhereHas('organization', fn ($organizations) => $organizations->where('name', 'like', "%{$search}%"))))
            ->orderByDesc('submitted_at')->paginate(15)->withQueryString();

        return view('verifier.program-proposals.index', compact('proposals', 'status', 'search'));
    }

    public function show(ProgramProposal $proposal): View
    {
        Gate::authorize('view', $proposal);
        $proposal->load(['program.organization', 'program.category', 'program.creator', 'program.activities.category', 'reviewer', 'submissionActivity.causer']);

        return view('verifier.program-proposals.show', compact('proposal'));
    }

    public function review(ReviewProgramProposalRequest $request, ProgramProposal $proposal, ReviewProgramProposalAction $action): RedirectResponse
    {
        $action->execute($request->user(), $proposal, $request->validated('decision'), $request->validated('notes'));

        return back()->with('status', 'Keputusan verifikasi Proposal berhasil disimpan.');
    }

    public function document(ProgramProposal $proposal): StreamedResponse
    {
        Gate::authorize('view', $proposal);
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
