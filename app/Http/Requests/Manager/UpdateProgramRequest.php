<?php

namespace App\Http\Requests\Manager;

class UpdateProgramRequest extends StoreProgramRequest
{
    public function authorize(): bool
    {
        $program = $this->route('program');
        $organization = $this->route('organization');

        return $program && $organization && $program->organization_id === $organization->getKey()
            && $this->user()?->can('update', $program);
    }
}
