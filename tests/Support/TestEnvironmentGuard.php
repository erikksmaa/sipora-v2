<?php

namespace Tests\Support;

final class TestEnvironmentGuard
{
    public const DATABASE = 'sipora_regression_test';

    public static function assertSafe(string $environment, string $driver, ?string $database): void
    {
        if ($environment !== 'testing' || $driver !== 'mysql' || $database !== self::DATABASE) {
            throw new \RuntimeException('Tests require the isolated sipora_regression_test MySQL database and testing environment. Development databases are forbidden.');
        }
    }
}
