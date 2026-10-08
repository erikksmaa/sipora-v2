<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialReport extends Model
{
    use HasBinaryUuid;

    protected $fillable = ['post_id', 'comment_id', 'reporter_id', 'reason', 'details', 'status', 'resolved_by', 'resolved_at'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(SocialPost::class);
    }

    public function comment(): BelongsTo
    {
        return $this->belongsTo(SocialComment::class);
    }
}
