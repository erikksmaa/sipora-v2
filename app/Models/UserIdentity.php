<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['user_id', 'verification_status', 'verification_method', 'verified_at', 'verified_by'])]
#[Hidden(['national_id_hash', 'national_id_ciphertext'])]
class UserIdentity extends Model
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

    public function verifications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserIdentityVerification::class, 'user_identity_id')->latest('submitted_at');
    }

    public function latestVerification(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(UserIdentityVerification::class, 'user_identity_id')->latestOfMany('submitted_at');
    }

    protected function casts(): array
    {
        return ['verified_at' => 'datetime'];
    }
}
