<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'instagram', 'facebook', 'linkedin'])]
#[Hidden(['user_id'])]
final class UserContactLink extends Model
{
    use HasBinaryUuid;

    protected $table = 'user_contact_links';
}
