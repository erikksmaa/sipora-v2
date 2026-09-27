<?php

namespace App\Models;

use App\Support\BinaryUuid;
use Spatie\Activitylog\Models\Activity;

class AuditEntry extends Activity
{
    protected $dateFormat = 'Y-m-d H:i:s.u';

    public function attributesToArray(): array
    {
        $attributes = parent::attributesToArray();
        foreach (['subject_id', 'causer_id'] as $key) {
            if (isset($attributes[$key])) {
                $attributes[$key] = BinaryUuid::text($attributes[$key]);
            }
        }

        return $attributes;
    }
}
