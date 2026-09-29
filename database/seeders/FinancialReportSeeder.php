<?php

namespace Database\Seeders;

use App\Models\FinancialItem;
use App\Models\FinancialReport;
use App\Models\Program;
use App\Models\ProgramLogbook;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

final class FinancialReportSeeder extends Seeder
{
    public function run(): void
    {
        $manager = User::query()->where('email', 'youth1@sipora.test')->firstOrFail();
        $verifier = User::query()->where('email', 'verifier@sipora.test')->firstOrFail();

        $draftProgram = Program::query()->where('slug', 'program-pemuda-digital-2026')->firstOrFail();
        $this->report($draftProgram, FinancialReport::STATUS_DRAFT, [['expense', 'Honor fasilitator', '12500000.00', true], ['expense', 'Sewa perangkat pelatihan', '8750000.50', false], ['income', 'Dukungan mitra', '2500000.00', false]], $manager);

        $submittedProgram = Program::query()->where('slug', 'program-literasi-teknologi')->firstOrFail();
        $submittedProgram->forceFill(['execution_status' => Program::STATUS_RUNNING])->save();
        $this->approvedLogbook($submittedProgram, $manager, $verifier, 60);
        $this->report($submittedProgram, FinancialReport::STATUS_SUBMITTED, [['expense', 'Perlengkapan lokakarya', '16500000.00', true]], $manager);

        $revisionProgram = Program::query()->where('slug', 'program-kolaborasi-digital')->firstOrFail();
        $this->approvedLogbook($revisionProgram, $manager, $verifier, 45);
        $this->report($revisionProgram, FinancialReport::STATUS_REVISION, [['expense', 'Produksi materi kolaborasi', '13000000.00', true]], $manager, $verifier, 'Sesuaikan bukti dan jelaskan realisasi yang melampaui anggaran.');
    }

    private function report(Program $program, string $status, array $items, User $manager, ?User $verifier = null, ?string $reviewNotes = null): void
    {
        $report = FinancialReport::withTrashed()->updateOrCreate(['program_id' => $program->getKey(), 'version' => 1], ['status' => $status, 'notes' => 'Data demonstrasi E-LPJ untuk lingkungan pengembangan.', 'submitted_at' => $status === FinancialReport::STATUS_DRAFT ? null : now()->subDays(2), 'reviewed_at' => $verifier ? now()->subDay() : null, 'reviewed_by' => $verifier?->getKey(), 'review_notes' => $reviewNotes, 'deleted_at' => null]);
        foreach ($items as $index => [$type, $description, $amount, $hasReceipt]) {
            $path = null;
            if ($hasReceipt) {
                $path = 'reports/'.$report->uuid().'/development-'.$index.'.pdf';
                Storage::disk('financial_receipts')->put($path, "%PDF-1.4\n% SIPORA development financial evidence\n");
            }
            FinancialItem::withTrashed()->updateOrCreate(['financial_report_id' => $report->getKey(), 'description' => $description], ['transaction_type' => $type, 'transaction_date' => now()->subDays(5 - $index)->toDateString(), 'amount' => $amount, 'receipt_path' => $path, 'deleted_at' => null]);
        }
    }

    private function approvedLogbook(Program $program, User $manager, User $verifier, int $progress): void
    {
        ProgramLogbook::withTrashed()->updateOrCreate(['program_id' => $program->getKey(), 'log_date' => now()->subWeek()->toDateString()], ['activity_id' => null, 'created_by_user_id' => $manager->getKey(), 'summary' => 'Pelaksanaan Program berjalan sesuai rencana.', 'progress_percent' => $progress, 'status' => ProgramLogbook::STATUS_APPROVED, 'submitted_at' => now()->subDays(6), 'reviewed_at' => now()->subDays(5), 'reviewed_by' => $verifier->getKey(), 'deleted_at' => null]);
    }
}
