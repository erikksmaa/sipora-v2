<?php

namespace App\Http\Controllers;

use App\Services\YouthStatisticsService;
use Illuminate\View\View;

final class PublicEcosystemMapController extends Controller
{
    public function __invoke(YouthStatisticsService $statistics): View
    {
        return view('ecosystem-map.index', [
            'isAdmin' => false,
            'coverage' => $statistics->publicGeography(),
            'threshold' => YouthStatisticsService::PUBLIC_THRESHOLD,
        ]);
    }
}
