<?php

namespace App\Actions\Youth;

use App\Models\User;
use App\Models\UserAchievement;
use App\Support\BinaryUuid;

final class DeleteAchievementAction
{
    public function execute(User $user, string $achievementId): void
    {
        $user->achievements()->where('id', BinaryUuid::bytesOrFail($achievementId, UserAchievement::class))->firstOrFail()->delete();
    }
}
