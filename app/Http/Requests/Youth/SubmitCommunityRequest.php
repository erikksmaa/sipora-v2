<?php

namespace App\Http\Requests\Youth;

use App\Models\Organization;
use Illuminate\Foundation\Http\FormRequest;

final class SubmitCommunityRequest extends FormRequest
{
    public function authorize(): bool
    {
        $organization = $this->route('organization');

        return $organization instanceof Organization
            && $this->user()?->can('submit', $organization) === true;
    }

    public function rules(): array
    {
        return ['submission_notes' => ['nullable', 'string', 'max:2000']];
    }
}
