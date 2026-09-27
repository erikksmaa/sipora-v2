<?php

namespace App\Actions\Youth;

use App\Models\User;
use App\Models\UserAchievement;
use App\Support\BinaryUuid;

final class UpsertAchievementAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(User $user, array $data, ?string $achievementId = null): UserAchievement
    {
        if ($achievementId) {
            $achievement = $user->achievements()->where('id', BinaryUuid::bytes($achievementId))->firstOrFail();
        } else {
            $achievement = new UserAchievement;
            $achievement->id = BinaryUuid::generate();
            $achievement->user_id = $user->getKey();
            $achievement->verification_status = 'self_reported';
        }

        $achievement->fill([
            'title' => $data['title'],
            'issuer_name' => $data['issuer_name'] ?? null,
            'achievement_date' => $data['achievement_date'] ?? null,
            'description' => $data['description'] ?? null,
            'evidence_path' => $data['evidence_path'] ?? null,
        ]);

        $achievement->save();

        return $achievement;
    }
}
