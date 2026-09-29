<?php

namespace App\Actions\Manager;

use App\Models\ProgramLogbookMedia;
use App\Models\User;

final class RemoveProgramLogbookMediaAction
{
    public function execute(User $actor, ProgramLogbookMedia $media): void
    {
        $media->delete();
        activity()->causedBy($actor)->performedOn($media->logbook)->event('program_logbook_media_removed')->withProperties(['logbook_id' => $media->logbook->uuid(), 'media_id' => $media->uuid()])->log('Media Logbook diarsipkan');
    }
}
