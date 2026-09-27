<?php

namespace App\Http\Requests\Youth;

use App\Rules\BinaryUuidExists;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDomicileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('youth') === true;
    }

    public function rules(): array
    {
        return [
            'administrative_area_id' => ['required', 'uuid', new BinaryUuidExists('administrative_areas')],
            'address_line' => ['nullable', 'string', 'max:500'],
            'rt' => ['nullable', 'digits_between:1,4'], 'rw' => ['nullable', 'digits_between:1,4'],
            'postal_code' => ['nullable', 'regex:/^[0-9]{5,10}$/'],
        ];
    }
}
