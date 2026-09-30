<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['slug' => 'program-pemuda-digital-2026', 'title' => 'Program Pemuda Digital 2026', 'organization' => 'komunitas-programmer-pemalang', 'category' => 'pengembangan-kapasitas-pemuda', 'creator' => 'youth1@sipora.test', 'start' => now()->subMonth()->toDateString(), 'end' => now()->addMonths(2)->toDateString(), 'execution' => Program::STATUS_RUNNING],
            ['slug' => 'program-kepemimpinan-muda', 'title' => 'Program Kepemimpinan Muda', 'organization' => 'komunitas-programmer-pemalang', 'category' => 'kepemudaan-dan-kepemimpinan', 'creator' => 'youth1@sipora.test', 'start' => null, 'end' => null],
            ['slug' => 'program-olahraga-komunitas', 'title' => 'Program Olahraga Komunitas', 'organization' => 'pemuda-olahraga-pemalang', 'category' => 'olahraga-masyarakat', 'creator' => 'youth3@sipora.test', 'start' => now()->addDays(5)->toDateString(), 'end' => now()->addMonths(2)->toDateString()],
            ['slug' => 'program-kreativitas-pemuda', 'title' => 'Program Kreativitas Pemuda', 'organization' => 'komunitas-programmer-pemalang', 'category' => 'pengembangan-kapasitas-pemuda', 'creator' => 'youth1@sipora.test', 'start' => now()->addDays(14)->toDateString(), 'end' => now()->addMonths(2)->toDateString()],
            ['slug' => 'program-literasi-teknologi', 'title' => 'Program Literasi Teknologi', 'organization' => 'komunitas-programmer-pemalang', 'category' => 'pengembangan-kapasitas-pemuda', 'creator' => 'youth1@sipora.test', 'start' => now()->addDays(10)->toDateString(), 'end' => now()->addMonths(2)->toDateString()],
            ['slug' => 'program-kolaborasi-digital', 'title' => 'Program Kolaborasi Digital', 'organization' => 'komunitas-programmer-pemalang', 'category' => 'pengembangan-kapasitas-pemuda', 'creator' => 'youth1@sipora.test', 'start' => now()->subWeeks(3)->toDateString(), 'end' => now()->addMonth()->toDateString(), 'execution' => Program::STATUS_RUNNING],
            ['slug' => 'program-evaluasi-pemuda', 'title' => 'Program Evaluasi Pemuda', 'organization' => 'komunitas-programmer-pemalang', 'category' => 'pengembangan-kapasitas-pemuda', 'creator' => 'youth1@sipora.test', 'start' => now()->subMonths(2)->toDateString(), 'end' => now()->subWeek()->toDateString(), 'execution' => Program::STATUS_RUNNING],
            ['slug' => 'program-tuntas-pemuda', 'title' => 'Program Tuntas Pemuda', 'organization' => 'komunitas-programmer-pemalang', 'category' => 'pengembangan-kapasitas-pemuda', 'creator' => 'youth1@sipora.test', 'start' => now()->subMonths(3)->toDateString(), 'end' => now()->subMonth()->toDateString(), 'execution' => Program::STATUS_RUNNING],
        ] as $record) {
            $organization = Organization::query()->where('slug', $record['organization'])->firstOrFail();
            $category = ProgramCategory::query()->where('slug', $record['category'])->firstOrFail();
            $creator = User::query()->where('email', $record['creator'])->firstOrFail();
            Program::withTrashed()->updateOrCreate(['slug' => $record['slug']], [
                'organization_id' => $organization->getKey(),
                'category_id' => $category->getKey(),
                'created_by_user_id' => $creator->getKey(),
                'title' => $record['title'],
                'description' => 'Program contoh untuk menguji domain inti Program pada lingkungan pengembangan.',
                'objectives' => 'Menghubungkan rangkaian Activity dalam satu rencana administratif yang terstruktur.',
                'start_date' => $record['start'],
                'end_date' => $record['end'],
                'execution_status' => $record['execution'] ?? Program::STATUS_PLANNED,
                'deleted_at' => null,
            ]);
        }
    }
}
