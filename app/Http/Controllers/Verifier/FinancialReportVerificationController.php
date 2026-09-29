<?php

namespace App\Http\Controllers\Verifier;

use App\Actions\Verifier\ReviewFinancialReportAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Verifier\ReviewFinancialReportRequest;
use App\Models\FinancialItem;
use App\Models\FinancialReport;
use App\Services\FinancialReportTotalsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class FinancialReportVerificationController extends Controller
{
    public function index(Request $request): View
    {
        $input = validator($request->query(), ['status' => ['nullable', Rule::in(['all', 'submitted', 'under_review', 'revision', 'rejected', 'approved'])], 'search' => ['nullable', 'string', 'max:120']])->validate();
        $status = $input['status'] ?? FinancialReport::STATUS_SUBMITTED;
        $search = trim($input['search'] ?? '');
        $reports = FinancialReport::query()->with(['program.organization', 'program.creator'])->whereNot('status', FinancialReport::STATUS_DRAFT)
            ->when($status !== 'all', fn ($query) => $query->where('status', $status))
            ->when($search !== '', fn ($query) => $query->whereHas('program', fn ($program) => $program->where('title', 'like', "%{$search}%")->orWhereHas('organization', fn ($organization) => $organization->where('name', 'like', "%{$search}%"))))
            ->orderByDesc('submitted_at')->paginate(15)->withQueryString();

        return view('verifier.financial-reports.index', compact('reports', 'status', 'search'));
    }

    public function show(FinancialReport $report, FinancialReportTotalsService $totals): View
    {
        Gate::authorize('view', $report);
        $report->load(['program.organization', 'program.creator', 'program.category', 'program.latestProposal', 'program.activities.category', 'program.logbooks', 'items', 'reviewer']);

        return view('verifier.financial-reports.show', ['report' => $report, 'summary' => $totals->summarize($report), 'money' => $totals]);
    }

    public function review(ReviewFinancialReportRequest $request, FinancialReport $report, ReviewFinancialReportAction $action): RedirectResponse
    {
        $action->execute($request->user(), $report, $request->validated('decision'), $request->validated('notes'));

        return back()->with('status', 'Keputusan review E-LPJ disimpan.');
    }

    public function receipt(FinancialReport $report, FinancialItem $item): StreamedResponse
    {
        abort_unless($item->financial_report_id === $report->getKey(), 404);
        Gate::authorize('view', $report);
        abort_if($item->receipt_path === null || ! Storage::disk('financial_receipts')->exists($item->receipt_path), 404);
        $mime = Storage::disk('financial_receipts')->mimeType($item->receipt_path) ?: 'application/octet-stream';

        return response()->stream(function () use ($item): void {
            $stream = Storage::disk('financial_receipts')->readStream($item->receipt_path);
            abort_unless(is_resource($stream), 404);
            fpassthru($stream);
            fclose($stream);
        }, 200, ['Content-Type' => $mime, 'Content-Disposition' => 'inline; filename="bukti-transaksi.'.pathinfo($item->receipt_path, PATHINFO_EXTENSION).'"', 'Cache-Control' => 'private, no-store, max-age=0', 'X-Content-Type-Options' => 'nosniff']);
    }
}
