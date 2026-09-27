<?php

namespace App\Actions\Youth;

use App\Models\User;
use App\Models\UserEducation;
use App\Support\BinaryUuid;

final class UpsertEducationAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(User $user, array $data, ?string $educationId = null): UserEducation
    {
        if ($educationId) {
            $education = $user->educations()->where('id', BinaryUuid::bytesOrFail($educationId, UserEducation::class))->firstOrFail();
        } else {
            $education = new UserEducation;
            $education->id = BinaryUuid::generate();
            $education->user_id = $user->getKey();
        }

        $education->fill([
            'education_level' => $data['education_level'],
            'institution_name' => $data['institution_name'],
            'field_of_study' => $data['field_of_study'] ?? null,
            'start_date' => $data['start_date'] ?? null,
            'end_date' => ! empty($data['is_current']) ? null : ($data['end_date'] ?? null),
            'is_current' => (bool) ($data['is_current'] ?? false),
            'description' => $data['description'] ?? null,
        ]);

        $education->save();

        return $education;
    }
}
