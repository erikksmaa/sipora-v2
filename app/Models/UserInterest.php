<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserInterest extends Model
{
    use HasBinaryUuid, SoftDeletes;

    protected $table = 'user_interests';

    protected $guarded = [];
}
