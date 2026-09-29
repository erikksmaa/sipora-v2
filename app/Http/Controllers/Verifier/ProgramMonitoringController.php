<?php

namespace App\Http\Controllers\Verifier;

use App\Actions\Verifier\ReviewProgramLogbookAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Verifier\ReviewProgramLogbookRequest;
use App\Models\ProgramLogbook;
use App\Models\ProgramLogbookMedia;
use App\Services\ProgramProgressService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ProgramMonitoringController extends Controller
{
    public function index(Request $request): View
    {
        $input = validator($request->query(), ['status' => ['nullable', Rule::in(['all', 'submitted', 'revision', 'approved'])], 'search' => ['nullable', 'string', 'max:120']])->validate();
        $status = $input['status'] ?? 'submitted';
        $search = trim($input['search'] ?? '');
        $logbooks = ProgramLogbook::query()->with(['program.organization', 'activity', 'creator'])->whereNot('status', ProgramLogbook::STATUS_DRAFT)->when($status !== 'all', fn ($q) => $q->where('status', $status))->when($search !== '', fn ($q) => $q->whereHas('program', fn ($p) => $p->where('title', 'like', "%{$search}%")->orWhereHas('organization', fn ($o) => $o->where('name', 'like', "%{$search}%"))))->orderByDesc('submitted_at')->paginate(15)->withQueryString();

        return view('verifier.program-monitoring.index', compact('logbooks', 'status', 'search'));
    }

    public function show(ProgramLogbook $logbook, ProgramProgressService $progress): View
    {
        Gate::authorize('view', $logbook);
        $logbook->load(['program.organization', 'program.category', 'program.activities', 'program.logbooks', 'activity', 'creator', 'reviewer', 'media.uploader']);

        return view('verifier.program-monitoring.show', ['logbook' => $logbook, 'metrics' => $progress->summarize($logbook->program)]);
    }

    public function review(ReviewProgramLogbookRequest $request, ProgramLogbook $logbook, ReviewProgramLogbookAction $action): RedirectResponse
    {
        $action->execute($request->user(), $logbook, $request->validated('decision'), $request->validated('notes'));

        return back()->with('status', 'Keputusan review Logbook disimpan.');
    }

    public function media(ProgramLogbook $logbook, ProgramLogbookMedia $media): StreamedResponse
    {
        abort_unless($media->logbook_id === $logbook->getKey(), 404);
        Gate::authorize('view', $logbook);
        abort_unless(Storage::disk('program_logbook_media')->exists($media->file_path), 404);
        $mime = Storage::disk('program_logbook_media')->mimeType($media->file_path) ?: 'application/octet-stream';

        return response()->stream(function () use ($media): void {
            $stream = Storage::disk('program_logbook_media')->readStream($media->file_path);
            abort_unless(is_resource($stream), 404);
            fpassthru($stream);
            fclose($stream);
        }, 200, ['Content-Type' => $mime, 'Content-Disposition' => 'inline; filename="logbook-media.'.pathinfo($media->file_path, PATHINFO_EXTENSION).'"', 'Cache-Control' => 'private, no-store, max-age=0', 'X-Content-Type-Options' => 'nosniff']);
    }
}
