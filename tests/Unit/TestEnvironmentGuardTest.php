<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Support\TestEnvironmentGuard;

final class TestEnvironmentGuardTest extends TestCase
{
    public function test_isolated_mysql_testing_environment_is_allowed(): void
    {
        TestEnvironmentGuard::assertSafe('testing', 'mysql', TestEnvironmentGuard::DATABASE);
        $this->addToAssertionCount(1);
    }

    public static function unsafeEnvironments(): array
    {
        return [
            ['testing', 'mysql', 'sipora'],
            ['local', 'mysql', TestEnvironmentGuard::DATABASE],
            ['production', 'mysql', TestEnvironmentGuard::DATABASE],
            ['testing', 'sqlite', TestEnvironmentGuard::DATABASE],
            ['testing', 'mysql', null],
            ['testing', 'mysql', 'sipora_regression_test_other'],
        ];
    }

    #[DataProvider('unsafeEnvironments')]
    public function test_unsafe_database_or_environment_is_rejected(string $environment, string $driver, ?string $database): void
    {
        $this->expectException(\RuntimeException::class);
        TestEnvironmentGuard::assertSafe($environment, $driver, $database);
    }
}
