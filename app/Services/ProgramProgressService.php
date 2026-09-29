<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Program;

final class ProgramProgressService
{
    public function summarize(Program $program): array
    {
        $activities = $program->relationLoaded('activities') ? $program->activities : $program->activities()->get();
        $logbooks = $program->relationLoaded('logbooks') ? $program->logbooks : $program->logbooks()->get();

        return [
            'activity_total' => $activities->count(),
            'activity_completed' => $activities->where('execution_status', Activity::EXECUTION_COMPLETED)->count(),
            'logbook_total' => $logbooks->count(),
            'latest_log_date' => $logbooks->max('log_date'),
            'latest_reported_progress' => $logbooks->sortByDesc('log_date')->first()?->progress_percent,
        ];
    }
}
