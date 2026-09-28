<?php

namespace App\Actions\Manager;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class CompleteActivityExecutionAction
{
    public function execute(User $manager, Activity $activity): Activity
    {
        return DB::transaction(function () use ($manager, $activity): Activity {
            $activity = Activity::query()->whereKey($activity->getKey())->lockForUpdate()->firstOrFail();

            if ($activity->review_status !== Activity::REVIEW_APPROVED
                || $activity->publication_status !== Activity::PUBLICATION_PUBLISHED
                || ! in_array($activity->execution_status, [Activity::EXECUTION_SCHEDULED, Activity::EXECUTION_ONGOING], true)
                || $activity->end_at->isFuture()
                || ! $activity->sessions()->exists()) {
                throw ValidationException::withMessages([
                    'execution' => ['Activity hanya dapat diselesaikan setelah jadwal berakhir dan memiliki sesi.'],
                ]);
            }

            $activity->forceFill(['execution_status' => Activity::EXECUTION_COMPLETED])->save();

            activity()->causedBy($manager)->performedOn($activity)->event('activity_execution_completed')
                ->withProperties(['activity_id' => $activity->uuid()])
                ->log('Eksekusi Activity diselesaikan');

            return $activity;
        });
    }
}
