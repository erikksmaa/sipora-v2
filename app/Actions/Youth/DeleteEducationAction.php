<?php

namespace App\Actions\Youth;

use App\Models\User;
use App\Support\BinaryUuid;

final class DeleteEducationAction
{
    public function execute(User $user, string $educationId): void
    {
        $user->educations()->where('id', BinaryUuid::bytes($educationId))->firstOrFail()->delete();
    }
}
