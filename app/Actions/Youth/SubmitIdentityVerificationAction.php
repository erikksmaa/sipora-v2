<?php

namespace App\Actions\Youth;

use App\Models\User;
use App\Models\UserIdentity;
use App\Models\UserIdentityVerification;
use App\Support\BinaryUuid;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class SubmitIdentityVerificationAction
{
    /**
     * @param  array{document_type: string, document_number?: ?string}  $data
     */
    public function execute(User $user, array $data, UploadedFile $file): UserIdentityVerification
    {
        return DB::transaction(function () use ($user, $data, $file): UserIdentityVerification {
            $identity = $user->identity()->firstOrCreate(
                [],
                ['id' => BinaryUuid::generate(), 'verification_status' => 'unverified']
            );

            if ($identity->verification_status === 'verified') {
                throw ValidationException::withMessages([
                    'document' => ['Identitas Anda telah terverifikasi resmi dan tidak dapat diubah secara sepihak.'],
                ]);
            }

            $docType = $data['document_type'];
            $docNumber = isset($data['document_number']) ? trim((string) $data['document_number']) : null;
            $docNumberHash = $docNumber !== null && $docNumber !== '' ? hash('sha256', $docNumber) : null;
            $docNumberCipher = $docNumber !== null && $docNumber !== '' ? Crypt::encryptString($docNumber) : null;
            $maskedNumber = null;
            if ($docNumber !== null && $docNumber !== '') {
                $len = strlen($docNumber);
                if ($len > 8) {
                    $maskedNumber = substr($docNumber, 0, 4).str_repeat('*', $len - 8).substr($docNumber, -4);
                } else {
                    $maskedNumber = str_repeat('*', max(1, $len - 2)).substr($docNumber, -2);
                }
            }

            $fileHash = hash_file('sha256', $file->getRealPath());
            $path = $file->store('identity-documents', 'private');

            $identity->fill([
                'verification_status' => 'pending',
                'verification_method' => $docType,
                'national_id_hash' => $docNumberHash,
                'national_id_ciphertext' => $docNumberCipher,
            ]);
            $identity->save();

            $verification = new UserIdentityVerification;
            $verification->id = BinaryUuid::generate();
            $verification->user_identity_id = $identity->getKey();
            $verification->document_type = $docType;
            $verification->document_number_hash = $docNumberHash;
            $verification->document_number_ciphertext = $docNumberCipher;
            $verification->document_path = $path;
            $verification->document_sha256 = $fileHash;
            $verification->status = 'pending';
            $verification->submitted_at = now();
            $verification->metadata = [
                'masked_document_number' => $maskedNumber,
                'original_filename' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'size_bytes' => $file->getSize(),
            ];
            $verification->save();

            activity()
                ->causedBy($user)
                ->performedOn($identity)
                ->event('identity_submitted')
                ->withProperties([
                    'document_type' => $docType,
                    'submission_id' => $verification->uuid(),
                ])
                ->log('Pengajuan verifikasi identitas pemuda');

            return $verification;
        });
    }
}
