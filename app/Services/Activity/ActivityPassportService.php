<?php

namespace App\Services\Activity;

use App\Models\ActivityParticipation;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

final class ActivityPassportService
{
    public function queryFor(User $user): Builder
    {
        return ActivityParticipation::query()
            ->where('user_id', $user->getKey())
            ->where('registration_status', ActivityParticipation::REGISTRATION_ACCEPTED)
            ->where('completion_status', ActivityParticipation::COMPLETION_COMPLETED)
            ->with(['activity.organization', 'activity.category', 'certificate'])
            ->withCount([
                'attendances as present_count' => fn ($query) => $query->where('attendance_status', 'present'),
                'attendances as absent_count' => fn ($query) => $query->where('attendance_status', 'absent'),
                'attendances as excused_count' => fn ($query) => $query->where('attendance_status', 'excused'),
            ])
            ->latest('completed_at');
    }

    public function isEligible(ActivityParticipation $participation, User $user): bool
    {
        return $participation->user_id === $user->getKey()
            && $participation->registration_status === ActivityParticipation::REGISTRATION_ACCEPTED
            && $participation->completion_status === ActivityParticipation::COMPLETION_COMPLETED;
    }
}
