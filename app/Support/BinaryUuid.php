<?php

namespace App\Support;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

final class BinaryUuid
{
    public static function generate(): string
    {
        return Uuid::uuid7()->getBytes();
    }

    public static function bytes(string $value): string
    {
        $uuid = strlen($value) === 16 ? Uuid::fromBytes($value) : Uuid::fromString($value);

        if ($uuid->getVersion() !== 7) {
            throw new InvalidArgumentException('A UUIDv7 identifier is required.');
        }

        return $uuid->getBytes();
    }

    public static function text(string $value): string
    {
        return Uuid::fromBytes(self::bytes($value))->toString();
    }

    public static function bytesOrFail(string $value, string $model): string
    {
        try {
            return self::bytes($value);
        } catch (InvalidArgumentException) {
            throw (new ModelNotFoundException)->setModel($model, [$value]);
        }
    }
}
