<?php

namespace App\Services\Activity;

use App\Models\Activity;
use App\Models\OrganizationMembership;
use App\Models\User;
use App\Models\UserIdentity;
use Illuminate\Validation\ValidationException;

final class ActivityEligibilityService
{
    public function ensureCanRegister(User $user, Activity $activity): void
    {
        if ($activity->review_status !== Activity::REVIEW_APPROVED
            || $activity->publication_status !== Activity::PUBLICATION_PUBLISHED
            || $activity->execution_status !== Activity::EXECUTION_SCHEDULED) {
            $this->fail('Activity tidak sedang menerima pendaftaran.');
        }

        $now = now();
        if ($activity->registration_open_at && $now->lt($activity->registration_open_at)) {
            $this->fail('Pendaftaran Activity belum dibuka.');
        }
        if (($activity->registration_close_at && $now->gt($activity->registration_close_at)) || $now->gte($activity->start_at)) {
            $this->fail('Pendaftaran Activity telah ditutup.');
        }

        if ($activity->requires_identity_verification
            && $user->identity?->verification_status !== UserIdentity::STATUS_VERIFIED) {
            $this->fail('Activity ini mensyaratkan identitas SIPORA yang telah diverifikasi.');
        }

        if ($activity->members_only && ! OrganizationMembership::query()
            ->where('organization_id', $activity->organization_id)
            ->where('user_id', $user->getKey())
            ->where('membership_status', OrganizationMembership::STATUS_ACTIVE)
            ->exists()) {
            $this->fail('Activity ini hanya tersedia bagi anggota aktif komunitas.');
        }

        if ($activity->min_age !== null || $activity->max_age !== null) {
            $birthDate = $user->profile?->birth_date;
            if (! $birthDate) {
                $this->fail('Lengkapi tanggal lahir pada profil untuk memeriksa kelayakan usia.');
            }
            $age = $birthDate->diffInYears($activity->start_at);
            if (($activity->min_age !== null && $age < $activity->min_age)
                || ($activity->max_age !== null && $age > $activity->max_age)) {
                $this->fail('Usia Anda tidak memenuhi persyaratan Activity.');
            }
        }
    }

    public function ensureCapacity(Activity $activity): void
    {
        if ($activity->quota !== null && $activity->participations()
            ->where('registration_status', 'accepted')->count() >= $activity->quota) {
            $this->fail('Kuota peserta Activity telah penuh.');
        }
    }

    private function fail(string $message): never
    {
        throw ValidationException::withMessages(['registration' => [$message]]);
    }
}
