<?php

namespace App\Actions\Youth;

use App\Models\User;
use App\Models\UserSkill;
use App\Support\BinaryUuid;
use Illuminate\Support\Facades\DB;

final class SyncUserSkillsAction
{
    /**
     * @param  array<int, string|array{skill_id: string, proficiency_level?: ?string}>  $skills
     */
    public function execute(User $user, array $skills): void
    {
        DB::transaction(function () use ($user, $skills): void {
            $parsed = [];
            foreach ($skills as $item) {
                if (is_array($item)) {
                    $skillId = $item['skill_id'];
                    $level = $item['proficiency_level'] ?? null;
                } else {
                    $skillId = (string) $item;
                    $level = null;
                }
                $bytes = BinaryUuid::bytes($skillId);
                $parsed[$bytes] = $level;
            }

            $binaryIds = array_keys($parsed);
            UserSkill::where('user_id', $user->getKey())->whereNotIn('skill_id', $binaryIds)->delete();

            foreach ($parsed as $binarySkillId => $level) {
                $attributes = ['deleted_at' => null, 'is_self_reported' => true];
                if ($level !== null) {
                    $attributes['proficiency_level'] = $level;
                }
                UserSkill::withTrashed()->updateOrCreate(
                    ['user_id' => $user->getKey(), 'skill_id' => $binarySkillId],
                    $attributes
                );
            }
        });
    }
}
