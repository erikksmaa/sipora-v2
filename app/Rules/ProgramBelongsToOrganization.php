<?php

namespace App\Rules;

use App\Models\Organization;
use App\Models\Program;
use App\Support\BinaryUuid;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use InvalidArgumentException;

final class ProgramBelongsToOrganization implements ValidationRule
{
    public function __construct(private readonly Organization $organization) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (blank($value)) {
            return;
        }

        try {
            $exists = Program::query()
                ->whereKey(BinaryUuid::bytes((string) $value))
                ->where('organization_id', $this->organization->getKey())
                ->whereIn('execution_status', [Program::STATUS_PLANNED, Program::STATUS_RUNNING])
                ->exists();
        } catch (InvalidArgumentException) {
            $exists = false;
        }

        if (! $exists) {
            $fail('Program yang dipilih tidak tersedia untuk komunitas ini.');
        }
    }
}
