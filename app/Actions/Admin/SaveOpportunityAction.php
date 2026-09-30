<?php

namespace App\Actions\Admin;

use App\Models\Opportunity;
use App\Models\User;
use App\Support\BinaryUuid;
use Illuminate\Support\Str;

final class SaveOpportunityAction
{
    public function execute(User $admin, array $data, ?Opportunity $opportunity = null): Opportunity
    {
        $data['category_id'] = BinaryUuid::bytes($data['category_id']);
        foreach (['organization_id', 'administrative_area_id'] as $foreignKey) {
            $data[$foreignKey] = filled($data[$foreignKey] ?? null) ? BinaryUuid::bytes($data[$foreignKey]) : null;
        }
        $opportunity ??= new Opportunity;
        if (! $opportunity->exists) {
            $opportunity->created_by_user_id = $admin->getKey();
            $opportunity->publication_status = Opportunity::STATUS_DRAFT;
            $opportunity->slug = $this->uniqueSlug($data['title']);
        }
        $opportunity->fill($data);
        $opportunity->save();

        activity()->causedBy($admin)->performedOn($opportunity)
            ->event($opportunity->wasRecentlyCreated ? 'opportunity_created' : 'opportunity_updated')
            ->withProperties(['opportunity_id' => $opportunity->uuid(), 'publication_status' => $opportunity->publication_status])
            ->log($opportunity->wasRecentlyCreated ? 'Opportunity dibuat' : 'Opportunity diperbarui');

        return $opportunity;
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'opportunity';
        $slug = mb_substr($base, 0, 175);
        $candidate = $slug;
        $counter = 2;
        while (Opportunity::withTrashed()->where('slug', $candidate)->exists()) {
            $candidate = $slug.'-'.$counter++;
        }

        return $candidate;
    }
}
