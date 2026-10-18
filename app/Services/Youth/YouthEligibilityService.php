<?php

namespace App\Services\Youth;

use App\Models\User;

final class YouthEligibilityService
{
    /** @return array{age:?int, eligible:?bool} */
    public function for(User $user): array
    {
        $birthDate = $user->profile?->birth_date;
        if ($birthDate === null) {
            return ['age' => null, 'eligible' => null];
        }
        $age = $birthDate->age;

        return ['age' => $age, 'eligible' => $age >= config('youth.min_age') && $age <= config('youth.max_age')];
    }
}
