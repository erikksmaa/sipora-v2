@extends('layouts.public')
@section('title', 'Register · SIPORA v2')
@section('content')
<section class="auth-card">
    <h1 class="mb-6 text-2xl font-bold">Create your account</h1>
    <x-auth-form :action="route('register.store')" recaptcha="register">
        <x-auth-input name="name" label="Name" autocomplete="name" />
        <x-auth-input name="email" label="Email" type="email" autocomplete="email" />
        <x-auth-input name="password" label="Password (at least 12 characters)" type="password" autocomplete="new-password" />
        <x-auth-input name="password_confirmation" label="Confirm password" type="password" autocomplete="new-password" />
        <x-slot:button>Register</x-slot:button>
    </x-auth-form>
    @include('auth.google-link')
</section>
@endsection
