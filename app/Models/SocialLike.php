<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Model;

class SocialLike extends Model
{
    use HasBinaryUuid;

    protected $fillable = ['post_id', 'user_id'];
}
