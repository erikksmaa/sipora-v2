<?php

namespace App\Rules;

use App\Support\BinaryUuid;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class BinaryUuidExists implements ValidationRule
{
    public function __construct(private readonly string $table) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $exists = DB::table($this->table)->where('id', BinaryUuid::bytes((string) $value))->whereNull('deleted_at')->exists();
        } catch (InvalidArgumentException) {
            $exists = false;
        }

        if (! $exists) {
            $fail('The selected :attribute is invalid.');
        }
    }
}
