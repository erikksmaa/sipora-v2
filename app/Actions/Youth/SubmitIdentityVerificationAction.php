<?php

namespace App\Actions\Youth;

use App\Models\User;
use App\Models\UserIdentity;
use App\Models\UserIdentityVerification;
use App\Support\BinaryUuid;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Throwable;

final class SubmitIdentityVerificationAction
{
    /**
     * @param  array{document_type: string, document_number?: ?string}  $data
     */
    public function execute(User $user, array $data, UploadedFile $file): UserIdentityVerification
    {
        $storedPath = null;

        try {
            return DB::transaction(function () use ($user, $data, $file, &$storedPath): UserIdentityVerification {
                $identity = $user->identity()->lockForUpdate()->first();
                if (! $identity) {
                    $identity = new UserIdentity([
                        'verification_status' => UserIdentity::STATUS_UNVERIFIED,
                    ]);
                    $identity->id = BinaryUuid::generate();
                    $identity->user_id = $user->getKey();
                    $identity->save();
                }

                if ($identity->verification_status === UserIdentity::STATUS_VERIFIED) {
                    throw ValidationException::withMessages([
                        'document_file' => ['Identitas Anda telah terverifikasi resmi dan tidak dapat diubah secara sepihak.'],
                    ]);
                }

                if ($identity->verifications()->where('status', UserIdentityVerification::STATUS_PENDING)->exists()) {
                    throw ValidationException::withMessages([
                        'document_file' => ['Masih ada pengajuan identitas yang menunggu peninjauan.'],
                    ]);
                }

                $docType = $data['document_type'];
                $docNumber = preg_replace('/\s+/', '', trim((string) $data['document_number']));
                $docNumberHash = hash('sha256', $docNumber);
                $docNumberCipher = Crypt::encryptString($docNumber);
                $len = strlen($docNumber);
                $maskedNumber = $len > 8
                    ? substr($docNumber, 0, 4).str_repeat('*', $len - 8).substr($docNumber, -4)
                    : str_repeat('*', max(1, $len - 2)).substr($docNumber, -2);

                $fileHash = hash_file('sha256', $file->getRealPath());
                $storedPath = $file->store('identity-documents', 'private');

                $identityData = [
                    'verification_status' => UserIdentity::STATUS_PENDING,
                    'verification_method' => $docType,
                    'national_id_hash' => null,
                    'national_id_ciphertext' => null,
                ];
                if (in_array($docType, ['ktp', 'kia'], true)) {
                    $identityData['national_id_hash'] = $docNumberHash;
                    $identityData['national_id_ciphertext'] = $docNumberCipher;
                }
                $identity->fill($identityData)->save();

                $verification = new UserIdentityVerification;
                $verification->id = BinaryUuid::generate();
                $verification->user_identity_id = $identity->getKey();
                $verification->document_type = $docType;
                $verification->document_number_hash = $docNumberHash;
                $verification->document_number_ciphertext = $docNumberCipher;
                $verification->document_path = $storedPath;
                $verification->document_sha256 = $fileHash;
                $verification->status = UserIdentityVerification::STATUS_PENDING;
                $verification->submitted_at = now();
                $verification->metadata = [
                    'masked_document_number' => $maskedNumber,
                    'mime_type' => $file->getMimeType(),
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
        } catch (Throwable $exception) {
            if ($storedPath !== null) {
                Storage::disk('private')->delete($storedPath);
            }

            throw $exception;
        }
    }
}
