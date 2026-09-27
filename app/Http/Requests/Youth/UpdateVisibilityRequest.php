<?php

namespace App\Http\Requests\Youth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVisibilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('youth') === true;
    }

    public function rules(): array
    {
        return collect([
            'is_profile_public', 'show_photo', 'show_bio', 'show_interests',
            'show_skills', 'show_education', 'show_organization_experience', 'show_achievements',
        ])->mapWithKeys(fn (string $field): array => [$field => ['required', 'boolean']])->all();
    }
}
