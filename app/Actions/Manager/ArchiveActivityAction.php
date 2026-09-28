<?php

namespace App\Actions\Manager;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class ArchiveActivityAction
{
    public function execute(User $actor, Activity $activity): Activity
    {
        return DB::transaction(function () use ($actor, $activity): Activity {
            $activity = Activity::query()->whereKey($activity->getKey())->lockForUpdate()->firstOrFail();
            if ($activity->publication_status !== Activity::PUBLICATION_PUBLISHED) {
                throw ValidationException::withMessages(['activity' => ['Hanya Activity terbit yang dapat diarsipkan.']]);
            }
            $activity->forceFill(['publication_status' => Activity::PUBLICATION_ARCHIVED])->save();
            activity()->causedBy($actor)->performedOn($activity)->event('activity_archived')
                ->withProperties(['activity_id' => $activity->uuid()])->log('Activity diarsipkan');

            return $activity;
        });
    }
}
