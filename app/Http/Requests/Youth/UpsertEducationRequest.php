<?php

namespace App\Http\Requests\Youth;

use Illuminate\Foundation\Http\FormRequest;

final class UpsertEducationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'education_level' => ['required', 'string', 'max:40'],
            'institution_name' => ['required', 'string', 'max:180'],
            'field_of_study' => ['nullable', 'string', 'max:180'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_current' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
