<?php

namespace App\Http\Controllers;

use App\Services\YouthStatisticsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class PublicYouthStatisticsController extends Controller
{
    public function __invoke(Request $request, YouthStatisticsService $statistics): View
    {
        $options = $statistics->filterOptions();
        $filters = [];
        if (in_array((int) $request->query('year'), $options['years'], true)) {
            $filters['year'] = (int) $request->query('year');
        }
        if (in_array($request->query('district'), array_column($options['districts'], 'code'), true)) {
            $filters['district'] = $request->query('district');
        }
        if (array_key_exists((string) $request->query('age'), $options['ages'])) {
            $filters['age'] = $request->query('age');
        }

        return view('public.statistics.index', [
            'statistics' => $statistics->public($filters),
            'filters' => $filters,
            'options' => $options,
            'threshold' => YouthStatisticsService::PUBLIC_THRESHOLD,
        ]);
    }
}
