<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminAnalyticsService;
use Illuminate\View\View;

final class AdminDashboardController extends Controller
{
    public function __invoke(AdminAnalyticsService $analytics): View
    {
        return view('workspaces.admin', ['analytics' => $analytics->overview()]);
    }
}
