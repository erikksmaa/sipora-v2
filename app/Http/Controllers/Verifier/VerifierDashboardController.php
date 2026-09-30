<?php

namespace App\Http\Controllers\Verifier;

use App\Http\Controllers\Controller;
use App\Services\VerifierQueueSummaryService;
use Illuminate\View\View;

final class VerifierDashboardController extends Controller
{
    public function __invoke(VerifierQueueSummaryService $summary): View
    {
        return view('workspaces.verifier', ['queues' => $summary->summarize()]);
    }
}
