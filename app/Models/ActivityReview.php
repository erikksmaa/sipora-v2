<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['activity_id', 'reviewer_id', 'decision', 'review_notes', 'reviewed_at'])]
#[Hidden(['activity_id', 'reviewer_id'])]
class ActivityReview extends Model
{
    use HasBinaryUuid, SoftDeletes;

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    protected function casts(): array
    {
        return ['reviewed_at' => 'datetime'];
    }
}
