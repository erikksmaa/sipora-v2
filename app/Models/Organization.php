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

#[Fillable([
    'created_by_user_id', 'category_id', 'administrative_area_id', 'name', 'slug',
    'description', 'logo_path', 'contact_email', 'contact_phone', 'website_url',
    'address_text', 'social_links', 'review_status', 'operational_status',
    'approved_at', 'approved_by',
])]
#[Hidden(['created_by_user_id', 'category_id', 'administrative_area_id', 'logo_path', 'approved_by'])]
class Organization extends Model
{
    use HasBinaryUuid, SoftDeletes;

    public const OPERATIONAL_ACTIVE = 'active';

    public const OPERATIONAL_INACTIVE = 'inactive';

    public const REVIEW_APPROVED = 'approved';

    public const REVIEW_DRAFT = 'draft';

    public const REVIEW_PENDING = 'pending_review';

    public const REVIEW_REJECTED = 'rejected';

    public const REVIEW_REVISION = 'revision';

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(OrganizationCategory::class, 'category_id');
    }

    public function administrativeArea(): BelongsTo
    {
        return $this->belongsTo(AdministrativeArea::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function verificationRequests(): HasMany
    {
        return $this->hasMany(OrganizationVerificationRequest::class);
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(OrganizationMembership::class);
    }

    public function activeMemberships(): HasMany
    {
        return $this->hasMany(OrganizationMembership::class)->where('membership_status', OrganizationMembership::STATUS_ACTIVE);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function latestVerificationRequest(): HasOne
    {
        return $this->hasOne(OrganizationVerificationRequest::class)->latestOfMany('submitted_at');
    }

    protected function casts(): array
    {
        return ['social_links' => 'array', 'approved_at' => 'datetime'];
    }
}
