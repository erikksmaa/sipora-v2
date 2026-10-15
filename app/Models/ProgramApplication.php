<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramApplication extends Model
{
    use HasBinaryUuid;

    public const LABELS = ['submitted' => 'Diajukan', 'under_review' => 'Ditinjau', 'accepted' => 'Diterima', 'waitlisted' => 'Daftar Tunggu', 'rejected' => 'Tidak Lolos', 'withdrawn' => 'Mengundurkan Diri'];

    protected $fillable = ['program_id', 'user_id', 'status', 'review_notes', 'reviewed_by', 'reviewed_at'];

    protected $hidden = ['program_id', 'user_id', 'reviewed_by'];

    protected function casts(): array
    {
        return ['reviewed_at' => 'datetime'];
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
