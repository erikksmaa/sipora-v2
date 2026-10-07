<?php

namespace App\Http\Middleware;

use App\Services\Youth\YouthOnboardingService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureYouthOnboardingStage
{
    public function __construct(private readonly YouthOnboardingService $onboarding) {}

    public function handle(Request $request, Closure $next, string $stage): Response
    {
        $state = $this->onboarding->state($request->user());

        if (! $this->onboarding->allows($state, $stage)) {
            return to_route('youth.onboarding')->with('status',
                $this->onboarding->lockedReason($state).' Lengkapi akun terlebih dahulu untuk menggunakan fitur ini.');
        }

        return $next($request);
    }
}
