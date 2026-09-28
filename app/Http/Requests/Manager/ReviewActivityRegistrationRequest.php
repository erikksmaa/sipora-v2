<?php

namespace App\Http\Requests\Manager;

use App\Models\ActivityParticipation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class ReviewActivityRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $activity = $this->route('activity');
        $organization = $this->route('organization');

        return $activity && $organization && $activity->organization_id === $organization->getKey()
            && $this->user()?->can('view', $activity);
    }

    public function rules(): array
    {
        return [
            'decision' => ['required', Rule::in([ActivityParticipation::REGISTRATION_ACCEPTED, ActivityParticipation::REGISTRATION_REJECTED])],
            'notes' => ['nullable', 'required_if:decision,'.ActivityParticipation::REGISTRATION_REJECTED, 'string', 'max:3000'],
        ];
    }
}
