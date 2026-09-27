@if(config('services.google.client_id') && config('services.google.client_secret'))
    <a href="{{ route('google.redirect') }}" class="btn-secondary mt-5 flex justify-center">Continue with Google</a>
    <p class="mt-3 text-sm text-slate-600">Google sign-in verifies account access, not your SIPORA identity.</p>
@endif
