<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['user_id', 'notification_type', 'title', 'body', 'data', 'read_at'])]
#[Hidden(['user_id'])]
class UserNotification extends Model
{
    use HasBinaryUuid, SoftDeletes;

    protected $table = 'notifications';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return ['data' => 'array', 'read_at' => 'datetime'];
    }
}
