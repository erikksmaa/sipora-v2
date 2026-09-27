@extends('layouts.public')
@section('content')
<section class="auth-card">
    <h1 class="mb-4 text-2xl font-bold">Verify your email</h1>
    <p class="mb-6 text-slate-600">Open the verification link sent to your email address to access your workspace.</p>
    <form action="{{ route('verification.send') }}" method="post">@csrf<button type="submit" class="btn">Resend verification link</button></form>
</section>
@endsection
