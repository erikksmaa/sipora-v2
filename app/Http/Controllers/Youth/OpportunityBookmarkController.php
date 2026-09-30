<?php

namespace App\Http\Controllers\Youth;

use App\Http\Controllers\Controller;
use App\Models\Opportunity;
use App\Models\OpportunityBookmark;
use App\Services\Discovery\OpportunityDiscoveryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class OpportunityBookmarkController extends Controller
{
    public function index(Request $request): View
    {
        $bookmarks = $request->user()->opportunityBookmarks()->with(['opportunity.category', 'opportunity.administrativeArea'])
            ->whereHas('opportunity', fn ($query) => $query->where('publication_status', Opportunity::STATUS_PUBLISHED)->where('published_at', '<=', now()))
            ->latest()->paginate(12);

        return view('youth.opportunities.bookmarks', compact('bookmarks'));
    }

    public function store(Request $request, Opportunity $opportunity, OpportunityDiscoveryService $discovery): RedirectResponse
    {
        $discovery->publicQuery()->whereKey($opportunity->getKey())->firstOrFail();
        $bookmark = OpportunityBookmark::withTrashed()->firstOrNew(['user_id' => $request->user()->getKey(), 'opportunity_id' => $opportunity->getKey()]);
        if ($bookmark->exists && $bookmark->deleted_at === null) {
            return back()->withErrors(['bookmark' => 'Opportunity sudah tersimpan.']);
        }
        $bookmark->deleted_at = null;
        $bookmark->save();

        return back()->with('status', 'Opportunity disimpan.');
    }

    public function destroy(Request $request, Opportunity $opportunity): RedirectResponse
    {
        $bookmark = $request->user()->opportunityBookmarks()->where('opportunity_id', $opportunity->getKey())->firstOrFail();
        $bookmark->delete();

        return back()->with('status', 'Opportunity dihapus dari daftar simpan.');
    }
}
