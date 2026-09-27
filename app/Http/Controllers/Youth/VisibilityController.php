<?php

namespace App\Http\Controllers\Youth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Youth\UpdateVisibilityRequest;
use App\Models\UserProfileVisibility;
use Illuminate\Http\RedirectResponse;

class VisibilityController extends Controller
{
    public function update(UpdateVisibilityRequest $request): RedirectResponse
    {
        UserProfileVisibility::updateOrCreate(['user_id' => $request->user()->getKey()], $request->validated());

        return to_route('youth.profile.show')->with('status', 'Pengaturan privasi diperbarui.');
    }
}
