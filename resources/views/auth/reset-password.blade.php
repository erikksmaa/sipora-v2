@extends('layouts.auth')
@section('title', 'Atur ulang kata sandi · SIPORA')
@section('content')
<section class="auth-card">
    <h1 class="mb-3 text-2xl font-bold">Buat kata sandi baru</h1>
    <p class="mb-6 text-sm leading-6 text-text-secondary">Gunakan minimal 8 karakter, maksimal 72 karakter, dan isi konfirmasi yang sama. Tautan reset berlaku selama 60 menit dan hanya dapat digunakan sekali.</p>
    <x-auth-flow-feedback />
    <x-auth-form :action="route('password.update')">
        <input type="hidden" name="token" value="{{ $token }}">
        <x-auth-input name="email" label="Email akun" type="email" autocomplete="email" :value="old('email', request('email'))" />
        <x-auth-input name="password" label="Kata sandi baru" type="password" autocomplete="new-password" />
        <x-auth-input name="password_confirmation" label="Konfirmasi kata sandi baru" type="password" autocomplete="new-password" />
        <x-slot:button>Simpan kata sandi baru</x-slot:button>
    </x-auth-form>
    <a href="{{ route('password.request') }}" class="btn-outline mt-6 w-full">Minta tautan reset baru</a>
</section>
@endsection
