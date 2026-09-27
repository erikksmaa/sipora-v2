<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['user_id', 'public_slug', 'full_name', 'birth_place', 'birth_date', 'gender', 'phone', 'bio', 'occupation_status', 'occupation_title', 'profile_photo_path'])]
class UserProfile extends Model
{
    use HasBinaryUuid, SoftDeletes;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return ['birth_date' => 'date'];
    }
}
