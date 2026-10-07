@extends('layouts.auth')
@section('title', 'Sign in · SIPORA v2')
@section('content')
<section class="auth-card">
    <h1 class="mb-6 text-2xl font-bold">Sign in</h1>
    <x-auth-form :action="route('login.store')" recaptcha="login">
        <x-auth-input name="email" label="Email" type="email" autocomplete="email" />
        <x-auth-input name="password" label="Password" type="password" autocomplete="current-password" />
        <label class="flex min-h-11 items-center gap-2"><input type="checkbox" name="remember" value="1"> Remember me</label>
        <x-slot:button>Sign in</x-slot:button>
    </x-auth-form>
    <a class="mt-5 block underline" href="{{ route('password.request') }}">Forgot your password?</a>
    @include('auth.google-link')
</section>
@endsection
