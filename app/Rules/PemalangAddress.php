<?php

namespace App\Rules;

use App\Models\AdministrativeArea;
use App\Support\BinaryUuid;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class PemalangAddress implements ValidationRule
{
    public function __construct(private mixed $district, private bool $village = true) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $query = AdministrativeArea::whereKey(BinaryUuid::bytes((string) $value));
            $valid = $this->village
                ? $query->where('area_level', 'village')->where('parent_id', BinaryUuid::bytes((string) $this->district))->whereHas('parent.parent', fn ($q) => $q->where('code', '33.27'))->exists()
                : $query->where('area_level', 'district')->whereHas('parent', fn ($q) => $q->where('code', '33.27'))->exists();
        } catch (\Throwable) {
            $valid = false;
        }
        if (! $valid) {
            $fail($this->village ? 'Desa/Kelurahan tidak sesuai dengan kecamatan yang dipilih.' : 'Pilih kecamatan di Kabupaten Pemalang.');
        }
    }
}
