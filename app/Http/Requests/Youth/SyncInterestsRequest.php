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
        return ['interests' => ['required', 'array', 'min:1', 'max:8'], 'interests.*' => ['required', 'distinct', 'uuid', new BinaryUuidExists('interests')]];
    }
}
