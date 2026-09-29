<?php

namespace App\Http\Requests\Manager;

use App\Rules\BinaryUuidExists;
use Illuminate\Foundation\Http\FormRequest;

class StoreProgramRequest extends FormRequest
{
    public function authorize(): bool
    {
        $organization = $this->route('organization');

        return $organization && $this->user()?->can('manageMemberships', $organization);
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'uuid', new BinaryUuidExists('program_categories')],
            'title' => ['required', 'string', 'max:220'],
            'description' => ['nullable', 'string', 'max:10000'],
            'objectives' => ['nullable', 'string', 'max:10000'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ];
    }
}
