<?php

namespace App\Actions\Manager;

use App\Models\ProgramLogbook;
use App\Models\User;

final class DeleteProgramLogbookAction
{
    public function execute(User $actor, ProgramLogbook $logbook): void
    {
        $logbook->delete();
        activity()->causedBy($actor)->performedOn($logbook)->event('program_logbook_deleted')->withProperties(['logbook_id' => $logbook->uuid(), 'program_id' => $logbook->program->uuid()])->log('Logbook Program diarsipkan');
    }
}
