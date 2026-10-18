<?php

namespace App\Rules;

use Carbon\CarbonImmutable;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class YouthAge implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $date = CarbonImmutable::createFromFormat('!Y-m-d', (string) $value);
            $age = (int) $date->diffInYears(CarbonImmutable::today(), false);
            if ($date->isFuture() || $age < config('youth.min_age') || $age > config('youth.max_age')) {
                $fail('Usia harus berada dalam rentang pemuda SIPORA (16–30 tahun).');
            }
        } catch (\Throwable) {
            $fail('Tanggal lahir tidak valid.');
        }
    }
}
