<?php

namespace App\Actions\Youth;

use App\Models\ActivityParticipation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class CancelActivityRegistrationAction
{
    public function execute(User $user, ActivityParticipation $participation): ActivityParticipation
    {
        return DB::transaction(function () use ($user, $participation): ActivityParticipation {
            $participation = ActivityParticipation::query()->whereKey($participation->getKey())->lockForUpdate()->firstOrFail();
            if ($participation->user_id !== $user->getKey()
                || ! in_array($participation->registration_status, [ActivityParticipation::REGISTRATION_PENDING, ActivityParticipation::REGISTRATION_ACCEPTED], true)) {
                throw ValidationException::withMessages(['registration' => ['Pendaftaran tidak dapat dibatalkan.']]);
            }
            $activity = $participation->activity()->lockForUpdate()->firstOrFail();
            $cutoff = $activity->registration_close_at ?? $activity->start_at;
            if (now()->gt($cutoff)) {
                throw ValidationException::withMessages(['registration' => ['Batas waktu pembatalan telah lewat.']]);
            }
            $participation->forceFill(['registration_status' => ActivityParticipation::REGISTRATION_CANCELLED])->save();
            activity()->causedBy($user)->performedOn($participation)->event('activity_registration_cancelled')
                ->withProperties(['activity_id' => $activity->uuid(), 'participation_id' => $participation->uuid()])
                ->log('Pendaftaran Activity dibatalkan');

            return $participation;
        });
    }
}
