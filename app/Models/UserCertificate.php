<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use LogicException;

#[Fillable(['user_id', 'participation_id', 'source_type', 'name', 'issuer_name', 'certificate_number', 'verification_code', 'issued_at', 'expires_at', 'file_path', 'external_url', 'verification_status'])]
#[Hidden(['user_id', 'participation_id', 'file_path'])]
class UserCertificate extends Model
{
    use HasBinaryUuid, SoftDeletes;

    public const SOURCE_SIPORA = 'sipora';

    public const SOURCE_EXTERNAL = 'external';

    public const STATUS_VERIFIED = 'verified';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function participation(): BelongsTo
    {
        return $this->belongsTo(ActivityParticipation::class, 'participation_id');
    }

    protected function casts(): array
    {
        return ['issued_at' => 'date', 'expires_at' => 'date'];
    }

    protected static function booted(): void
    {
        static::updating(function (self $certificate): void {
            if ($certificate->getOriginal('source_type') === self::SOURCE_SIPORA
                && ($certificate->isDirty('certificate_number') || $certificate->isDirty('verification_code'))) {
                throw new LogicException('Nomor dan kode verifikasi sertifikat SIPORA tidak dapat diubah setelah diterbitkan.');
            }
        });
    }
}
