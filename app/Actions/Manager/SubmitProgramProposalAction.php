<?php

namespace App\Actions\Manager;

use App\Models\Organization;
use App\Models\Program;
use App\Models\ProgramProposal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

final class SubmitProgramProposalAction
{
    public function execute(User $actor, ProgramProposal $proposal): ProgramProposal
    {
        return DB::transaction(function () use ($actor, $proposal): ProgramProposal {
            $proposal = ProgramProposal::query()->whereKey($proposal->getKey())->lockForUpdate()->firstOrFail();
            $program = Program::query()->whereKey($proposal->program_id)->lockForUpdate()->firstOrFail();
            $errors = [];
            if ($proposal->status !== ProgramProposal::STATUS_DRAFT || $program->latestProposal()->value('id') !== $proposal->getKey()) {
                $errors['proposal'] = ['Hanya draft Proposal terbaru yang dapat diajukan.'];
            }
            if ($program->execution_status !== Program::STATUS_PLANNED) {
                $errors['program'] = ['Program harus berstatus planned.'];
            }
            if ($program->organization->review_status !== Organization::REVIEW_APPROVED || $program->organization->operational_status !== Organization::OPERATIONAL_ACTIVE) {
                $errors['organization'] = ['Komunitas harus terverifikasi dan aktif.'];
            }
            if (! $program->activities()->exists()) {
                $errors['activities'] = ['Hubungkan minimal satu Activity sebelum mengajukan Proposal.'];
            }
            if (! $proposal->proposal_document_path || ! Storage::disk('proposal_documents')->exists($proposal->proposal_document_path)) {
                $errors['proposal_document'] = ['Dokumen Proposal wajib diunggah.'];
            }
            if ($errors) {
                throw ValidationException::withMessages($errors);
            }

            $proposal->forceFill(['status' => ProgramProposal::STATUS_SUBMITTED, 'submitted_at' => now()])->save();
            $event = $proposal->version > 1 ? 'program_proposal_resubmitted' : 'program_proposal_submitted';
            activity()->causedBy($actor)->performedOn($proposal)->event($event)
                ->withProperties(['proposal_id' => $proposal->uuid(), 'program_id' => $program->uuid(), 'version' => $proposal->version])
                ->log('Proposal Program diajukan untuk verifikasi');

            return $proposal->fresh();
        });
    }
}
