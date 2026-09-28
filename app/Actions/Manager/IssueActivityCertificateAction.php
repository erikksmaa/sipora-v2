<?php

namespace App\Actions\Manager;

use App\Models\Activity;
use App\Models\ActivityParticipation;
use App\Models\User;
use App\Models\UserCertificate;
use App\Notifications\ActivityCertificateIssued;
use App\Support\BinaryUuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class IssueActivityCertificateAction
{
    public function execute(User $manager, Activity $activity, ActivityParticipation $participation): UserCertificate
    {
        return DB::transaction(function () use ($manager, $activity, $participation): UserCertificate {
            $activity = Activity::query()->whereKey($activity->getKey())->lockForUpdate()->firstOrFail();
            $participation = ActivityParticipation::query()->whereKey($participation->getKey())->lockForUpdate()->firstOrFail();

            if ($participation->activity_id !== $activity->getKey()) {
                throw ValidationException::withMessages(['certificate' => ['Partisipasi bukan milik Activity ini.']]);
            }
            if (! $activity->certificate_enabled) {
                throw ValidationException::withMessages(['certificate' => ['Activity ini tidak menyediakan sertifikat.']]);
            }
            if ($participation->registration_status !== ActivityParticipation::REGISTRATION_ACCEPTED
                || $participation->completion_status !== ActivityParticipation::COMPLETION_COMPLETED) {
                throw ValidationException::withMessages(['certificate' => ['Sertifikat hanya dapat diterbitkan untuk partisipasi yang diterima dan selesai.']]);
            }
            if (UserCertificate::query()->where('participation_id', $participation->getKey())->exists()) {
                throw ValidationException::withMessages(['certificate' => ['Sertifikat untuk partisipasi ini sudah diterbitkan.']]);
            }

            $id = BinaryUuid::generate();
            $uuid = BinaryUuid::text($id);
            $certificate = new UserCertificate;
            $certificate->setAttribute('id', $id);
            $certificate->fill([
                'user_id' => $participation->user_id,
                'participation_id' => $participation->getKey(),
                'source_type' => UserCertificate::SOURCE_SIPORA,
                'name' => $activity->title,
                'issuer_name' => $activity->organization->name,
                'certificate_number' => 'SIPORA-ACT-'.now()->year.'-'.strtoupper(str_replace('-', '', $uuid)),
                'verification_code' => hash_hmac('sha256', $uuid, (string) config('app.key')),
                'issued_at' => today(),
                'expires_at' => null,
                'file_path' => null,
                'external_url' => null,
                'verification_status' => UserCertificate::STATUS_VERIFIED,
            ]);
            $certificate->save();

            activity()->causedBy($manager)->performedOn($certificate)->event('certificate_issued')
                ->withProperties([
                    'activity_id' => $activity->uuid(),
                    'participation_id' => $participation->uuid(),
                    'certificate_id' => $certificate->uuid(),
                ])->log('Sertifikat Activity diterbitkan');

            $participation->user->notify(new ActivityCertificateIssued($certificate));

            return $certificate;
        });
    }
}
