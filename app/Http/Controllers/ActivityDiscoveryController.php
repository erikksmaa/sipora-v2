<?php

namespace App\Http\Controllers;

use App\Services\Discovery\ActivityDiscoveryService;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ActivityDiscoveryController extends Controller
{
    public function __invoke(Request $request, ActivityDiscoveryService $discovery): View
    {
        return view('public.activities.index', $discovery->paginate($request->query()));
    }
}
