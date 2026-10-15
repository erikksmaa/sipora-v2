<?php

namespace App\Services\Program;

use Illuminate\Validation\Rule;

final class ProgramIntakeRules
{
    public static function rules(): array
    {
        return [
            'registration_enabled' => ['sometimes', 'boolean'],
            'selection_mode' => ['required_if:registration_enabled,1', 'sometimes', Rule::in(['open', 'review'])],
            'quota' => ['nullable', 'integer', 'min:1', 'max:4294967295'],
            'registration_open_at' => ['nullable', 'date'],
            'registration_close_at' => ['nullable', 'date', Rule::when(fn ($input) => filled($input->registration_open_at), ['after_or_equal:registration_open_at'])],
        ];
    }
}
