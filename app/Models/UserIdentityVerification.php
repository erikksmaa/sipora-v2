<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

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
    'user_identity_id',
    'document_number_hash',
    'document_number_ciphertext',
    'document_path',
    'document_sha256',
    'reviewed_by',
])]
class UserIdentityVerification extends Model
{
    use HasBinaryUuid, SoftDeletes;

    public const STATUS_PENDING = 'pending';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_REVISION = 'revision';

    public const STATUS_VERIFIED = 'verified';

    public function documentNumber(): ?string
    {
        return $this->document_number_ciphertext
            ? Crypt::decryptString($this->document_number_ciphertext)
            : null;
    }

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
