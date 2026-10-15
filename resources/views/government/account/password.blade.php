@extends($forced ? 'layouts.auth' : ($workspace === 'admin' ? 'layouts.admin' : 'layouts.verifier'))
@section('title', 'Kelola Password · SIPORA')
@section($forced || $workspace === 'admin' ? 'content' : 'verifier-content')
<section class="{{ $forced ? 'auth-card' : 'sipora-card mx-auto max-w-2xl' }}">
    <h1 class="text-2xl font-bold text-text-primary">{{ $forced ? 'Buat Password Baru' : 'Kelola Password' }}</h1>
    <p class="mt-3 text-sm leading-6 text-text-secondary">{{ $forced ? 'Anda wajib mengganti password sementara sebelum mengakses workspace. Tidak perlu memasukkan password sementara lagi.' : 'Ubah password akun Anda sendiri. Sesi lain akan diakhiri setelah password diperbarui.' }}</p>
    @if($forced)<x-auth-flow-feedback />@endif
    <form method="POST" action="{{ route($forced ? 'internal.password.update' : $workspace.'.account.password.update') }}" class="mt-6 space-y-5">
        @csrf @method('PUT')
        @if(!$forced && $hasPassword)<label class="block"><span class="field-label">Password saat ini</span><input class="field" type="password" name="current_password" autocomplete="current-password" maxlength="72" required></label>@endif
        <label class="block"><span class="field-label">Password baru</span><input class="field" type="password" name="password" autocomplete="new-password" minlength="8" maxlength="72" required></label>
        <label class="block"><span class="field-label">Konfirmasi password baru</span><input class="field" type="password" name="password_confirmation" autocomplete="new-password" minlength="8" maxlength="72" required></label>
        <p class="text-xs text-text-secondary">Minimal 8 dan maksimal 72 karakter. Gunakan password yang berbeda dari sebelumnya.</p>
        <button class="btn-primary" type="submit">Simpan Password</button>
    </form>
    @if($forced)<form method="POST" action="{{ route('logout') }}" class="mt-5">@csrf<button class="btn-outline" type="submit">Keluar</button></form>@endif
</section>
@endsection
