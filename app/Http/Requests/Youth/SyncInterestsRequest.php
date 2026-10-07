<?php

namespace App\Http\Requests\Youth;

use App\Rules\BinaryUuidExists;
use Illuminate\Foundation\Http\FormRequest;

class SyncInterestsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('youth') === true;
    }

    public function rules(): array
    {
        return ['interests' => ['nullable', 'array', 'max:8'], 'interests.*' => ['required', 'distinct', 'uuid', new BinaryUuidExists('interests')], 'other_selected' => ['sometimes', 'boolean'], 'custom_interest' => ['required_if:other_selected,1', 'nullable', 'string', 'min:2', 'max:120']];
    }
}
