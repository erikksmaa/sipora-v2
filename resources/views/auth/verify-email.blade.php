@extends('layouts.auth')
@section('title', 'Verifikasi Email · SIPORA')
@section('content')
<section class="auth-card">
    <p class="mb-3 text-xs font-bold uppercase tracking-wide text-text-secondary">Aktivasi akun</p>
    <h1 class="mb-4 text-2xl font-bold">Verifikasi email Anda</h1>
    <x-auth-flow-feedback />
    <p class="mb-3 text-sm leading-6 text-text-secondary">Periksa tautan verifikasi yang dikirim ke <strong class="break-all text-text-primary">{{ auth()->user()->email }}</strong>. Tautan berlaku selama 60 menit. Periksa juga folder spam.</p>
    <p class="mb-6 text-sm leading-6 text-text-secondary">Verifikasi email mengonfirmasi akses akun. Verifikasi identitas SIPORA dilakukan secara terpisah melalui peninjauan dokumen.</p>
    <form action="{{ route('verification.send') }}" method="post" x-data="{ busy: false }" @submit="busy = true" data-confirm="false">
        @csrf
        <button type="submit" class="btn-accent w-full" :disabled="busy" x-text="busy ? 'Mengirim…' : 'Kirim ulang email verifikasi'">Kirim ulang email verifikasi</button>
    </form>
    <p class="mt-4 text-xs leading-5 text-text-muted">Jika belum menerima email, kirim ulang tautan. Pengiriman dibatasi untuk mencegah spam.</p>
    <form action="{{ route('logout') }}" method="post" class="mt-6" data-confirm="false">@csrf<button class="btn-outline w-full" type="submit">Keluar / gunakan akun lain</button></form>
</section>
@endsection
