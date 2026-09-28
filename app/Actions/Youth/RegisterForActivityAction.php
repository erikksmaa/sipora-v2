<?php

namespace App\Actions\Youth;

use App\Models\Activity;
use App\Models\ActivityParticipation;
use App\Models\User;
use App\Notifications\ActivityRegistrationReviewed;
use App\Services\Activity\ActivityEligibilityService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class RegisterForActivityAction
{
    public function __construct(private readonly ActivityEligibilityService $eligibility) {}

    public function execute(User $user, Activity $activity, ?string $notes): ActivityParticipation
    {
        return DB::transaction(function () use ($user, $activity, $notes): ActivityParticipation {
            $activity = Activity::query()->whereKey($activity->getKey())->lockForUpdate()->firstOrFail();
            $this->eligibility->ensureCanRegister($user, $activity);
            if ($activity->participations()->where('user_id', $user->getKey())->exists()) {
                throw ValidationException::withMessages(['registration' => ['Anda sudah memiliki riwayat pendaftaran untuk Activity ini.']]);
            }

            $status = $activity->registration_mode === 'open'
                ? ActivityParticipation::REGISTRATION_ACCEPTED
                : ActivityParticipation::REGISTRATION_PENDING;
            if ($status === ActivityParticipation::REGISTRATION_ACCEPTED) {
                $this->eligibility->ensureCapacity($activity);
            }

            $participation = $activity->participations()->create([
                'user_id' => $user->getKey(),
                'activity_role' => 'participant',
                'registration_status' => $status,
                'completion_status' => ActivityParticipation::COMPLETION_PENDING,
                'registration_notes' => filled($notes) ? trim((string) $notes) : null,
                'requested_at' => now(),
            ]);
            activity()->causedBy($user)->performedOn($participation)->event('activity_registration_created')
                ->withProperties(['activity_id' => $activity->uuid(), 'participation_id' => $participation->uuid(), 'status' => $status])
                ->log('Pendaftaran Activity dibuat');
            if ($status === ActivityParticipation::REGISTRATION_ACCEPTED) {
                activity()->causedBy($user)->performedOn($participation)->event('activity_registration_accepted')
                    ->withProperties(['activity_id' => $activity->uuid(), 'participation_id' => $participation->uuid(), 'decision' => $status])
                    ->log('Pendaftaran Activity diterima otomatis');
                $user->notify(new ActivityRegistrationReviewed($participation));
            }

            return $participation;
        });
    }
}
