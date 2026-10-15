<?php

namespace App\Notifications;

use App\Models\ProgramApplication;
use App\Models\User;
use App\Notifications\Channels\SiporaDatabaseChannel;
use Illuminate\Notifications\Notification;

final class ProgramApplicationChanged extends Notification
{
    public function __construct(private readonly ProgramApplication $application) {}

    public function via(User $notifiable): array
    {
        return [SiporaDatabaseChannel::class];
    }

    public function toSiporaDatabase(User $notifiable): array
    {
        return ['notification_type' => 'program_application_result', 'title' => 'Pendaftaran Program: '.ProgramApplication::LABELS[$this->application->status], 'body' => 'Status pendaftaran Anda pada '.$this->application->program->title.' telah diperbarui.', 'data' => ['program_id' => $this->application->program->uuid(), 'status' => $this->application->status]];
    }
}
