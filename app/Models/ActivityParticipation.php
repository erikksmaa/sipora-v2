<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['activity_id', 'user_id', 'activity_role', 'registration_status', 'completion_status', 'registration_notes', 'requested_at', 'reviewed_at', 'reviewed_by', 'completed_at'])]
#[Hidden(['activity_id', 'user_id', 'reviewed_by'])]
class ActivityParticipation extends Model
{
    use HasBinaryUuid, SoftDeletes;

    public const REGISTRATION_ACCEPTED = 'accepted';

    public const REGISTRATION_CANCELLED = 'cancelled';

    public const REGISTRATION_PENDING = 'pending';

    public const REGISTRATION_REJECTED = 'rejected';

    public const COMPLETION_PENDING = 'pending';

    public const COMPLETION_COMPLETED = 'completed';

    public const COMPLETION_NO_SHOW = 'no_show';

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(ActivityAttendance::class, 'participation_id');
    }

    public function certificate(): HasOne
    {
        return $this->hasOne(UserCertificate::class, 'participation_id');
    }

    protected function casts(): array
    {
        return ['requested_at' => 'datetime', 'reviewed_at' => 'datetime', 'completed_at' => 'datetime'];
    }
}
