<?php

namespace App\Actions\Manager;

use App\Models\Activity;
use App\Models\ActivityAttendance;
use App\Models\ActivityParticipation;
use App\Models\ActivitySession;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class RecordActivityAttendanceAction
{
    public function execute(User $manager, Activity $activity, ActivitySession $session, ActivityParticipation $participation, array $data): ActivityAttendance
    {
        return DB::transaction(function () use ($manager, $activity, $session, $participation, $data): ActivityAttendance {
            $session = ActivitySession::query()->whereKey($session->getKey())->lockForUpdate()->firstOrFail();
            $participation = ActivityParticipation::query()->whereKey($participation->getKey())->lockForUpdate()->firstOrFail();

            if ($session->activity_id !== $activity->getKey() || $participation->activity_id !== $activity->getKey()) {
                throw ValidationException::withMessages(['attendance' => ['Sesi dan peserta harus berasal dari Activity yang sama.']]);
            }
            if ($participation->registration_status !== ActivityParticipation::REGISTRATION_ACCEPTED) {
                throw ValidationException::withMessages(['attendance' => ['Presensi hanya dapat dicatat untuk peserta yang diterima.']]);
            }

            $attendance = ActivityAttendance::query()
                ->where('activity_session_id', $session->getKey())
                ->where('participation_id', $participation->getKey())
                ->lockForUpdate()->first();
            $event = $attendance ? 'attendance_updated' : 'attendance_marked';
            $attendance ??= new ActivityAttendance([
                'activity_session_id' => $session->getKey(),
                'participation_id' => $participation->getKey(),
            ]);
            $attendance->fill($data);
            $attendance->recorded_by = $manager->getKey();
            $attendance->save();

            activity()->causedBy($manager)->performedOn($attendance)->event($event)
                ->withProperties([
                    'activity_id' => $activity->uuid(),
                    'session_id' => $session->uuid(),
                    'participation_id' => $participation->uuid(),
                    'attendance_status' => $attendance->attendance_status,
                ])->log('Presensi sesi Activity diperbarui');

            return $attendance;
        });
    }
}
