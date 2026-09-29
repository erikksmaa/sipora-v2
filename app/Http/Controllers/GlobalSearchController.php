<?php

namespace App\Http\Controllers;

use App\Services\Discovery\GlobalSearchService;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GlobalSearchController extends Controller
{
    public function __invoke(Request $request, GlobalSearchService $search): View
    {
        return view('public.search.index', $search->search($request->query('q')));
    }
}
