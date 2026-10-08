<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Model;

class ForumReaction extends Model
{
    use HasBinaryUuid;
    protected $fillable = ['thread_id', 'user_id'];
}
