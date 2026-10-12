<?php

namespace Database\Seeders;

use App\Models\AuditEntry;
use App\Models\Program;
use App\Models\ProgramProposal;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

final class DemoProposalStatusSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local') || DB::connection()->getDatabaseName() !== 'sipora') {
            throw new RuntimeException('Proposal status demo hanya untuk database local sipora.');
        }

        $root = base_path('output/pdf/07A_PROGRAM_PROPOSALS');
        $manifest = json_decode(file_get_contents($root.'/manifest.json'), true, flags: JSON_THROW_ON_ERROR);
        $scenarios = [
            'P001' => [ProgramProposal::STATUS_APPROVED, 'Proposal program demo disetujui.', '2026-08-05 09:00:00', '2026-08-10 10:00:00'],
            'P002' => [ProgramProposal::STATUS_REVISION, 'Mohon lengkapi indikator keberhasilan program dan rincian rencana anggaran kegiatan.', '2026-10-01 09:00:00', '2026-10-05 10:00:00'],
            'P003' => [ProgramProposal::STATUS_SUBMITTED, null, '2026-10-08 09:00:00', null],
            'P004' => [ProgramProposal::STATUS_REJECTED, 'Proposal belum memenuhi kesesuaian sasaran dan ruang lingkup program kepemudaan.', '2026-10-01 11:00:00', '2026-10-07 10:00:00'],
            'P005' => [ProgramProposal::STATUS_DRAFT, null, null, null],
        ];
        $verifier = User::where('email', 'verifier@sipora.test')->firstOrFail();
        $records = [];
        foreach ($manifest['programs'] as $record) {
            if (! isset($scenarios[$record['id']])) {
                continue;
            }
            $program = Program::where('slug', $record['slug'])->firstOrFail();
            $proposal = $program->latestProposal()->firstOrFail();
            $source = $root.'/'.$record['file'];
            if (! is_file($source) || ! str_starts_with(file_get_contents($source), '%PDF-')) {
                throw new RuntimeException('PDF proposal demo belum tersedia: '.$record['file']);
            }
            $records[] = [$record, $program, $proposal, $source];
        }
        if (count($records) !== 5) {
            throw new RuntimeException('Paket demo harus memuat tepat P001-P005.');
        }

        // Use dedicated demo paths, preserving existing proposal files and versions.
        foreach ($records as [$record, $program, $proposal, $source]) {
            Storage::disk('proposal_documents')->put('proposals/batch-7a/'.$record['file'], file_get_contents($source));
        }
        DB::transaction(function () use ($records, $scenarios, $verifier): void {
            foreach ($records as [$record, $program, $proposal]) {
                [$status, $notes, $submittedAt, $reviewedAt] = $scenarios[$record['id']];
                $proposal->forceFill([
                    'status' => $status,
                    'requested_budget' => $record['requested_budget'],
                    'proposal_document_path' => 'proposals/batch-7a/'.$record['file'],
                    'submitted_at' => $submittedAt,
                    'reviewed_at' => $reviewedAt,
                    'reviewed_by' => $reviewedAt ? $verifier->getKey() : null,
                    'review_notes' => $notes,
                ])->save();
                // Record fixture provenance without sending real review notifications.
                if ($status !== ProgramProposal::STATUS_DRAFT) {
                    $event = 'program_proposal_submitted';
                    if (! $proposal->submissionActivity()->exists()) {
                        activity()->causedBy($program->creator)->performedOn($proposal)->event($event)
                            ->withProperties(['demo_batch' => '7B', 'fictional' => true])
                            ->log('Pengajuan proposal untuk skenario demo SIPORA');
                    }
                }
                if (! AuditEntry::where('subject_type', $proposal->getMorphClass())->where('subject_id', $proposal->getKey())
                    ->where('event', 'demo_proposal_status_seeded')
                    ->where('properties->demo_batch', '7B')->where('properties->status', $status)->exists()) {
                    activity()->causedBy($verifier)->performedOn($proposal)->event('demo_proposal_status_seeded')
                        ->withProperties(['demo_batch' => '7B', 'fictional' => true, 'status' => $status, 'notes' => $notes])
                        ->log('Variasi status proposal demo SIPORA');
                }
            }
        });
        $this->command?->info('P001 approved / P002 revision / P003 submitted / P004 rejected / P005 draft.');
    }
}
