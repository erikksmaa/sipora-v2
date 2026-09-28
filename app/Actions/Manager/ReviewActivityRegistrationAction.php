<?php

namespace App\Actions\Manager;

use App\Models\Activity;
use App\Models\ActivityParticipation;
use App\Models\User;
use App\Notifications\ActivityRegistrationReviewed;
use App\Services\Activity\ActivityEligibilityService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class ReviewActivityRegistrationAction
{
    public function __construct(private readonly ActivityEligibilityService $eligibility) {}

    public function execute(User $manager, Activity $activity, ActivityParticipation $participation, string $decision, ?string $notes): ActivityParticipation
    {
        return DB::transaction(function () use ($manager, $activity, $participation, $decision, $notes): ActivityParticipation {
            $activity = Activity::query()->whereKey($activity->getKey())->lockForUpdate()->firstOrFail();
            $participation = ActivityParticipation::query()->whereKey($participation->getKey())->lockForUpdate()->firstOrFail();
            if ($participation->activity_id !== $activity->getKey()
                || $participation->registration_status !== ActivityParticipation::REGISTRATION_PENDING) {
                throw ValidationException::withMessages(['decision' => ['Pendaftaran tidak lagi menunggu keputusan.']]);
            }
            if ($decision === ActivityParticipation::REGISTRATION_ACCEPTED) {
                if ($activity->review_status !== Activity::REVIEW_APPROVED
                    || $activity->publication_status !== Activity::PUBLICATION_PUBLISHED
                    || $activity->execution_status !== Activity::EXECUTION_SCHEDULED) {
                    throw ValidationException::withMessages(['decision' => ['Activity tidak lagi dapat menerima peserta.']]);
                }
                $this->eligibility->ensureCapacity($activity);
            }
            if ($decision === ActivityParticipation::REGISTRATION_REJECTED && blank($notes)) {
                throw ValidationException::withMessages(['notes' => ['Alasan penolakan wajib diisi.']]);
            }

            $participation->forceFill([
                'registration_status' => $decision,
                'registration_notes' => filled($notes) ? trim((string) $notes) : $participation->registration_notes,
                'reviewed_at' => now(),
                'reviewed_by' => $manager->getKey(),
            ])->save();
            $event = $decision === ActivityParticipation::REGISTRATION_ACCEPTED
                ? 'activity_registration_accepted' : 'activity_registration_rejected';
            activity()->causedBy($manager)->performedOn($participation)->event($event)
                ->withProperties(['activity_id' => $activity->uuid(), 'participation_id' => $participation->uuid(), 'decision' => $decision])
                ->log('Keputusan pendaftaran Activity');
            $participation->user->notify(new ActivityRegistrationReviewed($participation));

            return $participation;
        });
    }
}
