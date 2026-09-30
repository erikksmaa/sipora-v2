<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['program_id', 'verifier_id', 'decision', 'evaluation_notes', 'evaluated_at'])]
#[Hidden(['program_id', 'verifier_id'])]
class ProgramEvaluation extends Model
{
    use HasBinaryUuid, SoftDeletes;

    public const DECISION_APPROVED = 'approved';

    public const DECISION_REVISION = 'revision';

    public const DECISION_REJECTED = 'rejected';

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verifier_id');
    }

    protected function casts(): array
    {
        return ['evaluated_at' => 'datetime'];
    }
}
