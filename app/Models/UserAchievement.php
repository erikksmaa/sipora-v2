<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'user_id',
    'title',
    'issuer_name',
    'achievement_date',
    'description',
    'evidence_path',
    'verification_status',
    'verified_by',
    'verified_at',
])]
#[Hidden(['user_id', 'evidence_path', 'verified_by'])]
class UserAchievement extends Model
{
    use HasBinaryUuid, SoftDeletes;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    protected function casts(): array
    {
        return [
            'achievement_date' => 'date',
            'verified_at' => 'datetime',
        ];
    }
}
