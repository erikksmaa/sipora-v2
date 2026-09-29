<?php

namespace App\Actions\Manager;

use App\Models\ProgramLogbook;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class SubmitProgramLogbookAction
{
    public function execute(User $actor, ProgramLogbook $logbook): ProgramLogbook
    {
        return DB::transaction(function () use ($actor, $logbook): ProgramLogbook {
            $logbook = ProgramLogbook::query()->whereKey($logbook->getKey())->lockForUpdate()->firstOrFail();
            if (! in_array($logbook->status, [ProgramLogbook::STATUS_DRAFT, ProgramLogbook::STATUS_REVISION], true)) {
                throw ValidationException::withMessages(['logbook' => ['Logbook tidak dapat diajukan.']]);
            }
            $logbook->forceFill(['status' => ProgramLogbook::STATUS_SUBMITTED, 'submitted_at' => now(), 'reviewed_at' => null, 'reviewed_by' => null])->save();
            activity()->causedBy($actor)->performedOn($logbook)->event('program_logbook_submitted')->withProperties(['logbook_id' => $logbook->uuid(), 'program_id' => $logbook->program->uuid()])->log('Logbook Program diajukan');

            return $logbook;
        });
    }
}
