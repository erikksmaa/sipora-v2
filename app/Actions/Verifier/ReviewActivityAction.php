<?php

namespace App\Actions\Verifier;

use App\Models\Activity;
use App\Models\ActivityReview;
use App\Models\User;
use App\Notifications\ActivityVerificationReviewed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

final class ReviewActivityAction
{
    public function execute(User $verifier, Activity $activity, string $decision, ?string $notes): Activity
    {
        return DB::transaction(function () use ($verifier, $activity, $decision, $notes): Activity {
            $activity = Activity::query()->whereKey($activity->getKey())->lockForUpdate()->firstOrFail();
            Gate::forUser($verifier)->authorize('review', $activity);
            if ($activity->review_status !== Activity::REVIEW_PENDING) {
                throw ValidationException::withMessages(['decision' => ['Activity tidak lagi menunggu verifikasi.']]);
            }
            if (in_array($decision, [Activity::REVIEW_REVISION, Activity::REVIEW_REJECTED], true) && blank($notes)) {
                throw ValidationException::withMessages(['notes' => ['Catatan wajib diisi untuk keputusan ini.']]);
            }
            ActivityReview::create(['activity_id' => $activity->getKey(), 'reviewer_id' => $verifier->getKey(),
                'decision' => $decision, 'review_notes' => filled($notes) ? trim((string) $notes) : null, 'reviewed_at' => now()]);
            $activity->forceFill(['review_status' => $decision])->save();
            $event = match ($decision) {
                Activity::REVIEW_APPROVED => 'activity_approved',
                Activity::REVIEW_REVISION => 'activity_revision_requested',
                default => 'activity_rejected',
            };
            activity()->causedBy($verifier)->performedOn($activity)->event($event)
                ->withProperties(['activity_id' => $activity->uuid(), 'decision' => $decision])->log('Keputusan verifikasi Activity');
            $activity->creator->notify(new ActivityVerificationReviewed($activity, $decision));

            return $activity->fresh(['latestReview']);
        });
    }
}
