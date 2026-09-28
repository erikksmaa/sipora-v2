<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'slug', 'description'])]
class ActivityCategory extends Model
{
    use HasBinaryUuid, SoftDeletes;

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'category_id');
    }
}
