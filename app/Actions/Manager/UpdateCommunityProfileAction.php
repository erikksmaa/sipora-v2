<?php

namespace App\Actions\Manager;

use App\Models\Organization;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

final class UpdateCommunityProfileAction
{
    public function execute(Organization $organization, array $data, ?UploadedFile $logo): Organization
    {
        $newLogoPath = $logo?->store('organization-logos', 'community_media');
        $oldLogoPath = $organization->logo_path;

        try {
            $organization = DB::transaction(function () use ($organization, $data, $newLogoPath): Organization {
                $organization = Organization::query()->whereKey($organization->getKey())->lockForUpdate()->firstOrFail();
                $organization->fill([
                    'name' => trim($data['name']),
                    'description' => $data['description'] ?? null,
                    'contact_email' => $data['contact_email'] ?? null,
                    'contact_phone' => $data['contact_phone'] ?? null,
                    'website_url' => $data['website_url'] ?? null,
                    'address_text' => $data['address_text'] ?? null,
                    'social_links' => array_filter([
                        'instagram' => $data['social_instagram'] ?? null,
                        'facebook' => $data['social_facebook'] ?? null,
                        'tiktok' => $data['social_tiktok'] ?? null,
                    ]) ?: (object) [],
                ]);
                if ($newLogoPath !== null) {
                    $organization->logo_path = $newLogoPath;
                }
                $organization->save();

                return $organization;
            });
        } catch (Throwable $exception) {
            if ($newLogoPath !== null) {
                Storage::disk('community_media')->delete($newLogoPath);
            }

            throw $exception;
        }

        if ($newLogoPath !== null && $oldLogoPath !== null) {
            Storage::disk('community_media')->delete($oldLogoPath);
        }

        return $organization;
    }
}
