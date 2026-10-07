<?php

namespace App\Http\Controllers\Youth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class AccountController extends Controller
{
    public function show(Request $request): View
    {
        return view('youth.account.show', [
            'user' => $request->user(),
            'providers' => $request->user()->socialAccounts()->pluck('provider')->unique()->values(),
        ]);
    }
}
