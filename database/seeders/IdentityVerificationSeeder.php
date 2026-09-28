<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserIdentity;
use App\Models\UserIdentityVerification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class IdentityVerificationSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->where('email', 'admin@sipora.test')->firstOrFail();
        $records = [
            'youth1@sipora.test' => ['number' => 'DEV-NIK-YOUTH-001', 'type' => 'ktp', 'status' => 'verified', 'reviewed' => true, 'note' => null],
            'youth2@sipora.test' => ['number' => 'DEV-KIA-YOUTH-002', 'type' => 'kia', 'status' => 'pending', 'reviewed' => false, 'note' => null],
            'youth3@sipora.test' => ['number' => 'DEV-CARD-YOUTH-003', 'type' => 'student_card', 'status' => 'revision', 'reviewed' => true, 'note' => 'Dokumen contoh perlu diperbarui agar lebih jelas.'],
        ];

        foreach ($records as $email => $record) {
            $user = User::query()->where('email', $email)->firstOrFail();
            $identity = UserIdentity::withTrashed()->firstOrNew(['user_id' => $user->getKey()]);
            $identity->forceFill([
                'national_id_hash' => hash('sha256', $record['number']),
                'national_id_ciphertext' => Crypt::encryptString($record['number']),
                'verification_status' => $record['status'],
                'verification_method' => $record['type'],
                'verified_at' => $record['status'] === 'verified' ? now()->subDays(10) : null,
                'verified_by' => $record['status'] === 'verified' ? $admin->getKey() : null,
                'deleted_at' => null,
            ])->save();

            $path = 'identity-verifications/development/'.$email.'.pdf';
            $contents = "%PDF-1.4\n% SIPORA development-only dummy identity document\n%%EOF\n";
            Storage::disk('private')->put($path, $contents);
            UserIdentityVerification::withTrashed()->updateOrCreate(
                ['user_identity_id' => $identity->getKey(), 'document_type' => $record['type']],
                [
                    'document_number_hash' => hash('sha256', $record['number']),
                    'document_number_ciphertext' => Crypt::encryptString($record['number']),
                    'document_path' => $path,
                    'document_sha256' => hash('sha256', $contents),
                    'status' => $record['status'],
                    'submitted_at' => now()->subDays(12),
                    'reviewed_at' => $record['reviewed'] ? now()->subDays(10) : null,
                    'reviewed_by' => $record['reviewed'] ? $admin->getKey() : null,
                    'review_notes' => $record['note'],
                    'metadata' => ['development_fixture' => true, 'mime_type' => 'application/pdf'],
                    'deleted_at' => null,
                ]
            );
        }
    }
}
