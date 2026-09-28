<?php

namespace App\Actions\Manager;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class SubmitActivityForReviewAction
{
    public function execute(User $actor, Activity $activity): Activity
    {
        return DB::transaction(function () use ($actor, $activity): Activity {
            $activity = Activity::query()->whereKey($activity->getKey())->lockForUpdate()->firstOrFail();
            if (! in_array($activity->review_status, [Activity::REVIEW_DRAFT, Activity::REVIEW_REVISION], true)) {
                throw ValidationException::withMessages(['activity' => ['Activity tidak dapat diajukan pada status saat ini.']]);
            }
            foreach (['category_id', 'title', 'description', 'location_type', 'start_at', 'end_at'] as $field) {
                if (blank($activity->{$field})) {
                    throw ValidationException::withMessages([$field => ['Lengkapi data wajib sebelum mengajukan Activity.']]);
                }
            }
            $activity->forceFill(['review_status' => Activity::REVIEW_PENDING])->save();
            activity()->causedBy($actor)->performedOn($activity)->event('activity_submitted')
                ->withProperties(['activity_id' => $activity->uuid()])->log('Activity diajukan untuk verifikasi');

            return $activity;
        });
    }
}
