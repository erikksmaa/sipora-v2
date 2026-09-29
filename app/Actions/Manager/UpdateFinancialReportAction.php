<?php

namespace App\Actions\Manager;

use App\Models\FinancialReport;
use App\Models\User;

final class UpdateFinancialReportAction
{
    public function execute(User $actor, FinancialReport $report, array $data): FinancialReport
    {
        $report->fill(['notes' => $data['notes'] ?? null])->save();
        activity()->causedBy($actor)->performedOn($report)->event('financial_report_updated')->withProperties(['financial_report_id' => $report->uuid(), 'program_id' => $report->program->uuid(), 'version' => $report->version])->log('Draft E-LPJ diperbarui');

        return $report;
    }
}
