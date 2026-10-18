<?php

namespace App\Http\Middleware;

use App\Services\Youth\GoogleOnboardingService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureGoogleOnboarding
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->routeIs('youth.registration.*') && app(GoogleOnboardingService::class)->pending($request->user())) {
            return to_route('youth.registration.edit');
        }

        return $next($request);
    }
}
