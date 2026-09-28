<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ActivitySession;
use Illuminate\Database\Seeder;

class ActivitySessionSeeder extends Seeder
{
    public function run(): void
    {
        $single = Activity::query()->where('slug', 'workshop-web-development-pemula')->firstOrFail();
        $this->session($single, 1, 'Workshop Dasar Web', $single->start_at, $single->end_at);

        $bootcamp = Activity::query()->where('slug', 'bootcamp-digital-pemalang')->firstOrFail();
        $this->session($bootcamp, 1, 'Fondasi Web dan Internet', $bootcamp->start_at, $bootcamp->start_at->copy()->addHours(4));
        $this->session($bootcamp, 2, 'HTML dan CSS', $bootcamp->start_at->copy()->addDay(), $bootcamp->start_at->copy()->addDay()->addHours(4));
        $this->session($bootcamp, 3, 'Mini Project', $bootcamp->start_at->copy()->addDays(2), $bootcamp->end_at);
    }

    private function session(Activity $activity, int $number, string $title, mixed $start, mixed $end): void
    {
        ActivitySession::withTrashed()->updateOrCreate(
            ['activity_id' => $activity->getKey(), 'session_number' => $number],
            ['title' => $title, 'description' => 'Sesi contoh untuk pengujian pengelolaan Activity.', 'start_at' => $start, 'end_at' => $end,
                'venue_name' => 'Gedung Pemuda Pemalang', 'address_text' => 'Kabupaten Pemalang', 'notes' => null, 'deleted_at' => null]
        );
    }
}
