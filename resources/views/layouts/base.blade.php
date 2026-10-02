<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIPORA v2')</title><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Big+Shoulders+Display:wght@600;700;800&family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">@vite(['resources/css/app.css', 'resources/js/app.js'])@stack('head')
</head>
<body class="min-h-screen bg-bg text-text-primary antialiased">
<a href="#main" class="sr-only focus:not-sr-only focus:block focus:p-3">Lewati ke konten</a>
<header class="sticky top-0 z-30 border-b border-border bg-white/95 backdrop-blur"><nav class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-5 py-3" aria-label="Navigasi utama">
<a href="{{ route('home') }}" class="text-xl font-bold text-primary">SIPORA <span class="text-sm font-normal">v2</span></a>
@auth @if(auth()->user()->hasRole('youth'))<div class="hidden items-center gap-5 text-sm font-semibold text-text-secondary lg:flex"><a class="hover:text-primary" href="{{ route('youth.home') }}">Beranda</a><a class="hover:text-primary" href="{{ route('search.index') }}">Jelajahi</a><a class="hover:text-primary" href="{{ route('youth.communities.index') }}">Community Saya</a><a class="hover:text-primary" href="{{ route('youth.passport.index') }}">Passport</a><a class="hover:text-primary" href="{{ route('youth.certificates.index') }}">Sertifikat</a><a class="hover:text-primary" href="{{ route('youth.portfolio.show') }}">Portfolio</a><a class="hover:text-primary" href="{{ route('youth.opportunities.bookmarks') }}">Tersimpan</a><a class="hover:text-primary" href="{{ route('youth.profile.show') }}">Profil</a></div>@endif @endauth
<div class="flex items-center gap-3">@auth<x-notification-indicator/><a href="{{ route(\App\Support\Auth\HomeRoute::for(auth()->user())) }}" class="hidden text-sm font-semibold text-text-secondary sm:block">Ruang saya</a><form action="{{ route('logout') }}" method="post">@csrf<button class="btn-secondary !min-h-9 !px-3 !py-1.5 text-sm" type="submit">Keluar</button></form>@else<a href="{{ route('login') }}">Masuk</a><a href="{{ route('register') }}" class="btn">Daftar</a>@endauth</div>
</nav></header>
<main id="main" tabindex="-1" class="mx-auto max-w-7xl px-5 py-8 sm:py-10">@if(session('status'))<div role="status" aria-live="polite" class="mb-6 rounded-xl border border-success/25 bg-success-soft p-4 text-success">{{ session('status') }}</div>@endif @if($errors->any())<div role="alert" aria-live="assertive" class="mb-6 rounded-xl border border-danger/25 bg-danger-soft p-4 text-danger"><p class="font-semibold">Periksa kembali data berikut:</p><ul class="list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif @yield('content')</main>
<footer class="mx-auto max-w-7xl px-5 py-8 text-sm text-text-muted">Dindikpora Kabupaten Pemalang · SIPORA v2</footer>
</body></html>
