<?php

namespace App\Actions\Manager;

use App\Models\Program;
use App\Models\ProgramProposal;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class SaveProgramProposalAction
{
    public function execute(User $actor, Program $program, array $data, ?UploadedFile $document = null, ?ProgramProposal $proposal = null): ProgramProposal
    {
        $newPath = $document?->storeAs('proposals', Str::uuid().'.'.$document->extension(), 'proposal_documents');
        $oldPath = null;

        try {
            $saved = DB::transaction(function () use ($actor, $program, $data, $newPath, $proposal, &$oldPath): ProgramProposal {
                if ($proposal === null) {
                    $program = Program::query()->whereKey($program->getKey())->lockForUpdate()->firstOrFail();
                    $proposal = ProgramProposal::create([
                        'program_id' => $program->getKey(),
                        'version' => ((int) $program->proposals()->withTrashed()->max('version')) + 1,
                        'status' => ProgramProposal::STATUS_DRAFT,
                        'requested_budget' => $data['requested_budget'] ?? null,
                        'proposal_document_path' => $newPath,
                    ]);
                    $event = 'program_proposal_created';
                } else {
                    $proposal = ProgramProposal::query()->whereKey($proposal->getKey())->lockForUpdate()->firstOrFail();
                    abort_unless($proposal->status === ProgramProposal::STATUS_DRAFT, 409);
                    $oldPath = $proposal->proposal_document_path;
                    $proposal->forceFill([
                        'requested_budget' => $data['requested_budget'] ?? null,
                        'proposal_document_path' => $newPath ?? $oldPath,
                    ])->save();
                    $event = 'program_proposal_updated';
                }

                activity()->causedBy($actor)->performedOn($proposal)->event($event)
                    ->withProperties(['proposal_id' => $proposal->uuid(), 'program_id' => $program->uuid(), 'version' => $proposal->version])
                    ->log('Draft Proposal Program disimpan');

                return $proposal->fresh();
            });

        } catch (\Throwable $exception) {
            if ($newPath) {
                Storage::disk('proposal_documents')->delete($newPath);
            }
            throw $exception;
        }

        if ($newPath && $oldPath) {
            try {
                Storage::disk('proposal_documents')->delete($oldPath);
            } catch (\Throwable $exception) {
                report($exception);
            }
        }

        return $saved;
    }
}
