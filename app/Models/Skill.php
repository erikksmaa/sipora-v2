<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'slug'])]
class Skill extends Model
{
    use HasBinaryUuid, SoftDeletes;

    public function userSkills(): HasMany
    {
        return $this->hasMany(UserSkill::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_skills')->withPivot(['proficiency_level', 'is_self_reported'])->withTimestamps();
    }
}
