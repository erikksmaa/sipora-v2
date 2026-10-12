<?php

namespace Database\Seeders;

use App\Models\FinancialItem;
use App\Models\FinancialReport;
use App\Models\Program;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

final class DemoProgramFinancialReportSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local') || DB::connection()->getDatabaseName() !== 'sipora') {
            throw new RuntimeException('E-LPJ demo hanya untuk database local sipora.');
        }
        $root = base_path('output/pdf/07D_ELPJ');
        $manifest = json_decode(file_get_contents($root.'/manifest.json'), true, flags: JSON_THROW_ON_ERROR);
        $program = Program::where('slug', $manifest['program_slug'])->firstOrFail();
        $report = $program->financialReports()->orderByDesc('version')->first();
        if ($report?->status === FinancialReport::STATUS_APPROVED
            && str_contains($report->notes ?? '', 'BATCH 7G')) {
            Storage::disk('private')->put('demo-reports/P001/program-001-elpj.pdf', file_get_contents($root.'/'.$manifest['pdf']));
            return;
        }
        if ($report && ($report->status !== FinancialReport::STATUS_DRAFT || ! str_contains($report->notes ?? '', 'BATCH 7D'))) {
            throw new RuntimeException('E-LPJ yang ada bukan draft Batch 7D; data tidak diubah.');
        }
        if (array_sum(array_column($manifest['budget_items'], 'actual')) !== 6600000
            || (int) $program->latestProposal()->value('requested_budget') !== 7000000) {
            throw new RuntimeException('RAB dan realisasi tidak sesuai golden demo.');
        }
        $pdf = $root.'/'.$manifest['pdf'];
        if (! is_file($pdf)) {
            throw new RuntimeException('PDF naratif E-LPJ belum tersedia.');
        }
        // Narrative PDF is not an expense receipt; preserve that distinction.
        Storage::disk('private')->put('demo-reports/P001/program-001-elpj.pdf', file_get_contents($pdf));
        DB::transaction(function () use ($program, $report, $manifest): void {
            $notes = "BATCH 7D - DATA DEMO SIPORA / BUKAN DOKUMEN RESMI.\n"
                ."Program P001: Pemalang Youth Digital Empowerment 2026.\n"
                ."Target: 60; terdaftar: 68; aktif: 61; selesai minimal satu kegiatan: 56; mengikuti lebih dari satu kegiatan: 42.\n"
                ."Definisi selesai laporan berbeda dari proposal. Capaian minimal tiga Activity dan satu karya belum dinilai.\n"
                ."Rencana Rp7.000.000; realisasi Rp6.600.000; sisa alokasi Rp400.000.\n"
                ."Tanggal item 10 Oktober 2026 adalah tanggal konsolidasi rekap demo, bukan bukti tanggal transaksi individual. Pemasukan dan kuitansi belum diberikan.\n"
                ."PDF naratif tersedia pada paket Batch 7D; tidak dipasang sebagai kuitansi.\n"
                ."Draft menunggu prasyarat Logbook Approved sebelum pengajuan. Tidak ada persetujuan E-LPJ atau evaluasi akhir.";
            $report ??= FinancialReport::create(['program_id' => $program->getKey(), 'version' => 1, 'status' => FinancialReport::STATUS_DRAFT]);
            $report->forceFill(['notes' => $notes, 'submitted_at' => null, 'reviewed_at' => null, 'reviewed_by' => null, 'review_notes' => null])->save();
            foreach ($manifest['budget_items'] as $item) {
                FinancialItem::withTrashed()->updateOrCreate([
                    'financial_report_id' => $report->getKey(),
                    'description' => 'Rekap demo: '.$item['category'],
                ], [
                    'transaction_type' => FinancialItem::TYPE_EXPENSE,
                    'transaction_date' => '2026-10-10',
                    'amount' => $item['actual'],
                    'receipt_path' => null, 'deleted_at' => null,
                ]);
            }
        });
        $this->command?->info('P001 E-LPJ draft: 5 rekap belanja, realisasi Rp6.600.000, selisih Rp400.000.');
    }
}
