@extends('layouts.public')
@section('content')
<section class="auth-card">
    <h1 class="mb-6 text-2xl font-bold">Reset your password</h1>
    <x-auth-form :action="route('password.email')" recaptcha="forgot_password">
        <x-auth-input name="email" label="Email" type="email" autocomplete="email" />
        <x-slot:button>Send reset link</x-slot:button>
    </x-auth-form>
</section>
@endsection
