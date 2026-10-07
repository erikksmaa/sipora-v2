<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'kind', 'name', 'normalized_name'])]
#[Hidden(['user_id', 'normalized_name'])]
final class UserCustomPortfolioTag extends Model
{
    use HasBinaryUuid;
}
