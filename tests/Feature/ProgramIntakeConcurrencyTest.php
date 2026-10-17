<?php

namespace Tests\Feature;

use App\Models\Program;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;
use Tests\Support\ProgramIntakeFixtures;
use Tests\TestCase;

class ProgramIntakeConcurrencyTest extends TestCase
{
    use DatabaseMigrations, ProgramIntakeFixtures;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        $this->beforeApplicationDestroyed(function (): void {
            RefreshDatabaseState::$migrated = false;
        });
    }

    public function test_competing_acceptance_open_registration_and_duplicates_are_serialized(): void
    {
        [$org,$manager,$program] = $this->intakeFixture();
        $first = $program->applications()->create(['user_id' => $this->intakeYouth()->getKey(), 'status' => 'submitted']);
        $second = $program->applications()->create(['user_id' => $this->intakeYouth()->getKey(), 'status' => 'submitted']);
        $this->race($program, [['review', $manager->uuid(), $program->slug, $first->uuid()], ['review', $manager->uuid(), $program->slug, $second->uuid()]]);
        $this->assertSame(1, $program->participants()->count());
        $this->assertSame(1, $program->applications()->where('status', 'submitted')->count());
        [$org,$manager,$open] = $this->intakeFixture(['selection_mode' => 'open']);
        $this->race($open, [['apply', $this->intakeYouth()->uuid(), $open->slug], ['apply', $this->intakeYouth()->uuid(), $open->slug]]);
        $this->assertSame(1, $open->applications()->count());
        $this->assertSame(1, $open->participants()->count());
        [$org,$manager,$duplicate] = $this->intakeFixture(['quota' => null]);
        $youth = $this->intakeYouth();
        $this->race($duplicate, [['apply', $youth->uuid(), $duplicate->slug], ['apply', $youth->uuid(), $duplicate->slug]]);
        $this->assertSame(1, $duplicate->applications()->count());
    }

    private function race(Program $program, array $arguments): void
    {
        config(['database.connections.program_lock' => config('database.connections.mysql')]);
        $lock = DB::connection('program_lock');
        $lock->beginTransaction();
        $lock->table('programs')->where('id', $program->getKey())->lockForUpdate()->first();
        $workers = [];
        try {
            foreach ($arguments as $args) {
                $worker = new Process(array_merge([PHP_BINARY, base_path('tests/Support/program-intake-worker.php')], $args), base_path(), ['APP_ENV' => 'testing', 'DB_DATABASE' => \Tests\Support\TestEnvironmentGuard::DATABASE, 'DB_URL' => '', 'CACHE_STORE' => 'array']);
                $worker->setTimeout(60);
                $worker->start();
                $workers[] = $worker;
            }
            $deadline = microtime(true) + 30;
            do {
                $ready = count(array_filter($workers, fn ($p) => str_contains($p->getOutput(), 'ready')));
                if ($ready === 2) {
                    break;
                }
                if (microtime(true) > $deadline) {
                    throw new \RuntimeException('Concurrency workers did not reach the locked transaction');
                }
                usleep(50000);
            } while (true);
            $lock->commit();
            foreach ($workers as $worker) {
                $worker->wait();
            }
            $this->assertSame(1, count(array_filter($workers, fn ($p) => str_contains($p->getOutput(), 'success'))));
            $this->assertSame(1, count(array_filter($workers, fn ($p) => str_contains($p->getOutput(), 'blocked'))));
            foreach ($workers as $worker) {
                $this->assertSame(0, $worker->getExitCode(), $worker->getErrorOutput());
            }
        } finally {
            if ($lock->transactionLevel() > 0) {
                $lock->rollBack();
            }
            foreach ($workers as $worker) {
                if ($worker->isRunning()) {
                    $worker->stop();
                }
            }
            DB::purge('program_lock');
        }
    }
}
