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
        return ['is_profile_public' => ['required', 'boolean'], 'show_photo' => ['required', 'boolean'], 'show_bio' => ['required', 'boolean'], 'show_interests' => ['required', 'boolean']];
    }
}
