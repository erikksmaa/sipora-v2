<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['program_id', 'version', 'status', 'requested_budget', 'proposal_document_path', 'submitted_at', 'reviewed_at', 'reviewed_by', 'review_notes'])]
#[Hidden(['program_id', 'reviewed_by', 'proposal_document_path'])]
class ProgramProposal extends Model
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

    public function submissionActivity(): MorphOne
    {
        return $this->morphOne(AuditEntry::class, 'subject')
            ->whereIn('event', ['program_proposal_submitted', 'program_proposal_resubmitted'])
            ->latestOfMany('created_at');
    }

    public function isReviewLocked(): bool
    {
        return in_array($this->status, [self::STATUS_SUBMITTED, self::STATUS_UNDER_REVIEW], true);
    }

    protected function casts(): array
    {
        return ['requested_budget' => 'decimal:2', 'submitted_at' => 'datetime', 'reviewed_at' => 'datetime'];
    }
}
