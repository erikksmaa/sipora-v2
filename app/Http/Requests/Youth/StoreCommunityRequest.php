<?php

namespace App\Http\Requests\Youth;

use App\Rules\BinaryUuidExists;
use Illuminate\Foundation\Http\FormRequest;

class StoreCommunityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('youth') === true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'uuid', new BinaryUuidExists('organization_categories')],
            'administrative_area_id' => ['nullable', 'uuid', new BinaryUuidExists('administrative_areas')],
            'name' => ['required', 'string', 'max:180'],
            'description' => ['nullable', 'string', 'max:5000'],
            'contact_email' => ['nullable', 'email:rfc', 'max:254'],
            'contact_phone' => ['nullable', 'string', 'max:32', 'regex:/^[0-9+().\-\s]+$/'],
            'website_url' => ['nullable', 'url:http,https', 'max:2048'],
            'address_text' => ['nullable', 'string', 'max:2000'],
            'social_instagram' => ['nullable', 'url:http,https', 'max:2048'],
            'social_facebook' => ['nullable', 'url:http,https', 'max:2048'],
            'social_tiktok' => ['nullable', 'url:http,https', 'max:2048'],
            'logo' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
