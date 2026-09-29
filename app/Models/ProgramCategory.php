<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'slug', 'description'])]
class ProgramCategory extends Model
{
    use HasBinaryUuid, SoftDeletes;

    public function programs(): HasMany
    {
        return $this->hasMany(Program::class, 'category_id');
    }
}
