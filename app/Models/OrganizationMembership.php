<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['organization_id', 'user_id', 'access_role', 'position_title', 'membership_status', 'requested_at', 'approved_at', 'approved_by', 'ended_at'])]
#[Hidden(['organization_id', 'user_id', 'approved_by'])]
class OrganizationMembership extends Model
{
    use HasBinaryUuid, SoftDeletes;

    public const ROLE_LEADER = 'leader';

    public const ROLE_MANAGER = 'manager';

    public const ROLE_MEMBER = 'member';

    public const STATUS_ACTIVE = 'active';

    public const STATUS_LEFT = 'left';

    public const STATUS_PENDING = 'pending';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_REMOVED = 'removed';

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    protected function casts(): array
    {
        return ['requested_at' => 'datetime', 'approved_at' => 'datetime', 'ended_at' => 'datetime'];
    }
}
