<?php

namespace App\Http\Controllers;

use App\Services\Discovery\ProgramDiscoveryService;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ProgramDiscoveryController extends Controller
{
    public function __invoke(Request $request, ProgramDiscoveryService $discovery): View
    {
        return view('public.programs.index', $discovery->paginate($request->query()));
    }
}
