<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['user_id', 'is_profile_public', 'show_photo', 'show_bio', 'show_interests'])]
class UserProfileVisibility extends Model
{
    use HasBinaryUuid, SoftDeletes;

    protected $table = 'user_profile_visibility';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return ['is_profile_public' => 'boolean', 'show_photo' => 'boolean', 'show_bio' => 'boolean', 'show_interests' => 'boolean'];
    }
}
