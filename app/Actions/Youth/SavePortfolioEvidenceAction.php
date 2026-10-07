<?php

namespace App\Actions\Youth;

use App\Models\OrganizationExperience;
use App\Models\PortfolioEvidence;
use App\Models\User;
use App\Models\UserAchievement;
use App\Models\UserEducation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class SavePortfolioEvidenceAction
{
    public function execute(User $user, string $type, UserEducation|OrganizationExperience|UserAchievement $item, UploadedFile $file): void
    {
        abort_unless($item->user_id === $user->getKey()
            && (($type === 'education' && $item instanceof UserEducation)
                || ($type === 'organization' && $item instanceof OrganizationExperience)
                || ($type === 'achievement' && $item instanceof UserAchievement)), 404);
        abort_if($item instanceof UserAchievement && $item->verification_status === 'verified', 403);

        $path = $type.'/'.$user->uuid().'/'.$item->uuid().'/'.Str::random(40).'.'.$file->guessExtension();
        if (! Storage::disk('portfolio_evidence')->putFileAs(dirname($path), $file, basename($path))) {
            abort(500, 'Bukti gagal disimpan. Silakan coba lagi.');
        }

        try {
            if ($type === 'achievement') {
                $oldPath = $item->evidence_path;
                $item->forceFill(['evidence_path' => $path])->save();
            } else {
                $existing = PortfolioEvidence::query()->where('user_id', $user->getKey())
                    ->where('record_type', $type)->where('record_id', $item->getKey())->first();
                $oldPath = $existing?->file_path;
                PortfolioEvidence::updateOrCreate(
                    ['record_type' => $type, 'record_id' => $item->getKey()],
                    ['user_id' => $user->getKey(), 'original_name' => basename($file->getClientOriginalName()),
                        'mime_type' => $file->getMimeType(), 'size_bytes' => $file->getSize(), 'file_path' => $path]
                );
            }
        } catch (\Throwable $exception) {
            Storage::disk('portfolio_evidence')->delete($path);
            throw $exception;
        }

        if ($oldPath && $oldPath !== $path) {
            Storage::disk('portfolio_evidence')->delete($oldPath);
        }
    }
}
