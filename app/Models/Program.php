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

#[Fillable(['organization_id', 'category_id', 'created_by_user_id', 'title', 'slug', 'description', 'objectives', 'start_date', 'end_date', 'execution_status'])]
#[Hidden(['organization_id', 'category_id', 'created_by_user_id'])]
class Program extends Model
{
    use HasBinaryUuid, SoftDeletes;

    public const STATUS_PLANNED = 'planned';

    public const STATUS_RUNNING = 'running';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProgramCategory::class, 'category_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class)->orderBy('start_at');
    }

    public function proposals(): HasMany
    {
        return $this->hasMany(ProgramProposal::class)->orderByDesc('version');
    }

    public function latestProposal(): HasOne
    {
        return $this->hasOne(ProgramProposal::class)->ofMany('version', 'max');
    }

    public function proposalCompositionLocked(): bool
    {
        $status = $this->relationLoaded('latestProposal') ? $this->latestProposal?->status : $this->latestProposal()->value('status');

        return in_array($status, [ProgramProposal::STATUS_SUBMITTED, ProgramProposal::STATUS_UNDER_REVIEW], true);
    }

    protected function casts(): array
    {
        return ['start_date' => 'date', 'end_date' => 'date'];
    }
}
