<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ForumReport extends Model
{
    use HasBinaryUuid;
    protected $fillable = ['thread_id', 'reply_id', 'reporter_id', 'reason', 'details', 'status', 'resolved_by', 'resolved_at'];
    protected function casts(): array { return ['resolved_at' => 'datetime']; }
    public function thread(): BelongsTo { return $this->belongsTo(ForumThread::class, 'thread_id'); }
    public function reply(): BelongsTo { return $this->belongsTo(ForumReply::class, 'reply_id'); }
    public function reporter(): BelongsTo { return $this->belongsTo(User::class, 'reporter_id'); }
}
