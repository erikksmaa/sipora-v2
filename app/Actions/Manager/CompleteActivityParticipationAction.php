<?php

namespace App\Actions\Manager;

use App\Models\Activity;
use App\Models\ActivityParticipation;
use App\Models\User;
use App\Notifications\ActivityParticipationCompleted;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class CompleteActivityParticipationAction
{
    public function execute(User $manager, Activity $activity, ActivityParticipation $participation, string $decision): ActivityParticipation
    {
        return DB::transaction(function () use ($manager, $activity, $participation, $decision): ActivityParticipation {
            $activity = Activity::query()->whereKey($activity->getKey())->lockForUpdate()->firstOrFail();
            $participation = ActivityParticipation::query()->whereKey($participation->getKey())->lockForUpdate()->firstOrFail();

            if ($participation->activity_id !== $activity->getKey()) {
                throw ValidationException::withMessages(['completion' => ['Partisipasi bukan milik Activity ini.']]);
            }
            if ($activity->execution_status !== Activity::EXECUTION_COMPLETED) {
                throw ValidationException::withMessages(['completion' => ['Eksekusi Activity harus diselesaikan terlebih dahulu.']]);
            }
            if ($participation->registration_status !== ActivityParticipation::REGISTRATION_ACCEPTED
                || $participation->completion_status !== ActivityParticipation::COMPLETION_PENDING) {
                throw ValidationException::withMessages(['completion' => ['Partisipasi tidak dapat diselesaikan pada status saat ini.']]);
            }

            $participation->forceFill([
                'completion_status' => $decision,
                'completed_at' => $decision === ActivityParticipation::COMPLETION_COMPLETED ? now() : null,
            ])->save();

            $event = $decision === ActivityParticipation::COMPLETION_COMPLETED
                ? 'participation_completed' : 'participation_marked_no_show';
            activity()->causedBy($manager)->performedOn($participation)->event($event)
                ->withProperties([
                    'activity_id' => $activity->uuid(),
                    'participation_id' => $participation->uuid(),
                    'decision' => $decision,
                ])->log('Keputusan penyelesaian partisipasi Activity');

            $participation->user->notify(new ActivityParticipationCompleted($participation));

            return $participation;
        });
    }
}
