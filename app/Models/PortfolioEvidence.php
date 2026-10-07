<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'record_type', 'record_id', 'original_name', 'mime_type', 'size_bytes', 'file_path'])]
#[Hidden(['user_id', 'record_id', 'file_path'])]
final class PortfolioEvidence extends Model
{
    use HasBinaryUuid;

    protected $table = 'portfolio_evidences';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
