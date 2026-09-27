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
            'full_name' => ['required', 'string', 'min:2', 'max:160'],
            'birth_place' => ['nullable', 'string', 'max:120'],
            'birth_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:1900-01-01', 'before_or_equal:today'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other', 'prefer_not_to_say'])],
            'phone' => ['nullable', 'regex:/^\+?[0-9][0-9\s-]{7,30}$/', 'max:32'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'occupation_status' => ['nullable', Rule::in(['student', 'university_student', 'worker', 'entrepreneur', 'unemployed', 'other'])],
            'occupation_title' => ['nullable', 'string', 'max:160'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
