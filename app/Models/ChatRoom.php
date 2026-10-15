<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatRoom extends Model
{
    use HasBinaryUuid;

    protected $fillable = ['type', 'community_id', 'name', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function community(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'community_id');
    }
}
