<?php

namespace App\Actions\Youth;

use App\Models\OrganizationExperience;
use App\Models\User;
use App\Support\BinaryUuid;

final class UpsertOrganizationExperienceAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(User $user, array $data, ?string $experienceId = null): OrganizationExperience
    {
        if ($experienceId) {
            $experience = $user->organizationExperiences()->where('id', BinaryUuid::bytesOrFail($experienceId, OrganizationExperience::class))->firstOrFail();
        } else {
            $experience = new OrganizationExperience;
            $experience->id = BinaryUuid::generate();
            $experience->user_id = $user->getKey();
        }

        $experience->fill([
            'organization_name' => $data['organization_name'],
            'role_title' => $data['role_title'] ?? null,
            'start_date' => $data['start_date'] ?? null,
            'end_date' => ! empty($data['is_current']) ? null : ($data['end_date'] ?? null),
            'is_current' => (bool) ($data['is_current'] ?? false),
            'description' => $data['description'] ?? null,
        ]);

        $experience->save();

        return $experience;
    }
}
