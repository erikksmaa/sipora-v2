<?php

namespace App\Actions\Verifier;

use App\Models\FinancialReport;
use App\Models\OrganizationMembership;
use App\Models\User;
use App\Notifications\FinancialReportReviewed;
use App\Services\FinancialReportTotalsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class ReviewFinancialReportAction
{
    public function __construct(private readonly FinancialReportTotalsService $totals) {}

    public function execute(User $verifier, FinancialReport $report, string $decision, ?string $notes): FinancialReport
    {
        return DB::transaction(function () use ($verifier, $report, $decision, $notes): FinancialReport {
            $report = FinancialReport::query()->with(['items', 'program.creator', 'program.latestProposal'])->whereKey($report->getKey())->lockForUpdate()->firstOrFail();
            if ($report->status !== FinancialReport::STATUS_SUBMITTED) {
                throw ValidationException::withMessages(['decision' => ['E-LPJ tidak lagi menunggu review.']]);
            }
            if (in_array($decision, [FinancialReport::STATUS_REVISION, FinancialReport::STATUS_REJECTED], true) && blank($notes)) {
                throw ValidationException::withMessages(['notes' => ['Catatan wajib diisi untuk revisi atau penolakan.']]);
            }
            $conflict = OrganizationMembership::query()->where('organization_id', $report->program->organization_id)->where('user_id', $verifier->getKey())
                ->where('membership_status', OrganizationMembership::STATUS_ACTIVE)->whereIn('access_role', [OrganizationMembership::ROLE_LEADER, OrganizationMembership::ROLE_MANAGER])->exists();
            if ($conflict) {
                throw ValidationException::withMessages(['decision' => ['Verifier tidak boleh meninjau E-LPJ komunitas yang dikelolanya.']]);
            }

            $report->forceFill(['status' => $decision, 'reviewed_at' => now(), 'reviewed_by' => $verifier->getKey(), 'review_notes' => filled($notes) ? trim($notes) : null])->save();
            $event = match ($decision) {
                FinancialReport::STATUS_APPROVED => 'financial_report_approved',
                FinancialReport::STATUS_REJECTED => 'financial_report_rejected',
                default => 'financial_report_revision_requested',
            };
            $summary = $this->totals->summarize($report);
            activity()->causedBy($verifier)->performedOn($report)->event($event)->withProperties(['financial_report_id' => $report->uuid(), 'program_id' => $report->program->uuid(), 'decision' => $decision, 'realization_total' => $summary['realization_total']])->log('Keputusan review E-LPJ');
            $report->program->creator->notify(new FinancialReportReviewed($report));

            return $report;
        });
    }
}
