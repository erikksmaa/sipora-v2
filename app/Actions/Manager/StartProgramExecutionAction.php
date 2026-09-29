<?php

namespace App\Actions\Manager;

use App\Models\Program;
use App\Models\ProgramProposal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class StartProgramExecutionAction
{
    public function execute(User $actor, Program $program): Program
    {
        return DB::transaction(function () use ($actor, $program): Program {
            $program = Program::query()->whereKey($program->getKey())->lockForUpdate()->firstOrFail();
            if ($program->execution_status !== Program::STATUS_PLANNED) {
                throw ValidationException::withMessages(['program' => ['Program tidak lagi dapat dimulai.']]);
            }
            if ($program->latestProposal()->value('status') !== ProgramProposal::STATUS_APPROVED) {
                throw ValidationException::withMessages(['proposal' => ['Proposal terbaru harus disetujui.']]);
            }
            if (! $program->activities()->exists()) {
                throw ValidationException::withMessages(['activities' => ['Program harus memiliki minimal satu Activity.']]);
            }
            $program->forceFill(['execution_status' => Program::STATUS_RUNNING])->save();
            activity()->causedBy($actor)->performedOn($program)->event('program_execution_started')->withProperties(['program_id' => $program->uuid()])->log('Eksekusi Program dimulai');

            return $program;
        });
    }
}
