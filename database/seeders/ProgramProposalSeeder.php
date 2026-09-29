<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\ProgramProposal;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProgramProposalSeeder extends Seeder
{
    public function run(): void
    {
        $verifier = User::query()->where('email', 'verifier@sipora.test')->firstOrFail();
        $records = [
            ['program' => 'program-pemuda-digital-2026', 'status' => ProgramProposal::STATUS_APPROVED, 'budget' => 75000000, 'reviewed' => true, 'notes' => 'Proposal memenuhi persyaratan.'],
            ['program' => 'program-olahraga-komunitas', 'status' => ProgramProposal::STATUS_SUBMITTED, 'budget' => 40000000, 'reviewed' => false, 'notes' => null],
            ['program' => 'program-kreativitas-pemuda', 'status' => ProgramProposal::STATUS_REVISION, 'budget' => 25000000, 'reviewed' => true, 'notes' => 'Perjelas rincian jadwal dan keluaran Activity.'],
            ['program' => 'program-kepemimpinan-muda', 'status' => ProgramProposal::STATUS_DRAFT, 'budget' => null, 'reviewed' => false, 'notes' => null],
        ];

        foreach ($records as $record) {
            $program = Program::query()->where('slug', $record['program'])->firstOrFail();
            $path = 'proposals/development-'.$record['program'].'.pdf';
            Storage::disk('proposal_documents')->put($path, "%PDF-1.4\n% SIPORA development fixture only\n");
            $proposal = ProgramProposal::withTrashed()->updateOrCreate(['program_id' => $program->getKey(), 'version' => 1], [
                'status' => $record['status'], 'requested_budget' => $record['budget'], 'proposal_document_path' => $path,
                'submitted_at' => $record['status'] === ProgramProposal::STATUS_DRAFT ? null : now()->subDays(5),
                'reviewed_at' => $record['reviewed'] ? now()->subDays(2) : null,
                'reviewed_by' => $record['reviewed'] ? $verifier->getKey() : null,
                'review_notes' => $record['notes'], 'deleted_at' => null,
            ]);
            if ($record['status'] !== ProgramProposal::STATUS_DRAFT && ! $proposal->submissionActivity()->exists()) {
                activity()->causedBy($program->creator)->performedOn($proposal)->event('program_proposal_submitted')
                    ->withProperties(['proposal_id' => $proposal->uuid(), 'program_id' => $program->uuid(), 'version' => 1, 'development_fixture' => true])
                    ->log('Proposal Program diajukan untuk verifikasi');
            }
        }
    }
}
