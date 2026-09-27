<?php

namespace App\Http\Controllers\Youth;

use App\Http\Controllers\Controller;
use App\Models\UserIdentity;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IdentityVerificationController extends Controller
{
    public function __invoke(Request $request): View
    {
        $identity = UserIdentity::firstOrCreate(['user_id' => $request->user()->getKey()]);

        return view('youth.identity-verification', ['user' => $request->user()->load('profile'), 'identity' => $identity]);
    }
}
