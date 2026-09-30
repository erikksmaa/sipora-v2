<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['category_id', 'organization_id', 'created_by_user_id', 'title', 'slug', 'provider_name', 'description', 'administrative_area_id', 'location_text', 'external_url', 'deadline_at', 'starts_at', 'ends_at', 'publication_status', 'published_at'])]
#[Hidden(['category_id', 'organization_id', 'created_by_user_id', 'administrative_area_id'])]
class Opportunity extends Model
{
    use HasBinaryUuid, SoftDeletes;

    public const STATUS_ARCHIVED = 'archived';

    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(OpportunityCategory::class, 'category_id');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function administrativeArea(): BelongsTo
    {
        return $this->belongsTo(AdministrativeArea::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(OpportunityBookmark::class);
    }

    public function isPublic(): bool
    {
        return $this->publication_status === self::STATUS_PUBLISHED && $this->published_at?->lte(now()) === true;
    }

    protected function casts(): array
    {
        return ['deadline_at' => 'datetime', 'starts_at' => 'datetime', 'ends_at' => 'datetime', 'published_at' => 'datetime'];
    }
}
