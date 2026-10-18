@extends('layouts.auth')
@section('title', 'Masuk · SIPORA v2')
@section('content')
<section class="auth-card">
    <h1 class="mb-6 text-2xl font-bold">Masuk</h1>
    <x-auth-form :action="route('login.store')" recaptcha="login">
        <x-auth-input name="email" label="Email" type="email" autocomplete="email" />
        <x-auth-input name="password" label="Kata sandi" type="password" autocomplete="current-password" />
        <label class="flex min-h-11 items-center gap-2"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
        <x-slot:button>Masuk</x-slot:button>
    </x-auth-form>
    <p class="mt-6 text-sm text-text-secondary">Belum memiliki akun? <a class="font-bold text-interactive underline underline-offset-4" href="{{ route('register') }}">Daftar sekarang</a></p>
    <a class="mt-5 block underline" href="{{ route('password.request') }}">Lupa kata sandi?</a>
    @include('auth.google-link')
</section>
@endsection
