<?php

namespace App\Models\Concerns;

use App\Support\BinaryUuid;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

trait HasBinaryUuid
{
    public function initializeHasBinaryUuid(): void
    {
        $this->setIncrementing(false);
        $this->setKeyType('string');
        $this->setDateFormat('Y-m-d H:i:s.u');
    }

    public static function bootHasBinaryUuid(): void
    {
        static::creating(function (Model $model): void {
            $key = $model->getKeyName();
            $model->setAttribute($key, $model->getKey() === null
                ? BinaryUuid::generate()
                : BinaryUuid::bytes($model->getKey()));
        });
    }

    // Eloquent keys stay binary so standard relationships and Spatie pivots
    // bind exactly the same bytes. Text conversion happens at boundaries.
    public function uuid(): string
    {
        return BinaryUuid::text($this->getKey());
    }

    public function getRouteKey(): mixed
    {
        return $this->uuid();
    }

    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        if ($field && $field !== $this->getKeyName()) {
            return parent::resolveRouteBindingQuery($query, $value, $field);
        }

        try {
            return $query->where($field ?? $this->getKeyName(), BinaryUuid::bytes($value));
        } catch (InvalidArgumentException) {
            return $query->whereRaw('1 = 0');
        }
    }

    public function getQueueableId(): mixed
    {
        return $this->uuid();
    }

    public function newQueryForRestoration($ids)
    {
        return $this->newQueryWithoutScopes()->whereKey(
            array_map(BinaryUuid::bytes(...), is_array($ids) ? $ids : [$ids])
        );
    }

    public function attributesToArray(): array
    {
        $attributes = parent::attributesToArray();

        if (isset($attributes[$this->getKeyName()])) {
            $attributes[$this->getKeyName()] = $this->uuid();
        }

        return $attributes;
    }
}
