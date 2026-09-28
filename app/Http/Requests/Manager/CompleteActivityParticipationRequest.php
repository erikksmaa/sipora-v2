<?php

namespace App\Http\Requests\Manager;

use App\Models\ActivityParticipation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class CompleteActivityParticipationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $activity = $this->route('activity');
        $organization = $this->route('organization');

        return $activity && $organization && $activity->organization_id === $organization->getKey()
            && $this->user()?->can('completeParticipation', $activity);
    }

    public function rules(): array
    {
        return [
            'decision' => ['required', Rule::in([
                ActivityParticipation::COMPLETION_COMPLETED,
                ActivityParticipation::COMPLETION_NO_SHOW,
            ])],
        ];
    }
}
