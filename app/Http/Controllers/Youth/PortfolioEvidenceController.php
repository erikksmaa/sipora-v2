<?php

namespace App\Http\Controllers\Youth;

use App\Actions\Youth\SavePortfolioEvidenceAction;
use App\Http\Controllers\Controller;
use App\Models\OrganizationExperience;
use App\Models\PortfolioEvidence;
use App\Models\User;
use App\Models\UserAchievement;
use App\Models\UserEducation;
use App\Models\UserProfile;
use App\Support\BinaryUuid;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class PortfolioEvidenceController extends Controller
{
    public function show(Request $request, string $type, string $record): View
    {
        $item = $this->ownedRecord($request, $type, $record);
        $evidence = $this->metadata($request, $type, $item);
        $achievementEvidenceInfo = null;
        if ($type === 'achievement' && $item->evidence_path && Storage::disk('portfolio_evidence')->exists($item->evidence_path)) {
            $achievementEvidenceInfo = [
                'mime_type' => Storage::disk('portfolio_evidence')->mimeType($item->evidence_path),
                'size_bytes' => Storage::disk('portfolio_evidence')->size($item->evidence_path),
            ];
        }
        $visible = (bool) $request->user()->profileVisibility?->{match ($type) {
            'education' => 'show_education',
            'organization' => 'show_organization_experience',
            default => 'show_achievements',
        }};

        return view('youth.portfolio-evidence', compact('item', 'evidence', 'type', 'visible', 'achievementEvidenceInfo'));
    }

    public function store(Request $request, SavePortfolioEvidenceAction $saveEvidence, string $type, string $record): RedirectResponse
    {
        $item = $this->ownedRecord($request, $type, $record);
        $this->ensureEditable($item);
        $request->validate(['evidence' => ['required', File::types(['pdf', 'jpg', 'jpeg', 'png'])->max(5 * 1024)]]);
        $saveEvidence->execute($request->user(), $type, $item, $request->file('evidence'));

        return to_route('youth.portfolio.evidence.show', [$type, $record])->with('status', 'Bukti pendukung tersimpan secara privat. Status klaim tetap self-reported.');
    }

    public function file(Request $request, string $type, string $record): StreamedResponse
    {
        $item = $this->ownedRecord($request, $type, $record);
        $evidence = $this->metadata($request, $type, $item);

        return $this->stream($type === 'achievement' ? $item->evidence_path : $evidence?->file_path);
    }

    public function publicFile(string $profile, string $type, string $record): StreamedResponse
    {
        abort_unless(in_array($type, ['organization', 'achievement'], true), 404);
        $profile = UserProfile::query()->where('public_slug', $profile)->with('user.profileVisibility')->firstOrFail();
        $visibility = $profile->user->profileVisibility;
        $sectionVisible = $type === 'organization'
            ? $visibility?->show_organization_experience : $visibility?->show_achievements;
        abort_unless($visibility?->is_profile_public && $sectionVisible, 404);
        $item = $this->recordFor($profile->user, $type, $record);
        if ($type === 'achievement') {
            abort_unless(in_array($item->verification_status, ['self_reported', 'pending', 'verified'], true), 404);
            $path = $item->evidence_path;
        } else {
            $path = PortfolioEvidence::query()->where('user_id', $profile->user->getKey())
                ->where('record_type', 'organization')->where('record_id', $item->getKey())->value('file_path');
        }

        return $this->stream($path);
    }

    public function destroy(Request $request, string $type, string $record): RedirectResponse
    {
        $item = $this->ownedRecord($request, $type, $record);
        $this->ensureEditable($item);
        $evidence = $this->metadata($request, $type, $item);
        $path = $type === 'achievement' ? $item->evidence_path : $evidence?->file_path;
        abort_unless($path, 404);
        if ($type === 'achievement') {
            $item->forceFill(['evidence_path' => null])->save();
        } else {
            $evidence->delete();
        }
        Storage::disk('portfolio_evidence')->delete($path);

        return to_route('youth.portfolio.evidence.show', [$type, $record])->with('status', 'Bukti pendukung dihapus.');
    }

    private function ownedRecord(Request $request, string $type, string $record): UserEducation|OrganizationExperience|UserAchievement
    {
        return $this->recordFor($request->user(), $type, $record);
    }

    private function recordFor(User $user, string $type, string $record): UserEducation|OrganizationExperience|UserAchievement
    {
        $relation = match ($type) {
            'education' => 'educations',
            'organization' => 'organizationExperiences',
            'achievement' => 'achievements',
            default => abort(404),
        };
        $class = match ($type) {
            'education' => UserEducation::class,
            'organization' => OrganizationExperience::class,
            default => UserAchievement::class,
        };

        return $user->{$relation}()->whereKey(BinaryUuid::bytesOrFail($record, $class))->firstOrFail();
    }

    private function stream(?string $path): StreamedResponse
    {
        abort_unless($path && Storage::disk('portfolio_evidence')->exists($path), 404);
        $mime = Storage::disk('portfolio_evidence')->mimeType($path);
        abort_unless(in_array($mime, ['application/pdf', 'image/jpeg', 'image/png'], true), 404);

        return response()->stream(function () use ($path): void {
            $stream = Storage::disk('portfolio_evidence')->readStream($path);
            abort_unless(is_resource($stream), 404);
            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="bukti-portfolio.'.($mime === 'application/pdf' ? 'pdf' : ($mime === 'image/png' ? 'png' : 'jpg')).'"',
            'Cache-Control' => 'private, no-store, max-age=0',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function metadata(Request $request, string $type, UserEducation|OrganizationExperience|UserAchievement $item): ?PortfolioEvidence
    {
        if ($type === 'achievement') {
            return null;
        }

        return PortfolioEvidence::query()->where('user_id', $request->user()->getKey())
            ->where('record_type', $type)->where('record_id', $item->getKey())->first();
    }

    private function ensureEditable(UserEducation|OrganizationExperience|UserAchievement $item): void
    {
        if ($item instanceof UserAchievement) {
            abort_if($item->verification_status === 'verified', 403);
        }
    }
}
