<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'user_identity_id',
    'document_type',
    'document_number_hash',
    'document_number_ciphertext',
    'document_path',
    'document_sha256',
    'status',
    'submitted_at',
    'reviewed_at',
    'reviewed_by',
    'review_notes',
    'metadata',
])]
#[Hidden([
    'document_number_hash',
    'document_number_ciphertext',
    'document_sha256',
])]
class UserIdentityVerification extends Model
{
    use HasBinaryUuid, SoftDeletes;

    public function userIdentity(): BelongsTo
    {
        return $this->belongsTo(UserIdentity::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'metadata' => 'array',
        ];
    }
}
