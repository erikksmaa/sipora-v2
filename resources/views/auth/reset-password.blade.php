@extends('layouts.public')
@section('content')
<section class="auth-card">
    <h1 class="mb-6 text-2xl font-bold">Choose a new password</h1>
    <x-auth-form :action="route('password.update')">
        <input type="hidden" name="token" value="{{ $token }}">
        <x-auth-input name="email" label="Email" type="email" autocomplete="email" :value="request('email')" />
        <x-auth-input name="password" label="Password (at least 12 characters)" type="password" autocomplete="new-password" />
        <x-auth-input name="password_confirmation" label="Confirm password" type="password" autocomplete="new-password" />
        <x-slot:button>Reset password</x-slot:button>
    </x-auth-form>
</section>
@endsection
