<?php

namespace App\Notifications;

use App\Models\ActivityParticipation;
use App\Models\User;
use App\Notifications\Channels\SiporaDatabaseChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

final class ActivityParticipationCompleted extends Notification
{
    use Queueable;

    public function __construct(private readonly ActivityParticipation $participation) {}

    public function via(User $notifiable): array
    {
        return [SiporaDatabaseChannel::class];
    }

    public function toSiporaDatabase(User $notifiable): array
    {
        $completed = $this->participation->completion_status === ActivityParticipation::COMPLETION_COMPLETED;

        return [
            'notification_type' => 'activity_participation_completion',
            'title' => $completed ? 'Activity masuk ke Passport' : 'Status partisipasi diperbarui',
            'body' => $completed
                ? 'Partisipasi Anda pada '.$this->participation->activity->title.' telah diselesaikan dan masuk ke Activity Passport.'
                : 'Partisipasi Anda pada '.$this->participation->activity->title.' ditandai tidak hadir.',
            'data' => [
                'activity_id' => $this->participation->activity->uuid(),
                'participation_id' => $this->participation->uuid(),
                'status' => $this->participation->completion_status,
            ],
        ];
    }
}
