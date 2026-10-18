<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserIdentity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;

final class DemoIdentityAnchorSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            return;
        }
        foreach (User::role('youth')->where('email', 'like', '%@sipora.test')->with(['profile', 'identity'])->get() as $user) {
            $identity = $user->identity ?? new UserIdentity(['user_id' => $user->getKey()]);
            if ($identity->nationalId()) {
                continue;
            }
            $date = $user->profile?->birth_date;
            $day = ($date?->day ?? 1) + ($user->profile?->gender === 'female' ? 40 : 0);
            $datePart = str_pad((string) $day, 2, '0', STR_PAD_LEFT).($date?->format('my') ?? '0105');
            $serial = hexdec(substr(bin2hex($user->getKey()), -8)) % 10000;
            do {
                // Region 000000 deliberately marks a synthetic development fixture.
                $nik = '000000'.$datePart.str_pad((string) $serial, 4, '0', STR_PAD_LEFT);
                $serial = ($serial + 1) % 10000;
                $hash = hash('sha256', $nik);
            } while (UserIdentity::withTrashed()->where('national_id_hash', $hash)->exists());
            $identity->forceFill(['national_id_hash' => $hash, 'national_id_ciphertext' => Crypt::encryptString($nik)])->save();
        }
    }
}
