<?php

namespace App\Http\Controllers;

use App\Services\Discovery\CommunityDiscoveryService;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class CommunityDiscoveryController extends Controller
{
    public function __invoke(Request $request, CommunityDiscoveryService $discovery): View
    {
        return view('public.communities.index', $discovery->paginate($request->query()));
    }
}
