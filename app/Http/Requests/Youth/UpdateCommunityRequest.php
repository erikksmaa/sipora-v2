<?php

namespace App\Http\Requests\Youth;

use App\Models\Organization;

final class UpdateCommunityRequest extends StoreCommunityRequest
{
    public function authorize(): bool
    {
        $organization = $this->route('organization');

        return $organization instanceof Organization
            && $this->user()?->can('update', $organization) === true;
    }
}
