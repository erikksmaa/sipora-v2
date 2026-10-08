<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SocialPost extends Model
{
    use HasBinaryUuid;

    protected $fillable = ['author_id', 'organization_id', 'body', 'image_path', 'visibility', 'is_hidden', 'is_removed'];

    protected function casts(): array
    {
        return ['is_hidden' => 'boolean', 'is_removed' => 'boolean'];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(SocialComment::class, 'post_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(SocialLike::class, 'post_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_hidden', false)->where('is_removed', false)->where('visibility', 'public')
            ->where(fn (Builder $q) => $q->whereNull('organization_id')->orWhereHas('organization', fn (Builder $o) => $o->where('review_status', 'approved')->where('operational_status', 'active')));
    }
}
