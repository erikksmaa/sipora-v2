<?php

namespace App\Actions\Youth;

use App\Models\User;
use App\Models\UserIdentity;
use App\Models\UserIdentityVerification;
use App\Support\BinaryUuid;
use App\Support\Nik;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Throwable;

final class SubmitIdentityVerificationAction
{
    /**
     * @param  array{document_type: string}  $data
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
                $nik = $identity->nationalId();
                if (! $nik) {
                    throw ValidationException::withMessages(['national_id' => 'Lengkapi NIK pada Biodata sebelum mengajukan bukti identitas.']);
                }
                $fileHash = hash_file('sha256', $file->getRealPath());
                $storedPath = $file->store('identity-documents', 'private');
                $identity->forceFill(['verification_status' => UserIdentity::STATUS_PENDING, 'verification_method' => $docType])->save();

                $verification = new UserIdentityVerification;
                $verification->id = BinaryUuid::generate();
                $verification->user_identity_id = $identity->getKey();
                $verification->document_type = $docType;
                $verification->document_number_hash = $identity->national_id_hash;
                $verification->document_number_ciphertext = null;
                $verification->document_path = $storedPath;
                $verification->document_sha256 = $fileHash;
                $verification->status = UserIdentityVerification::STATUS_PENDING;
                $verification->submitted_at = now();
                $verification->metadata = [
                    'masked_nik' => Nik::mask($nik),
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
