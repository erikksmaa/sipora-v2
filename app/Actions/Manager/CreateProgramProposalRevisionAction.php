<?php

namespace App\Actions\Manager;

use App\Models\ProgramProposal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

final class CreateProgramProposalRevisionAction
{
    public function execute(User $actor, ProgramProposal $source): ProgramProposal
    {
        $newPath = null;
        try {
            return DB::transaction(function () use ($actor, $source, &$newPath): ProgramProposal {
                $source = ProgramProposal::query()->whereKey($source->getKey())->lockForUpdate()->firstOrFail();
                if ($source->status !== ProgramProposal::STATUS_REVISION || $source->program->latestProposal()->value('id') !== $source->getKey()) {
                    throw ValidationException::withMessages(['proposal' => ['Proposal ini tidak dapat direvisi.']]);
                }

                if ($source->proposal_document_path && Storage::disk('proposal_documents')->exists($source->proposal_document_path)) {
                    $extension = pathinfo($source->proposal_document_path, PATHINFO_EXTENSION) ?: 'pdf';
                    $newPath = 'proposals/'.Str::uuid().'.'.$extension;
                    Storage::disk('proposal_documents')->copy($source->proposal_document_path, $newPath);
                }
                $proposal = ProgramProposal::create([
                    'program_id' => $source->program_id,
                    'version' => $source->version + 1,
                    'status' => ProgramProposal::STATUS_DRAFT,
                    'requested_budget' => $source->requested_budget,
                    'proposal_document_path' => $newPath,
                ]);
                activity()->causedBy($actor)->performedOn($proposal)->event('program_proposal_created')
                    ->withProperties(['proposal_id' => $proposal->uuid(), 'program_id' => $source->program->uuid(), 'version' => $proposal->version, 'from_version' => $source->version])
                    ->log('Versi revisi Proposal Program dibuat');

                return $proposal;
            });
        } catch (\Throwable $exception) {
            if ($newPath) {
                Storage::disk('proposal_documents')->delete($newPath);
            }
            throw $exception;
        }
    }
}
