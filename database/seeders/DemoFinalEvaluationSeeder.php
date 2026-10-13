<?php

namespace Database\Seeders;

use App\Actions\Manager\CreateFinancialReportAction;
use App\Actions\Manager\SubmitFinancialReportAction;
use App\Actions\Verifier\FinalizeProgramEvaluationAction;
use App\Actions\Verifier\ReviewFinancialReportAction;
use App\Actions\Verifier\ReviewProgramLogbookAction;
use App\Actions\Verifier\ReviewProgramProposalAction;
use App\Models\FinancialItem;
use App\Models\FinancialReport;
use App\Models\Program;
use App\Models\ProgramEvaluation;
use App\Models\ProgramLogbook;
use App\Models\ProgramLogbookMedia;
use App\Models\ProgramProposal;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

final class DemoFinalEvaluationSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local') || DB::connection()->getDatabaseName() !== 'sipora') {
            throw new RuntimeException('Final evaluation demo hanya untuk database local sipora.');
        }
        // Exercise native actions while preventing delivery of any real notifications.
        Notification::fake();
        $verifier = User::where('email', 'verifier@sipora.test')->firstOrFail();
        $p1 = Program::where('slug', 'pemalang-youth-digital-empowerment-2026')->firstOrFail();
        $p2 = Program::where('slug', 'creative-youth-development-program')->firstOrFail();
        $evidence = json_decode(file_get_contents(public_path('media/demo/07F_SUPPORTING_EVIDENCE/manifest.json')), true, flags: JSON_THROW_ON_ERROR);
        foreach ($evidence['evidence'] as $item) {
            if (! Storage::disk($item['app_disk'])->exists($item['app_file_path'])) {
                Storage::disk($item['app_disk'])->put($item['app_file_path'], file_get_contents(base_path($item['source'])));
            }
        }
        DB::transaction(function () use ($p1, $p2, $verifier, $evidence): void {
            $closing = $p1->logbooks()->whereDate('log_date', '2026-10-10')->firstOrFail();
            $last = $evidence['evidence'][3];
            ProgramLogbookMedia::withTrashed()->updateOrCreate([
                'logbook_id' => $closing->getKey(), 'file_path' => $last['app_file_path'],
            ], ['uploaded_by' => $p1->created_by_user_id, 'caption' => 'A005 / Seminar Kewirausahaan Digital - supporting evidence demo', 'deleted_at' => null]);
            foreach ($p1->logbooks()->where('status', ProgramLogbook::STATUS_SUBMITTED)->get() as $logbook) {
                app(ReviewProgramLogbookAction::class)->execute($verifier, $logbook, ProgramLogbook::STATUS_APPROVED, 'Logbook fiktif Batch 7G ditinjau untuk demonstrasi workflow.');
            }
            $report = $p1->latestFinancialReport()->firstOrFail();
            if ($report->status === FinancialReport::STATUS_DRAFT) {
                $report = app(SubmitFinancialReportAction::class)->execute($p1->creator, $report);
            }
            if ($report->status === FinancialReport::STATUS_SUBMITTED) {
                $report = app(ReviewFinancialReportAction::class)->execute($verifier, $report, FinancialReport::STATUS_APPROVED, 'E-LPJ demo diterima untuk skenario finalisasi; definisi selesai minimal satu kegiatan tetap dibedakan dari kriteria proposal.');
            }
            $report->forceFill(['notes' => str_replace('Draft menunggu prasyarat Logbook Approved sebelum pengajuan. Tidak ada persetujuan E-LPJ atau evaluasi akhir.', 'BATCH 7G: E-LPJ melewati Submitted dan Approved; final evaluation demo Approved. Catatan kelengkapan data pada PDF tetap berlaku.', $report->notes ?? '')])->save();
            $latestEvaluation = $p1->latestEvaluation()->first();
            if ($latestEvaluation?->decision !== ProgramEvaluation::DECISION_APPROVED) {
                app(FinalizeProgramEvaluationAction::class)->execute(
                    $verifier, $p1, ProgramEvaluation::DECISION_APPROVED,
                    'DATA DEMO SIPORA. Skenario workflow disetujui dan Program Completed. Capaian 56 memakai minimal satu kegiatan; kriteria penyelesaian proposal dan rincian data yang belum tersedia tidak dinyatakan telah terverifikasi.',
                    $latestEvaluation?->uuid()
                );
            } else {
                $p1->forceFill(['execution_status' => Program::STATUS_COMPLETED])->save();
            }

            $p2Report = $p2->latestFinancialReport()->first();
            if ($p2Report?->status === FinancialReport::STATUS_REVISION && str_contains($p2Report->notes ?? '', 'BATCH 7G')) {
                return;
            }
            if ($p2Report) {
                throw new RuntimeException('E-LPJ P002 yang ada bukan skenario Batch 7G; tidak ditimpa.');
            }
            // Preserve latest Revision by moving it to v2, with approved v1 as history.
            $proposal = $p2->latestProposal()->firstOrFail();
            if ($proposal->version !== 1 || $proposal->status !== ProgramProposal::STATUS_REVISION) {
                throw new RuntimeException('P002 harus memakai proposal Revision v1 sebelum menyiapkan riwayat demo.');
            }
            $proposal->forceFill(['status' => ProgramProposal::STATUS_APPROVED, 'reviewed_by' => $verifier->getKey(), 'reviewed_at' => now(), 'review_notes' => 'Versi awal proposal fiktif disetujui dalam riwayat Batch 7G.'])->save();
            $log = ProgramLogbook::withTrashed()->updateOrCreate([
                'program_id' => $p2->getKey(), 'log_date' => '2026-10-10',
            ], ['created_by_user_id' => $p2->created_by_user_id, 'activity_id' => null,
                'summary' => 'BATCH 7G - Rekap awal pelaksanaan demo P002. Dokumentasi dan rincian realisasi belum lengkap.',
                'progress_percent' => 60, 'status' => ProgramLogbook::STATUS_SUBMITTED,
                'submitted_at' => now(), 'reviewed_at' => null, 'reviewed_by' => null, 'review_notes' => null, 'deleted_at' => null]);
            app(ReviewProgramLogbookAction::class)->execute($verifier, $log, ProgramLogbook::STATUS_APPROVED, 'Catatan progres awal demo diterima; bukti E-LPJ memerlukan kelengkapan.');
            $p2Report = app(CreateFinancialReportAction::class)->execute($p2->creator, $p2, [
                'notes' => 'BATCH 7G - DATA DEMO SIPORA. Rekap sementara Rp3.600.000 dipilih sebagai nominal fiktif untuk skenario Revision; rincian per komponen dan dokumentasi belum lengkap.',
            ]);
            FinancialItem::create(['financial_report_id' => $p2Report->getKey(), 'transaction_type' => FinancialItem::TYPE_EXPENSE,
                'transaction_date' => '2026-10-10', 'description' => 'Rekap sementara pelaksanaan demo P002', 'amount' => 3600000, 'receipt_path' => null]);
            $p2Report = app(SubmitFinancialReportAction::class)->execute($p2->creator, $p2Report);
            app(ReviewFinancialReportAction::class)->execute($verifier, $p2Report, FinancialReport::STATUS_REVISION, 'Mohon lengkapi dokumentasi kegiatan dan rekapitulasi realisasi anggaran.');
            $v2 = ProgramProposal::create([
                'program_id' => $p2->getKey(), 'version' => 2, 'status' => ProgramProposal::STATUS_SUBMITTED,
                'requested_budget' => $proposal->requested_budget, 'proposal_document_path' => $proposal->proposal_document_path,
                'submitted_at' => now(),
            ]);
            app(ReviewProgramProposalAction::class)->execute($verifier, $v2, ProgramProposal::STATUS_REVISION, 'Mohon lengkapi indikator keberhasilan program dan rincian rencana anggaran kegiatan.');
        });
        $this->command?->info('P001 Completed / E-LPJ Approved / Final Evaluation Approved. Creative Youth Development Program: E-LPJ Revision / Proposal Revision v2.');
    }
}
