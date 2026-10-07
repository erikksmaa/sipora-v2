<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\YouthStatisticsService;
use Illuminate\View\View;

final class EcosystemMapController extends Controller
{
    public function __invoke(YouthStatisticsService $statistics): View
    {
        return view('ecosystem-map.index', [
            'isAdmin' => true,
            'coverage' => $statistics->adminGeography(),
            'threshold' => YouthStatisticsService::PUBLIC_THRESHOLD,
        ]);
    }
}
