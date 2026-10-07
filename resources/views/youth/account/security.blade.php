@extends('layouts.youth')
@section('title', 'Keamanan Akun · SIPORA')
@section('content')
<div class="mx-auto max-w-2xl space-y-5">
    <x-workspace.page-header eyebrow="Akun" :title="$hasPassword ? 'Ubah Password' : 'Buat Password'" description="Password lokal memberi pilihan lain untuk masuk ke akun SIPORA." />
    <section class="sipora-card">
        <form method="post" action="{{ route('youth.account.security.update') }}" class="space-y-5">
            @csrf @method('PUT')
            @if($hasPassword)
                <label><span class="field-label">Password saat ini</span><input class="field" type="password" name="current_password" autocomplete="current-password" required>@error('current_password')<span class="field-error">{{ $message }}</span>@enderror</label>
            @else
                <p class="rounded-xl border border-border bg-surface-soft p-4 text-sm text-text-secondary">Akun ini belum memiliki password lokal. Buat password tanpa perlu memasukkan password saat ini.</p>
            @endif
            <label><span class="field-label">Password baru</span><input class="field" type="password" name="password" autocomplete="new-password" minlength="8" required>@error('password')<span class="field-error">{{ $message }}</span>@enderror</label>
            <label><span class="field-label">Ulangi password baru</span><input class="field" type="password" name="password_confirmation" autocomplete="new-password" minlength="8" required></label>
            <button class="btn" type="submit">{{ $hasPassword ? 'Ubah Password' : 'Buat Password' }}</button>
        </form>
    </section>
</div>
@endsection
