<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminAnalyticsService;
use App\Services\YouthStatisticsService;
use Illuminate\View\View;

final class AdminDashboardController extends Controller
{
    public function __invoke(AdminAnalyticsService $analytics, YouthStatisticsService $statistics): View
    {
        $youthStatistics = $statistics->admin();

        return view('workspaces.admin', [
            'analytics' => $analytics->overview($youthStatistics['totals']),
            'youthStatistics' => $youthStatistics,
        ]);
    }
}
