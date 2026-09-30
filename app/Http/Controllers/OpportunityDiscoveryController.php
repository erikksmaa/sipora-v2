<?php

namespace App\Http\Controllers;

use App\Services\Discovery\OpportunityDiscoveryService;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class OpportunityDiscoveryController extends Controller
{
    public function __invoke(Request $request, OpportunityDiscoveryService $discovery): View
    {
        return view('public.opportunities.index', $discovery->paginate($request->query()));
    }
}
