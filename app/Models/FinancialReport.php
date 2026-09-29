<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['program_id', 'version', 'status', 'notes', 'submitted_at', 'reviewed_at', 'reviewed_by', 'review_notes'])]
#[Hidden(['program_id', 'reviewed_by'])]
class FinancialReport extends Model
{
    use HasBinaryUuid, SoftDeletes;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_SUBMITTED = 'submitted';

    public const STATUS_UNDER_REVIEW = 'under_review';

    public const STATUS_REVISION = 'revision';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_APPROVED = 'approved';

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(FinancialItem::class)->orderBy('transaction_date')->orderBy('created_at');
    }

    protected function casts(): array
    {
        return ['version' => 'integer', 'submitted_at' => 'datetime', 'reviewed_at' => 'datetime'];
    }
}
