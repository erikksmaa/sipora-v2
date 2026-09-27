<?php

namespace App\Http\Controllers;

use App\Presenters\PublicLandingPresenter;
use Illuminate\Contracts\View\View;

final class PublicLandingController extends Controller
{
    public function __invoke(PublicLandingPresenter $presenter): View
    {
        return view('home', $presenter->present());
    }
}
