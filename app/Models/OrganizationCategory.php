<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'slug', 'description'])]
class OrganizationCategory extends Model
{
    use HasBinaryUuid, SoftDeletes;

    public function organizations(): HasMany
    {
        return $this->hasMany(Organization::class, 'category_id');
    }
}
