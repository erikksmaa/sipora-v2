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
            ['activity' => 'workshop-web-development-pemula', 'email' => 'youth3@sipora.test', 'status' => 'pending'],
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
            ActivityParticipation::withTrashed()->updateOrCreate(
                ['activity_id' => $activity->getKey(), 'user_id' => $user->getKey()],
                ['activity_role' => 'participant', 'registration_status' => $record['status'], 'completion_status' => 'pending',
                    'registration_notes' => $record['status'] === 'rejected' ? 'Contoh penolakan pendaftaran.' : null,
                    'requested_at' => now()->subWeeks(2), 'reviewed_at' => $reviewed ? now()->subWeeks(2)->addDay() : null,
                    'reviewed_by' => $reviewed ? $reviewer->getKey() : null, 'completed_at' => null, 'deleted_at' => null]
            );
        }
    }
}
