<?php

namespace App\Actions\Manager;

use App\Models\FinancialReport;
use App\Models\Program;
use App\Models\ProgramLogbook;
use App\Models\ProgramProposal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class CreateFinancialReportAction
{
    public function execute(User $actor, Program $program, array $data): FinancialReport
    {
        return DB::transaction(function () use ($actor, $program, $data): FinancialReport {
            $program = Program::query()->whereKey($program->getKey())->lockForUpdate()->firstOrFail();
            $eligible = $program->execution_status === Program::STATUS_RUNNING
                && $program->latestProposal()->value('status') === ProgramProposal::STATUS_APPROVED
                && $program->logbooks()->where('status', ProgramLogbook::STATUS_APPROVED)->exists()
                && ! $program->financialReports()->exists();
            if (! $eligible) {
                throw ValidationException::withMessages(['report' => ['Program belum memenuhi syarat untuk membuat E-LPJ.']]);
            }

            $report = FinancialReport::create(['program_id' => $program->getKey(), 'version' => 1, 'status' => FinancialReport::STATUS_DRAFT, 'notes' => $data['notes'] ?? null]);
            activity()->causedBy($actor)->performedOn($report)->event('financial_report_created')->withProperties(['financial_report_id' => $report->uuid(), 'program_id' => $program->uuid(), 'version' => 1])->log('Draft E-LPJ dibuat');

            return $report;
        });
    }
}
