<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['activity_id', 'session_number', 'title', 'description', 'start_at', 'end_at', 'venue_name', 'address_text', 'meeting_url', 'notes'])]
#[Hidden(['activity_id'])]
class ActivitySession extends Model
{
    use HasBinaryUuid, SoftDeletes;

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(ActivityAttendance::class);
    }

    protected function casts(): array
    {
        return ['start_at' => 'datetime', 'end_at' => 'datetime'];
    }
}
