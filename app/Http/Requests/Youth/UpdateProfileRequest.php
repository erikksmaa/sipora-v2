<?php

namespace App\Http\Requests\Youth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('youth') === true;
    }

    public function rules(): array
    {
        return [
            'bio' => ['required', 'string', 'max:2000'],
            'occupation_status' => ['required', Rule::in(['student', 'university_student', 'worker', 'entrepreneur', 'unemployed', 'other'])],
            'occupation_title' => ['nullable', 'string', 'max:160'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
