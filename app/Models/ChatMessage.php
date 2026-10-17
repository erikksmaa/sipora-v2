<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMessage extends Model
{
    use HasBinaryUuid, SoftDeletes;

    protected $fillable = ['chat_room_id', 'user_id', 'client_nonce', 'message'];

    protected $hidden = ['user_id', 'client_nonce', 'chat_room_id'];

    public function room(): BelongsTo
    {
        return $this->belongsTo(ChatRoom::class, 'chat_room_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(ChatMessageReport::class, 'chat_message_id');
    }
}
