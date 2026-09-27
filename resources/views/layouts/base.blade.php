<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIPORA v2')</title>@vite(['resources/css/app.css', 'resources/js/app.js'])@stack('head')
</head>
<body class="min-h-screen bg-[#f5f6fc] text-slate-900 antialiased">
<a href="#main" class="sr-only focus:not-sr-only focus:block focus:p-3">Lewati ke konten</a>
<header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/95 backdrop-blur"><nav class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-5 py-3" aria-label="Navigasi utama">
<a href="{{ route('home') }}" class="text-xl font-bold text-[#243378]">SIPORA <span class="text-sm font-normal">v2</span></a>
@auth @if(auth()->user()->hasRole('youth'))<div class="hidden items-center gap-7 text-sm font-semibold text-slate-600 lg:flex"><a class="hover:text-[#243378]" href="{{ route('youth.home') }}">Beranda</a><span class="cursor-not-allowed text-slate-400" title="Tersedia pada fase berikutnya">Eksplor</span><span class="cursor-not-allowed text-slate-400" title="Tersedia pada fase berikutnya">Kegiatan Saya</span><span class="cursor-not-allowed text-slate-400" title="Tersedia pada fase berikutnya">Komunitas</span><a class="hover:text-[#243378]" href="{{ route('youth.profile.show') }}">Profil</a></div>@endif @endauth
<div class="flex items-center gap-3">@auth<a href="{{ route(\App\Support\Auth\HomeRoute::for(auth()->user())) }}" class="hidden text-sm font-semibold text-slate-600 sm:block">Ruang saya</a><form action="{{ route('logout') }}" method="post">@csrf<button class="btn-secondary !min-h-9 !px-3 !py-1.5 text-sm" type="submit">Keluar</button></form>@else<a href="{{ route('login') }}">Masuk</a><a href="{{ route('register') }}" class="btn">Daftar</a>@endauth</div>
</nav></header>
<main id="main" class="mx-auto max-w-7xl px-5 py-8 sm:py-10">@if(session('status'))<div role="status" class="mb-6 rounded-xl border border-green-300 bg-green-50 p-4">{{ session('status') }}</div>@endif @if($errors->any())<div role="alert" class="mb-6 rounded-xl border border-red-300 bg-red-50 p-4"><p class="font-semibold">Periksa kembali data berikut:</p><ul class="list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif @yield('content')</main>
<footer class="mx-auto max-w-7xl px-5 py-8 text-sm text-slate-500">Dindikpora Kabupaten Pemalang · SIPORA v2</footer>
</body></html>
