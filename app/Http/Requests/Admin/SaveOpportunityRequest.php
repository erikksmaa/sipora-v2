<?php

namespace App\Http\Requests\Admin;

use App\Rules\BinaryUuidExists;
use Illuminate\Foundation\Http\FormRequest;

final class SaveOpportunityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin') === true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'uuid', new BinaryUuidExists('opportunity_categories')],
            'organization_id' => ['nullable', 'uuid', new BinaryUuidExists('organizations')],
            'administrative_area_id' => ['nullable', 'uuid', new BinaryUuidExists('administrative_areas')],
            'title' => ['required', 'string', 'max:220'],
            'provider_name' => ['required', 'string', 'max:180'],
            'description' => ['required', 'string', 'max:20000'],
            'location_text' => ['nullable', 'string', 'max:220'],
            'external_url' => ['required', 'url', 'max:2000', 'starts_with:http://,https://'],
            'deadline_at' => ['nullable', 'date'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ];
    }
}
