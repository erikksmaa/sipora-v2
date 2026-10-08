<?php

namespace App\Http\Controllers\Youth;

use App\Http\Controllers\Controller;
use App\Services\Youth\DevelopmentPathwayService;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class DevelopmentPathwayController extends Controller
{
    public function __invoke(Request $request, DevelopmentPathwayService $pathway): View
    {
        return view('youth.development-pathway.index', ['pathway' => $pathway->for($request->user())]);
    }
}
