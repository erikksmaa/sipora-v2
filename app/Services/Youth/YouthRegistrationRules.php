<?php

namespace App\Services\Youth;

use App\Models\User;
use App\Rules\BinaryUuidExists;
use App\Rules\PemalangAddress;
use App\Rules\UniqueNik;
use App\Rules\YouthAge;
use Illuminate\Validation\Rule;

final class YouthRegistrationRules
{
    public function rules(mixed $district, ?User $user = null): array
    {
        return [
            'birth_place' => ['required', 'string', 'max:160'],
            'national_id' => ['bail', 'required', 'string', 'regex:/^[0-9]{16}$/D', new UniqueNik($user?->getKey())],
            'birth_date' => ['bail', 'required', 'date_format:Y-m-d', new YouthAge],
            'gender' => ['required', Rule::in(['male', 'female'])],
            'administrative_area_id' => ['bail', 'required', 'uuid', new PemalangAddress(null, false)],
            'village_id' => ['bail', 'required', 'uuid', new PemalangAddress($district)],
            'address_line' => ['nullable', 'string', 'max:500'],
            'rt' => ['nullable', 'digits_between:1,4'], 'rw' => ['nullable', 'digits_between:1,4'],
            'postal_code' => ['nullable', 'regex:/^[0-9]{5}$/D'],
            'interests' => ['required', 'array', 'min:1', 'max:5'],
            'interests.*' => ['bail', 'required', 'uuid', 'distinct', new BinaryUuidExists('interests')],
        ];
    }
}
