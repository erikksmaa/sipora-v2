<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['email' => 'youth1@sipora.test', 'type' => 'identity_verified', 'title' => 'Identitas terverifikasi', 'body' => 'Verifikasi identitas Anda telah disetujui.'],
            ['email' => 'youth1@sipora.test', 'type' => 'community_approved', 'title' => 'Komunitas disetujui', 'body' => 'Komunitas Programmer Pemalang telah aktif.'],
            ['email' => 'youth2@sipora.test', 'type' => 'activity_registration_accepted', 'title' => 'Pendaftaran diterima', 'body' => 'Pendaftaran Activity Anda telah diterima.'],
        ] as $record) {
            $user = User::query()->where('email', $record['email'])->firstOrFail();
            UserNotification::withTrashed()->updateOrCreate(
                ['user_id' => $user->getKey(), 'notification_type' => $record['type'], 'title' => $record['title']],
                ['body' => $record['body'], 'data' => ['development_fixture' => true], 'read_at' => null, 'deleted_at' => null]
            );
        }
    }
}
