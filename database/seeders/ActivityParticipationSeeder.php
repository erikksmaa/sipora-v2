<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ActivityParticipation;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivityParticipationSeeder extends Seeder
{
    public function run(): void
    {
        $reviewer = User::query()->where('email', 'youth1@sipora.test')->firstOrFail();
        $records = [
            ['activity' => 'workshop-web-development-pemula', 'email' => 'youth2@sipora.test', 'status' => 'accepted'],
            ['activity' => 'workshop-web-development-pemula', 'email' => 'youth3@sipora.test', 'status' => 'accepted'],
            ['activity' => 'workshop-web-development-pemula', 'email' => 'youth4@sipora.test', 'status' => 'rejected'],
            ['activity' => 'workshop-web-development-pemula', 'email' => 'youth5@sipora.test', 'status' => 'cancelled'],
            ['activity' => 'bootcamp-digital-pemalang', 'email' => 'youth1@sipora.test', 'status' => 'accepted'],
            ['activity' => 'bootcamp-digital-pemalang', 'email' => 'youth2@sipora.test', 'status' => 'accepted'],
            ['activity' => 'bootcamp-digital-pemalang', 'email' => 'youth4@sipora.test', 'status' => 'accepted'],
        ];

        foreach ($records as $record) {
            $activity = Activity::query()->where('slug', $record['activity'])->firstOrFail();
            $user = User::query()->where('email', $record['email'])->firstOrFail();
            $reviewed = in_array($record['status'], ['accepted', 'rejected'], true);
            $completion = match ([$record['activity'], $record['email']]) {
                ['bootcamp-digital-pemalang', 'youth1@sipora.test'] => ActivityParticipation::COMPLETION_COMPLETED,
                ['bootcamp-digital-pemalang', 'youth2@sipora.test'] => ActivityParticipation::COMPLETION_COMPLETED,
                ['bootcamp-digital-pemalang', 'youth4@sipora.test'] => ActivityParticipation::COMPLETION_NO_SHOW,
                default => ActivityParticipation::COMPLETION_PENDING,
            };
            ActivityParticipation::withTrashed()->updateOrCreate(
                ['activity_id' => $activity->getKey(), 'user_id' => $user->getKey()],
                ['activity_role' => 'participant', 'registration_status' => $record['status'], 'completion_status' => $completion,
                    'registration_notes' => $record['status'] === 'rejected' ? 'Contoh penolakan pendaftaran.' : null,
                    'requested_at' => now()->subWeeks(2), 'reviewed_at' => $reviewed ? now()->subWeeks(2)->addDay() : null,
                    'reviewed_by' => $reviewed ? $reviewer->getKey() : null,
                    'completed_at' => $completion === ActivityParticipation::COMPLETION_COMPLETED ? now()->subWeek() : null,
                    'deleted_at' => null]
            );
        }
    }
}
