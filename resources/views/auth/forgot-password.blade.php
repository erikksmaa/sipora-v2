@extends('layouts.auth')
@section('title', 'Lupa Kata sandi · SIPORA')
@section('content')
<section class="auth-card">
    <h1 class="mb-3 text-2xl font-bold">Lupa kata sandi?</h1>
    <p class="mb-6 text-sm leading-6 text-text-secondary">Masukkan email akun SIPORA. Kami akan mengirim tautan untuk membuat kata sandi baru jika akun tersedia.</p>
    <x-auth-flow-feedback />
    <x-auth-form :action="route('password.email')" recaptcha="forgot_password">
        <x-auth-input name="email" label="Email akun" type="email" autocomplete="email" />
        <x-slot:button>Kirim Tautan Reset</x-slot:button>
    </x-auth-form>
    <a href="{{ route('login') }}" class="btn-outline mt-6 w-full">Kembali ke Masuk</a>
</section>
@endsection
