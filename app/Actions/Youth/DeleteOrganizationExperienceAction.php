<?php

namespace App\Actions\Youth;

use App\Models\OrganizationExperience;
use App\Models\User;
use App\Support\BinaryUuid;

final class DeleteOrganizationExperienceAction
{
    public function execute(User $user, string $experienceId): void
    {
        $user->organizationExperiences()->where('id', BinaryUuid::bytesOrFail($experienceId, OrganizationExperience::class))->firstOrFail()->delete();
    }
}
