<?php

namespace App\Http\Controllers;

use App\Services\Youth\PublicYouthDirectoryService;
use App\Services\YouthStatisticsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class PublicYouthDirectoryController extends Controller
{
    public function __invoke(Request $request, PublicYouthDirectoryService $directory, YouthStatisticsService $statistics): View
    {
        return view('public.youth.index', [
            'profiles' => $directory->paginate($request->query('q'), $request->query('area')),
            'areas' => $directory->areas(),
            'query' => mb_substr(trim($request->string('q')->toString()), 0, 120),
            'selectedArea' => mb_substr(trim($request->string('area')->toString()), 0, 120),
            'youthStatistics' => $statistics->publicSummary(),
        ]);
    }
}
