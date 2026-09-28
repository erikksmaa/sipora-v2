<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ActivityAttendance;
use App\Models\ActivityParticipation;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivityAttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $activity = Activity::query()->where('slug', 'bootcamp-digital-pemalang')->firstOrFail();
        $recorder = User::query()->where('email', 'youth3@sipora.test')->firstOrFail();
        $patterns = [
            'youth1@sipora.test' => ['present', 'present', 'present'],
            'youth2@sipora.test' => ['present', 'absent', 'present'],
            'youth4@sipora.test' => ['excused', 'present', 'absent'],
        ];

        foreach ($patterns as $email => $statuses) {
            $user = User::query()->where('email', $email)->firstOrFail();
            $participation = ActivityParticipation::query()->where('activity_id', $activity->getKey())
                ->where('user_id', $user->getKey())->where('registration_status', 'accepted')->firstOrFail();
            foreach ($statuses as $index => $status) {
                $session = $activity->sessions()->where('session_number', $index + 1)->firstOrFail();
                $present = $status === ActivityAttendance::STATUS_PRESENT;
                ActivityAttendance::withTrashed()->updateOrCreate(
                    ['activity_session_id' => $session->getKey(), 'participation_id' => $participation->getKey()],
                    ['attendance_status' => $status, 'checked_in_at' => $present ? $session->start_at->copy()->addMinutes(5) : null,
                        'checked_out_at' => $present ? $session->end_at->copy()->subMinutes(5) : null, 'recorded_by' => $recorder->getKey(),
                        'notes' => $status === 'excused' ? 'Izin pada dataset pengembangan.' : null, 'deleted_at' => null]
                );
            }
        }
    }
}
