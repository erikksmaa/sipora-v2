<?php

namespace Tests\Unit;

use App\Models\User;
use App\Support\BinaryUuid;
use Illuminate\Database\MySqlConnection;
use Illuminate\Database\Schema\Blueprint;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class BinaryUuidTest extends TestCase
{
    public function test_uuid_generation_and_round_trip_preserve_v7_bytes(): void
    {
        $bytes = BinaryUuid::generate();
        $this->assertSame(16, strlen($bytes));
        $this->assertSame(7, Uuid::fromBytes($bytes)->getVersion());
        $this->assertSame($bytes, BinaryUuid::bytes(BinaryUuid::text($bytes)));
        $this->assertNotSame($bytes, BinaryUuid::generate());
    }

    public function test_non_v7_ids_are_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        BinaryUuid::bytes(Uuid::uuid4()->toString());
    }

    public function test_user_uses_binary_relationship_keys_and_text_boundary_ids(): void
    {
        $user = new User;
        $user->id = BinaryUuid::generate();
        $this->assertFalse($user->getIncrementing());
        $this->assertSame('string', $user->getKeyType());
        $this->assertSame(16, strlen($user->getKey()));
        $this->assertSame($user->uuid(), $user->getAuthIdentifier());
        $this->assertSame($user->uuid(), $user->getRouteKey());
        $this->assertSame($user->uuid(), $user->getQueueableId());
        $this->assertSame($user->uuid(), $user->toArray()['id']);
    }

    public function test_mysql_grammar_produces_fixed_binary_and_microsecond_datetime_without_database_access(): void
    {
        $connection = new MySqlConnection(null, 'unused', '', ['prefix_indexes' => true]);
        $connection->useDefaultSchemaGrammar();
        $blueprint = new Blueprint($connection, 'uuid_gate');
        $blueprint->create();
        $blueprint->binary('id', 16, true)->primary();
        $blueprint->dateTime('created_at', 6)->nullable();
        $sql = implode(' ', $blueprint->toSql());
        $this->assertStringContainsString('binary(16)', $sql);
        $this->assertStringContainsString('datetime(6)', $sql);
        $this->assertStringNotContainsString('auto_increment', $sql);
    }
}
