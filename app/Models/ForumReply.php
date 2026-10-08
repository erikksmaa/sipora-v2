<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ForumReply extends Model
{
    use HasBinaryUuid;

    protected $fillable = ['thread_id', 'author_id', 'body', 'is_hidden'];

    protected function casts(): array { return ['is_hidden' => 'boolean']; }
    public function thread(): BelongsTo { return $this->belongsTo(ForumThread::class, 'thread_id'); }
    public function author(): BelongsTo { return $this->belongsTo(User::class, 'author_id'); }
}
