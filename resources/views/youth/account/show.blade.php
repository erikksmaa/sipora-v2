@extends('layouts.youth')
@section('title', 'Informasi Akun · SIPORA')
@section('content')
<div class="mx-auto max-w-3xl space-y-5">
    <x-workspace.page-header eyebrow="Akun" title="Informasi Akun" description="Kelola akses masuk dan periksa status akun. Email akun tidak muncul pada Portfolio publik." />
    <section class="sipora-card">
        <h2 class="text-lg font-extrabold">Detail akun</h2>
        <dl class="mt-5 divide-y divide-border text-sm">
            <div class="flex flex-wrap justify-between gap-2 py-3"><dt class="font-semibold text-text-secondary">Email</dt><dd class="break-all font-bold">{{ $user->email }}</dd></div>
            <div class="flex justify-between gap-2 py-3"><dt class="font-semibold text-text-secondary">Status email</dt><dd class="font-bold">{{ $user->hasVerifiedEmail() ? 'Terverifikasi' : 'Belum terverifikasi' }}</dd></div>
            <div class="flex justify-between gap-2 py-3"><dt class="font-semibold text-text-secondary">Terdaftar sejak</dt><dd class="font-bold">{{ $user->created_at?->translatedFormat('d M Y') }}</dd></div>
            <div class="flex justify-between gap-2 py-3"><dt class="font-semibold text-text-secondary">Peran akun</dt><dd class="font-bold">Youth</dd></div>
        </dl>
    </section>
    <section class="sipora-card">
        <h2 class="text-lg font-extrabold">Metode masuk</h2>
        <div class="mt-4 grid gap-3 sm:grid-cols-2">
            <div class="rounded-xl border border-border bg-surface-soft p-4"><strong>Email / Password</strong><p class="mt-1 text-sm text-text-secondary">{{ filled($user->password) ? 'Tersedia' : 'Belum dikonfigurasi' }}</p></div>
            <div class="rounded-xl border border-border bg-surface-soft p-4"><strong>Google</strong><p class="mt-1 text-sm text-text-secondary">{{ $providers->contains('google') ? 'Terhubung' : 'Tidak terhubung' }}</p></div>
        </div>
        <p class="mt-4 text-sm text-text-secondary">Masuk melalui Google tidak sama dengan verifikasi identitas SIPORA.</p>
        <a class="btn-secondary mt-5" href="{{ route('youth.account.security') }}">Kelola keamanan</a>
    </section>
</div>
@endsection
