<?php

namespace App\Http\Controllers\Manager;

use App\Actions\Manager\CreateFinancialReportAction;
use App\Actions\Manager\CreateFinancialReportRevisionAction;
use App\Actions\Manager\DeleteFinancialItemAction;
use App\Actions\Manager\SaveFinancialItemAction;
use App\Actions\Manager\SubmitFinancialReportAction;
use App\Actions\Manager\UpdateFinancialReportAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\SaveFinancialItemRequest;
use App\Http\Requests\Manager\SaveFinancialReportRequest;
use App\Models\FinancialItem;
use App\Models\FinancialReport;
use App\Models\Organization;
use App\Models\Program;
use App\Services\Community\ManagedCommunityService;
use App\Services\FinancialReportTotalsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class FinancialReportController extends Controller
{
    public function create(Request $request, Organization $organization, Program $program, ManagedCommunityService $managed): View
    {
        $this->program($organization, $program);
        Gate::authorize('create', [FinancialReport::class, $program]);

        return view('manager.financial-reports.form', ['organization' => $organization, 'program' => $program, 'report' => new FinancialReport, 'managedCommunities' => $managed->forUser($request->user())]);
    }

    public function store(SaveFinancialReportRequest $request, Organization $organization, Program $program, CreateFinancialReportAction $action): RedirectResponse
    {
        $this->program($organization, $program);
        $report = $action->execute($request->user(), $program, $request->validated());

        return to_route('manager.financial-reports.show', [$organization, $program, $report])->with('status', 'Draft E-LPJ dibuat.');
    }

    public function show(Request $request, Organization $organization, Program $program, FinancialReport $report, ManagedCommunityService $managed, FinancialReportTotalsService $totals): View
    {
        $this->report($organization, $program, $report);
        Gate::authorize('view', $report);
        $report->load(['items', 'reviewer', 'program.latestProposal']);

        return view('manager.financial-reports.show', ['organization' => $organization, 'program' => $program, 'report' => $report, 'summary' => $totals->summarize($report), 'money' => $totals, 'managedCommunities' => $managed->forUser($request->user())]);
    }

    public function edit(Request $request, Organization $organization, Program $program, FinancialReport $report, ManagedCommunityService $managed): View
    {
        $this->report($organization, $program, $report);
        Gate::authorize('update', $report);

        return view('manager.financial-reports.form', ['organization' => $organization, 'program' => $program, 'report' => $report, 'managedCommunities' => $managed->forUser($request->user())]);
    }

    public function update(SaveFinancialReportRequest $request, Organization $organization, Program $program, FinancialReport $report, UpdateFinancialReportAction $action): RedirectResponse
    {
        $this->report($organization, $program, $report);
        $action->execute($request->user(), $report, $request->validated());

        return to_route('manager.financial-reports.show', [$organization, $program, $report])->with('status', 'Catatan E-LPJ diperbarui.');
    }

    public function revise(Request $request, Organization $organization, Program $program, FinancialReport $report, CreateFinancialReportRevisionAction $action): RedirectResponse
    {
        $this->report($organization, $program, $report);
        Gate::authorize('revise', $report);
        $revision = $action->execute($request->user(), $report);

        return to_route('manager.financial-reports.show', [$organization, $program, $revision])->with('status', 'Versi revisi E-LPJ dibuat.');
    }

    public function submit(Request $request, Organization $organization, Program $program, FinancialReport $report, SubmitFinancialReportAction $action): RedirectResponse
    {
        $this->report($organization, $program, $report);
        Gate::authorize('submit', $report);
        $action->execute($request->user(), $report);

        return back()->with('status', 'E-LPJ diajukan kepada Verifier.');
    }

    public function storeItem(SaveFinancialItemRequest $request, Organization $organization, Program $program, FinancialReport $report, SaveFinancialItemAction $action): RedirectResponse
    {
        $this->report($organization, $program, $report);
        $action->execute($request->user(), $report, $request->validated(), $request->file('receipt'));

        return back()->with('status', 'Item keuangan ditambahkan.');
    }

    public function updateItem(SaveFinancialItemRequest $request, Organization $organization, Program $program, FinancialReport $report, FinancialItem $item, SaveFinancialItemAction $action): RedirectResponse
    {
        $this->item($organization, $program, $report, $item);
        $action->execute($request->user(), $report, $request->validated(), $request->file('receipt'), $item);

        return back()->with('status', 'Item keuangan diperbarui.');
    }

    public function destroyItem(Request $request, Organization $organization, Program $program, FinancialReport $report, FinancialItem $item, DeleteFinancialItemAction $action): RedirectResponse
    {
        $this->item($organization, $program, $report, $item);
        Gate::authorize('update', $report);
        $action->execute($request->user(), $item);

        return back()->with('status', 'Item keuangan dihapus.');
    }

    public function receipt(Organization $organization, Program $program, FinancialReport $report, FinancialItem $item): StreamedResponse
    {
        $this->item($organization, $program, $report, $item);
        Gate::authorize('view', $report);

        return $this->stream($item);
    }

    private function program(Organization $organization, Program $program): void
    {
        abort_unless($program->organization_id === $organization->getKey(), 404);
    }

    private function report(Organization $organization, Program $program, FinancialReport $report): void
    {
        $this->program($organization, $program);
        abort_unless($report->program_id === $program->getKey(), 404);
    }

    private function item(Organization $organization, Program $program, FinancialReport $report, FinancialItem $item): void
    {
        $this->report($organization, $program, $report);
        abort_unless($item->financial_report_id === $report->getKey(), 404);
    }

    private function stream(FinancialItem $item): StreamedResponse
    {
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
