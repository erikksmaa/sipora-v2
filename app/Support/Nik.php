<?php

namespace App\Support;

use Carbon\CarbonImmutable;

final class Nik
{
    public const DUPLICATE = 'NIK ini sudah terdaftar di SIPORA. Jika ini akun Anda, silakan masuk atau gunakan pemulihan akun.';

    public static function validFormat(?string $value): bool
    {
        return is_string($value) && preg_match('/^[0-9]{16}$/D', $value) === 1;
    }

    public static function mask(?string $value): string
    {
        return $value ? str_repeat('*', max(0, strlen($value) - 4)).substr($value, -4) : 'Belum dilengkapi';
    }

    public static function birthDate(string $nik, ?CarbonImmutable $today = null): ?string
    {
        if (! self::validFormat($nik)) {
            return null;
        }
        $today ??= CarbonImmutable::today();
        $day = (int) substr($nik, 6, 2);
        if ($day > 40) {
            $day -= 40;
        }
        $month = (int) substr($nik, 8, 2);
        $yy = (int) substr($nik, 10, 2);
        foreach ([1900, 2000, 2100] as $century) {
            $year = $century + $yy;
            if (! checkdate($month, $day, $year)) {
                continue;
            }
            $date = CarbonImmutable::create($year, $month, $day)->startOfDay();
            $age = (int) $date->diffInYears($today, false);
            if ($date <= $today && $age >= config('youth.min_age') && $age <= config('youth.max_age')) {
                return $date->format('Y-m-d');
            }
        }

        return null;
    }
}
