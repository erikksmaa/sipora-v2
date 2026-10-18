<?php

namespace App\Services\Youth;

use App\Models\User;
use App\Models\UserIdentity;
use App\Support\Nik;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class IdentityAnchorService
{
    public function assign(User $user, string $nik): UserIdentity
    {
        if (! Nik::validFormat($nik)) {
            throw ValidationException::withMessages(['national_id' => 'NIK harus terdiri dari 16 digit angka.']);
        }
        try {
            return DB::transaction(function () use ($user, $nik) {
                User::whereKey($user->getKey())->lockForUpdate()->firstOrFail();
                $identity = $user->identity()->firstOrNew();
                $existing = $identity->nationalId();
                if ($existing && ! hash_equals($existing, $nik)) {
                    throw ValidationException::withMessages(['national_id' => 'NIK akun sudah tersimpan. Hubungi pengelola jika memerlukan koreksi.']);
                }
                $hash = hash('sha256', $nik);
                if (UserIdentity::withTrashed()->where('national_id_hash', $hash)->when($identity->exists, fn ($q) => $q->where('id', '!=', $identity->getKey()))->exists()) {
                    throw ValidationException::withMessages(['national_id' => Nik::DUPLICATE]);
                }
                $identity->forceFill(['national_id_hash' => $hash, 'national_id_ciphertext' => Crypt::encryptString($nik)])->save();

                return $identity;
            });
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages(['national_id' => Nik::DUPLICATE]);
        }
    }
}
