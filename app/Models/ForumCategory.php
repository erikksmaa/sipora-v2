<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumCategory extends Model
{
    use HasBinaryUuid;

    protected $fillable = ['name', 'slug', 'description', 'is_active'];

    public function getRouteKeyName(): string { return 'slug'; }

    public function threads(): HasMany { return $this->hasMany(ForumThread::class, 'category_id'); }
}
