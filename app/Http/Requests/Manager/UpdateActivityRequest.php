<?php

namespace App\Http\Requests\Manager;

class UpdateActivityRequest extends StoreActivityRequest
{
    public function authorize(): bool
    {
        $activity = $this->route('activity');
        $organization = $this->route('organization');

        return $activity && $organization && $activity->organization_id === $organization->getKey()
            && $this->user()?->can('update', $activity);
    }
}
