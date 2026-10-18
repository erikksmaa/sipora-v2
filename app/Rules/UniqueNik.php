<?php

namespace App\Rules;

use App\Models\UserIdentity;
use App\Support\Nik;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class UniqueNik implements ValidationRule
{
    public function __construct(private ?string $userId = null) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! Nik::validFormat($value)) {
            return;
        }
        if (UserIdentity::withTrashed()->where('national_id_hash', hash('sha256', $value))->when($this->userId, fn ($q) => $q->where('user_id', '!=', $this->userId))->exists()) {
            $fail(Nik::DUPLICATE);
        }
    }
}
