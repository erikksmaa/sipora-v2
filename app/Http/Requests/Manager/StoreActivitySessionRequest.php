<?php

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreActivitySessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $activity = $this->route('activity');
        $organization = $this->route('organization');

        return $activity && $organization && $activity->organization_id === $organization->getKey()
            && $this->user()?->can('update', $activity);
    }

    public function rules(): array
    {
        $activity = $this->route('activity');
        $session = $this->route('session');

        return [
            'session_number' => ['required', 'integer', 'min:1', Rule::unique('activity_sessions')->where('activity_id', $activity->getKey())->ignore($session?->getKey())],
            'title' => ['required', 'string', 'max:180'],
            'description' => ['nullable', 'string'],
            'start_at' => ['required', 'date'],
            'end_at' => ['required', 'date', 'after:start_at'],
            'venue_name' => ['nullable', 'string', 'max:180'],
            'address_text' => ['nullable', 'string'],
            'meeting_url' => ['nullable', 'url:http,https'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
