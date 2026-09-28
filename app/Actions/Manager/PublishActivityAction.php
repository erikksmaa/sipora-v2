<?php

namespace App\Actions\Manager;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class PublishActivityAction
{
    public function execute(User $actor, Activity $activity): Activity
    {
        return DB::transaction(function () use ($actor, $activity): Activity {
            $activity = Activity::query()->whereKey($activity->getKey())->lockForUpdate()->firstOrFail();
            if ($activity->review_status !== Activity::REVIEW_APPROVED || $activity->publication_status !== Activity::PUBLICATION_UNPUBLISHED) {
                throw ValidationException::withMessages(['activity' => ['Hanya Activity yang disetujui dan belum terbit yang dapat dipublikasikan.']]);
            }
            $activity->forceFill(['publication_status' => Activity::PUBLICATION_PUBLISHED, 'published_at' => now()])->save();
            activity()->causedBy($actor)->performedOn($activity)->event('activity_published')
                ->withProperties(['activity_id' => $activity->uuid()])->log('Activity dipublikasikan');

            return $activity;
        });
    }
}
