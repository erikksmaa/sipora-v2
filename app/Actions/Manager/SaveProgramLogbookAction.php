<?php

namespace App\Actions\Manager;

use App\Models\Activity;
use App\Models\Program;
use App\Models\ProgramLogbook;
use App\Models\User;
use App\Support\BinaryUuid;
use Illuminate\Validation\ValidationException;

final class SaveProgramLogbookAction
{
    public function execute(User $actor, Program $program, array $data, ?ProgramLogbook $logbook = null): ProgramLogbook
    {
        $logbook ??= new ProgramLogbook;
        $activityId = filled($data['activity_id'] ?? null) ? BinaryUuid::bytes($data['activity_id']) : null;
        if ($activityId && ! Activity::query()->whereKey($activityId)->where('program_id', $program->getKey())->exists()) {
            throw ValidationException::withMessages(['activity_id' => ['Activity harus terhubung dengan Program ini.']]);
        }
        $values = ['log_date' => $data['log_date'], 'summary' => $data['summary'], 'obstacles' => $data['obstacles'] ?? null,
            'solutions' => $data['solutions'] ?? null, 'progress_percent' => $data['progress_percent'],
            'activity_id' => $activityId];
        if (! $logbook->exists) {
            $values += ['program_id' => $program->getKey(), 'created_by_user_id' => $actor->getKey(), 'status' => ProgramLogbook::STATUS_DRAFT];
        }
        $logbook->fill($values)->save();
        $event = $logbook->wasRecentlyCreated ? 'program_logbook_created' : 'program_logbook_updated';
        activity()->causedBy($actor)->performedOn($logbook)->event($event)->withProperties(['logbook_id' => $logbook->uuid(), 'program_id' => $program->uuid()])->log('Logbook Program disimpan');

        return $logbook->fresh(['activity', 'creator', 'media']);
    }
}
