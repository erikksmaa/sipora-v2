<?php

namespace App\Services\Community;

use App\Models\Organization;

final class CommunityProfileCompletionService
{
    /** @return array{percentage:int,completed:int,total:int} */
    public function calculate(Organization $organization): array
    {
        $checks = [
            filled($organization->name),
            $organization->category_id !== null,
            filled($organization->description),
            filled($organization->logo_path),
            filled($organization->contact_email) || filled($organization->contact_phone),
            filled($organization->address_text),
            filled($organization->website_url),
            ! empty($organization->social_links),
        ];
        $completed = count(array_filter($checks));

        return ['percentage' => (int) round(($completed / count($checks)) * 100), 'completed' => $completed, 'total' => count($checks)];
    }
}
