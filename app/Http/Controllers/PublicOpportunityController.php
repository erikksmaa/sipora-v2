<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use App\Services\Discovery\OpportunityDiscoveryService;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class PublicOpportunityController extends Controller
{
    public function show(Request $request, Opportunity $opportunity, OpportunityDiscoveryService $discovery): View
    {
        $opportunity = $discovery->publicQuery()->whereKey($opportunity->getKey())->firstOrFail();
        $providerCommunity = $opportunity->organization?->review_status === 'approved' && $opportunity->organization?->operational_status === 'active'
            ? $opportunity->organization : null;
        $bookmarked = $request->user()?->hasRole('youth') === true
            && $request->user()->opportunityBookmarks()->where('opportunity_id', $opportunity->getKey())->exists();

        return view('public.opportunities.show', compact('opportunity', 'bookmarked', 'providerCommunity'));
    }
}
