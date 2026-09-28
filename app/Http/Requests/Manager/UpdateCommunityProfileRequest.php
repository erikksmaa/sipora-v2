<?php

namespace App\Http\Requests\Manager;

use App\Models\Organization;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateCommunityProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        $organization = $this->route('organization');

        return $organization instanceof Organization
            && $this->user()?->can('manageMemberships', $organization) === true;
    }

    public function rules(): array
    {
        return [
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
