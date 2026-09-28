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

#[Fillable(['organization_id', 'program_id', 'category_id', 'created_by_user_id', 'title', 'slug', 'description', 'poster_path', 'location_type', 'venue_name', 'administrative_area_id', 'address_text', 'meeting_url', 'start_at', 'end_at', 'registration_open_at', 'registration_close_at', 'quota', 'registration_mode', 'min_age', 'max_age', 'requires_identity_verification', 'members_only', 'eligibility_notes', 'certificate_enabled', 'review_status', 'publication_status', 'execution_status', 'published_at'])]
#[Hidden(['organization_id', 'program_id', 'category_id', 'created_by_user_id', 'poster_path', 'administrative_area_id'])]
class Activity extends Model
{
    use HasBinaryUuid, SoftDeletes;

    public const PUBLICATION_ARCHIVED = 'archived';

    public const PUBLICATION_PUBLISHED = 'published';

    public const PUBLICATION_UNPUBLISHED = 'unpublished';

    public const REVIEW_APPROVED = 'approved';

    public const REVIEW_DRAFT = 'draft';

    public const REVIEW_PENDING = 'pending_review';

    public const REVIEW_REJECTED = 'rejected';

    public const REVIEW_REVISION = 'revision';

    public const EXECUTION_SCHEDULED = 'scheduled';

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ActivityCategory::class, 'category_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function administrativeArea(): BelongsTo
    {
        return $this->belongsTo(AdministrativeArea::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ActivityReview::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(ActivitySession::class)->orderBy('session_number');
    }

    public function participations(): HasMany
    {
        return $this->hasMany(ActivityParticipation::class);
    }

    public function latestReview(): HasOne
    {
        return $this->hasOne(ActivityReview::class)->latestOfMany('reviewed_at');
    }

    protected function casts(): array
    {
        return ['start_at' => 'datetime', 'end_at' => 'datetime', 'registration_open_at' => 'datetime', 'registration_close_at' => 'datetime', 'requires_identity_verification' => 'boolean', 'members_only' => 'boolean', 'certificate_enabled' => 'boolean', 'published_at' => 'datetime'];
    }
}
