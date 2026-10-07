<?php

namespace App\Http\Controllers\Youth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Youth\UpdateAccountPasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

final class AccountSecurityController extends Controller
{
    public function edit(Request $request): View
    {
        return view('youth.account.security', ['hasPassword' => filled($request->user()->password)]);
    }

    public function update(UpdateAccountPasswordRequest $request): RedirectResponse
    {
        $request->user()->forceFill(['password' => Hash::make($request->validated('password'))])->save();
        $request->session()->regenerate();

        return to_route('youth.account.security')->with('status', 'Password akun berhasil disimpan.');
    }
}
