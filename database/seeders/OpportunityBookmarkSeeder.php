<?php

namespace Database\Seeders;

use App\Models\Opportunity;
use App\Models\OpportunityBookmark;
use App\Models\User;
use Illuminate\Database\Seeder;

class OpportunityBookmarkSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->where('email', 'youth1@sipora.test')->firstOrFail();

        foreach (['beasiswa-pemuda-pemalang', 'volunteer-festival-pemuda'] as $slug) {
            $opportunity = Opportunity::query()->where('slug', $slug)->firstOrFail();
            $bookmark = OpportunityBookmark::withTrashed()->firstOrNew([
                'user_id' => $user->getKey(),
                'opportunity_id' => $opportunity->getKey(),
            ]);
            $bookmark->deleted_at = null;
            $bookmark->save();
        }
    }
}
