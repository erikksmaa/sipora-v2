<?php

namespace App\Actions\Youth;

use App\Models\Organization;
use App\Models\User;
use App\Support\BinaryUuid;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

final class SaveCommunityDraftAction
{
    public function execute(User $user, array $data, ?Organization $organization = null, ?UploadedFile $logo = null): Organization
    {
        $newLogoPath = $logo?->store('organization-logos', 'community_media');
        $oldLogoPath = $organization?->logo_path;

        try {
            $organization = DB::transaction(function () use ($user, $data, $organization, $newLogoPath): Organization {
                $community = $organization ?? new Organization;

                if (! $organization) {
                    $community->created_by_user_id = $user->getKey();
                    $community->slug = $this->uniqueSlug($data['name']);
                    $community->review_status = Organization::REVIEW_DRAFT;
                    $community->operational_status = Organization::OPERATIONAL_INACTIVE;
                }

                $community->fill([
                    'category_id' => BinaryUuid::bytes($data['category_id']),
                    'administrative_area_id' => isset($data['administrative_area_id'])
                        ? BinaryUuid::bytes($data['administrative_area_id'])
                        : null,
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
                    $community->logo_path = $newLogoPath;
                }

                $community->save();

                return $community;
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

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'komunitas';

        do {
            $slug = Str::limit($base, 181, '').'-'.Str::lower(Str::random(8));
        } while (Organization::withTrashed()->where('slug', $slug)->exists());

        return $slug;
    }
}
