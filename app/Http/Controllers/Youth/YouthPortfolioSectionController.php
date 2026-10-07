<?php

namespace App\Http\Controllers\Youth;

use App\Http\Controllers\Controller;
use App\Models\PortfolioEvidence;
use App\Models\UserCertificate;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class YouthPortfolioSectionController extends Controller
{
    public function show(Request $request, string $section): View
    {
        abort_unless(in_array($section, ['education', 'experience', 'achievements', 'training'], true), 404);
        $user = $request->user();
        $items = match ($section) {
            'education' => $user->educations()->get(),
            'experience' => $user->organizationExperiences()->get(),
            'achievements' => $user->achievements()->get(),
            default => $user->certificates()->where('source_type', UserCertificate::SOURCE_EXTERNAL)->latest('issued_at')->get(),
        };
        $evidences = PortfolioEvidence::query()->where('user_id', $user->getKey())
            ->where('record_type', match ($section) {
                'education' => 'education',
                'experience' => 'organization',
                default => 'achievement',
            })->get()
            ->keyBy(fn (PortfolioEvidence $evidence) => $evidence->record_type.':'.bin2hex($evidence->record_id));

        return view('youth.portfolio.section', compact('section', 'items', 'evidences'));
    }
}
