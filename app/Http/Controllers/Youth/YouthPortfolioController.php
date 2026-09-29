<?php

namespace App\Http\Controllers\Youth;

use App\Http\Controllers\Controller;
use App\Services\Youth\YouthPortfolioService;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class YouthPortfolioController extends Controller
{
    public function __invoke(Request $request, YouthPortfolioService $portfolio): View
    {
        return view('portfolio.show', ['portfolio' => $portfolio->forOwner($request->user())]);
    }
}
