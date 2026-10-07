<?php

namespace App\Http\Controllers\Youth;

use App\Http\Controllers\Controller;
use App\Models\UserCustomPortfolioTag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class CustomPortfolioTagController extends Controller
{
    public function destroy(Request $request, UserCustomPortfolioTag $tag): RedirectResponse
    {
        abort_unless($tag->user_id === $request->user()->getKey(), 404);
        $tag->delete();

        return back()->with('status', 'Pilihan lainnya dihapus.');
    }
}
