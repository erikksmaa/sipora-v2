<?php

namespace App\Http\Requests\Manager;

use App\Rules\BinaryUuidExists;
use App\Rules\ProgramBelongsToOrganization;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        $organization = $this->route('organization');

        return $organization && $this->user()?->can('manageMemberships', $organization);
    }

    public function rules(): array
    {
        return [
            'program_id' => ['nullable', 'uuid', new ProgramBelongsToOrganization($this->route('organization'))],
            'category_id' => ['required', 'uuid', new BinaryUuidExists('activity_categories')],
            'title' => ['required', 'string', 'max:220'],
            'description' => ['nullable', 'string', 'max:10000'],
            'poster' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'location_type' => ['required', Rule::in(['offline', 'online', 'hybrid'])],
            'venue_name' => ['nullable', 'required_unless:location_type,online', 'string', 'max:180'],
            'administrative_area_id' => ['nullable', 'uuid', new BinaryUuidExists('administrative_areas')],
            'address_text' => ['nullable', 'string', 'max:2000'],
            'meeting_url' => ['nullable', 'required_if:location_type,online,hybrid', 'url:http,https', 'max:2048'],
            'start_at' => ['required', 'date'],
            'end_at' => ['required', 'date', 'after:start_at'],
            'registration_open_at' => ['nullable', 'date'],
            'registration_close_at' => ['nullable', 'date', 'after_or_equal:registration_open_at', 'before_or_equal:start_at'],
            'quota' => ['nullable', 'integer', 'min:1'],
            'registration_mode' => ['required', Rule::in(['open', 'approval_required'])],
            'min_age' => ['nullable', 'integer', 'between:0,100'],
            'max_age' => ['nullable', 'integer', 'between:0,100', 'gte:min_age'],
            'requires_identity_verification' => ['nullable', 'boolean'],
            'members_only' => ['nullable', 'boolean'],
            'eligibility_notes' => ['nullable', 'string', 'max:3000'],
            'certificate_enabled' => ['nullable', 'boolean'],
        ];
    }
}
