<?php

namespace App\Http\Requests\Youth;

use App\Rules\BinaryUuidExists;
use App\Rules\PemalangAddress;
use App\Rules\UniqueNik;
use App\Rules\YouthAge;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateBiodataRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $normalized = [];
        if (! $this->filled('gender') && in_array($this->user()?->profile?->gender, ['other', 'prefer_not_to_say'], true)) {
            $normalized['gender'] = $this->user()->profile->gender;
        }
        foreach (['instagram', 'facebook', 'linkedin'] as $field) {
            if (is_string($this->input($field))) {
                $normalized[$field] = trim($this->input($field));
            }
        }
        if (isset($normalized['instagram'])) {
            $normalized['instagram'] = ltrim($normalized['instagram'], '@');
        }
        $this->merge($normalized);
    }

    public function authorize(): bool
    {
        return $this->user()?->hasRole('youth') === true;
    }

    public function rules(): array
    {
        return [
            'national_id' => [$this->user()?->identity?->nationalId() ? 'nullable' : 'required', 'bail', 'string', 'regex:/^[0-9]{16}$/D', new UniqueNik($this->user()?->getKey())],
            'village_id' => [$this->user()?->identity?->nationalId() ? 'nullable' : 'required', 'bail', 'uuid', new PemalangAddress($this->input('administrative_area_id'))],
            'interests' => [$this->user()?->identity?->nationalId() ? 'sometimes' : 'required', 'array', 'min:1', 'max:5'],
            'interests.*' => ['bail', 'uuid', 'distinct', new BinaryUuidExists('interests')],
            'full_name' => ['required', 'string', 'min:2', 'max:160'],
            'birth_place' => ['nullable', 'string', 'max:120'],
            'birth_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:1900-01-01', 'before_or_equal:today', new YouthAge],
            'gender' => [$this->user()?->identity?->nationalId() ? 'nullable' : 'required', Rule::in(['male', 'female', $this->user()?->profile?->gender])],
            'phone' => ['required', 'regex:/^\+?[0-9][0-9\s-]{7,30}$/', 'max:32'],
            'instagram' => ['nullable', 'regex:/^[A-Za-z0-9._]{1,30}$/', 'max:30'],
            'facebook' => ['nullable', 'regex:/^(?:https:\/\/(?:www\.)?facebook\.com\/[A-Za-z0-9._-]+\/?|[A-Za-z0-9._-]{3,80})$/', 'max:255'],
            'linkedin' => ['nullable', 'regex:/^(?:https:\/\/(?:www\.)?linkedin\.com\/in\/[A-Za-z0-9-]+\/?|[A-Za-z0-9-]{3,100})$/', 'max:255'],
            'administrative_area_id' => ['required', 'uuid', new BinaryUuidExists('administrative_areas')],
            'address_line' => ['nullable', 'string', 'max:500'],
            'rt' => ['nullable', 'digits_between:1,4'],
            'rw' => ['nullable', 'digits_between:1,4'],
            'postal_code' => ['nullable', 'regex:/^[0-9]{5,10}$/'],
        ];
    }
}
