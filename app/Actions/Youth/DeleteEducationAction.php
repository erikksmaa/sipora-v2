<?php

namespace App\Actions\Youth;

use App\Models\User;
use App\Models\UserEducation;
use App\Support\BinaryUuid;

final class DeleteEducationAction
{
    public function execute(User $user, string $educationId): void
    {
        $user->educations()->where('id', BinaryUuid::bytesOrFail($educationId, UserEducation::class))->firstOrFail()->delete();
    }
}
