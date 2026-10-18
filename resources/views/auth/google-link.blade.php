@if(config('services.google.client_id') && config('services.google.client_secret'))
    <a href="{{ route('google.redirect') }}" class="btn-secondary mt-5 flex w-full items-center justify-center gap-3"><x-google-icon /> Lanjutkan dengan Google</a>
    <p class="mt-3 text-sm text-text-secondary">Masuk dengan Google memverifikasi akses akun. Identitas SIPORA tetap memerlukan peninjauan.</p>
@endif
