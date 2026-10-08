<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumThread extends Model
{
    use HasBinaryUuid;

    public const ACTIVE = 'active';
    public const LOCKED = 'locked';
    public const HIDDEN = 'hidden';

    protected $fillable = ['category_id', 'author_id', 'title', 'body', 'status'];

    public function category(): BelongsTo { return $this->belongsTo(ForumCategory::class, 'category_id'); }
    public function author(): BelongsTo { return $this->belongsTo(User::class, 'author_id'); }
    public function replies(): HasMany { return $this->hasMany(ForumReply::class, 'thread_id'); }
    public function reactions(): HasMany { return $this->hasMany(ForumReaction::class, 'thread_id'); }
    public function reports(): HasMany { return $this->hasMany(ForumReport::class, 'thread_id'); }
}
