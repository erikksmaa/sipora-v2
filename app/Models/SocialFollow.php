<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Model;

class SocialFollow extends Model
{
    use HasBinaryUuid;

    protected $fillable = ['user_id', 'youth_id', 'organization_id'];
}
