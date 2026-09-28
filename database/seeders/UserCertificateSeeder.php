<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ActivityParticipation;
use App\Models\User;
use App\Models\UserCertificate;
use App\Support\BinaryUuid;
use Illuminate\Database\Seeder;

class UserCertificateSeeder extends Seeder
{
    public function run(): void
    {
        $activity = Activity::query()->where('slug', 'bootcamp-digital-pemalang')->firstOrFail();
        $user = User::query()->where('email', 'youth2@sipora.test')->firstOrFail();
        $participation = ActivityParticipation::query()
            ->where('activity_id', $activity->getKey())
            ->where('user_id', $user->getKey())
            ->where('registration_status', ActivityParticipation::REGISTRATION_ACCEPTED)
            ->where('completion_status', ActivityParticipation::COMPLETION_COMPLETED)
            ->firstOrFail();

        $certificate = UserCertificate::withTrashed()->firstOrNew(['participation_id' => $participation->getKey()]);
        if (! $certificate->exists) {
            $id = BinaryUuid::generate();
            $uuid = BinaryUuid::text($id);
            $certificate->setAttribute('id', $id);
            $certificate->certificate_number = 'SIPORA-ACT-'.now()->year.'-'.strtoupper(str_replace('-', '', $uuid));
            $certificate->verification_code = hash_hmac('sha256', $uuid, (string) config('app.key'));
        }
        $certificate->fill([
            'user_id' => $user->getKey(),
            'source_type' => UserCertificate::SOURCE_SIPORA,
            'name' => $activity->title,
            'issuer_name' => $activity->organization->name,
            'issued_at' => now()->subWeek()->toDateString(),
            'expires_at' => null,
            'file_path' => null,
            'external_url' => null,
            'verification_status' => UserCertificate::STATUS_VERIFIED,
        ]);
        $certificate->deleted_at = null;
        $certificate->save();
    }
}
