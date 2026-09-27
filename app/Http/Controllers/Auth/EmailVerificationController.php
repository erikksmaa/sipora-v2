<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerifyEmailRequest;
use App\Support\Auth\HomeRoute;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function verify(VerifyEmailRequest $request)
    {
        $request->fulfill();

        return to_route(HomeRoute::for($request->user()))->with('status', 'Email verified.');
    }

    public function send(Request $request)
    {
        if (! $request->user()->hasVerifiedEmail()) {
            $request->user()->sendEmailVerificationNotification();
        }

        return back()->with('status', 'Verification link sent.');
    }
}
