<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['program_id', 'activity_id', 'created_by_user_id', 'log_date', 'summary', 'obstacles', 'solutions', 'progress_percent', 'status', 'submitted_at', 'reviewed_at', 'reviewed_by', 'review_notes'])]
#[Hidden(['program_id', 'activity_id', 'created_by_user_id', 'reviewed_by'])]
class ProgramLogbook extends Model
{
    use HasBinaryUuid, SoftDeletes;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_SUBMITTED = 'submitted';

    public const STATUS_REVISION = 'revision';

    public const STATUS_APPROVED = 'approved';

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function media(): HasMany
    {
        return $this->hasMany(ProgramLogbookMedia::class, 'logbook_id')->oldest();
    }

    protected function casts(): array
    {
        return ['log_date' => 'date', 'submitted_at' => 'datetime', 'reviewed_at' => 'datetime', 'progress_percent' => 'integer'];
    }
}
