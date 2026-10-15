<?php

use App\Models\Program;
use App\Models\ProgramApplication;
use App\Models\User;
use App\Services\Program\ProgramIntakeService;
use App\Support\BinaryUuid;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Validation\ValidationException;

// Used only by the real-MySQL concurrency feature test; refuses development DB.
require __DIR__.'/../../vendor/autoload.php';
$app = require __DIR__.'/../../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();
if (! app()->environment('testing') || config('database.connections.mysql.database') !== 'sipora_attendance_test') {
    throw new RuntimeException('Concurrency worker requires isolated testing database');
}
$user = User::findOrFail(BinaryUuid::bytes($argv[2]));
$program = Program::where('slug', $argv[3])->firstOrFail();
echo "ready\n";
flush();
try {
    $service = app(ProgramIntakeService::class);
    if ($argv[1] === 'review') {
        $service->review($user, $program, ProgramApplication::findOrFail(BinaryUuid::bytes($argv[4])), 'accepted', 'submitted', null);
    } else {
        $service->apply($user, $program);
    }
    echo "success\n";
} catch (ValidationException $exception) {
    echo "blocked\n";
}
