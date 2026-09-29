<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['logbook_id', 'uploaded_by', 'file_path', 'caption'])]
#[Hidden(['logbook_id', 'uploaded_by', 'file_path'])]
class ProgramLogbookMedia extends Model
{
    use HasBinaryUuid, SoftDeletes;

    protected $table = 'program_logbook_media';

    public function logbook(): BelongsTo
    {
        return $this->belongsTo(ProgramLogbook::class, 'logbook_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
