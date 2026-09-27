<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="SIPORA menghubungkan pemuda Pemalang dengan kegiatan, komunitas, peluang, dan program pengembangan diri.">
    <title>@yield('title', 'SIPORA — Ruang Tumbuh Pemuda Pemalang')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-white text-slate-900 antialiased">
<a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded-lg focus:bg-white focus:p-3">Lewati ke konten</a>
<header x-data="{ open: false }" class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/95 backdrop-blur">
    <nav class="mx-auto flex h-20 max-w-7xl items-center justify-between gap-6 px-5" aria-label="Navigasi publik">
        <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="SIPORA beranda">
            <span class="grid size-10 place-items-center rounded-xl bg-[#243378] text-sm font-black text-white">SP</span>
            <span><strong class="block text-xl leading-none tracking-tight text-[#243378]">SIPORA</strong><small class="text-[10px] font-semibold uppercase tracking-[0.13em] text-slate-500">Pemuda Pemalang</small></span>
        </a>
        <div class="hidden items-center gap-7 text-sm font-semibold text-slate-600 lg:flex">
            <a href="#beranda" class="text-[#243378]">Beranda</a><a href="#kegiatan" class="transition hover:text-[#243378]">Kegiatan</a><a href="#komunitas" class="transition hover:text-[#243378]">Komunitas</a><a href="#peluang" class="transition hover:text-[#243378]">Peluang</a><a href="#program" class="transition hover:text-[#243378]">Program</a><a href="#tentang" class="transition hover:text-[#243378]">Tentang</a>
        </div>
        <div class="hidden items-center gap-3 sm:flex">
            @auth
                <a href="{{ route(\App\Support\Auth\HomeRoute::for(auth()->user())) }}" class="btn-secondary !min-h-10 !rounded-lg !px-4">Ruang Saya</a>
            @else
                <a href="{{ route('login') }}" class="px-3 py-2 text-sm font-bold text-[#243378]">Masuk</a><a href="{{ route('register') }}" class="landing-btn-primary !min-h-10 !px-5">Daftar</a>
            @endauth
        </div>
        <button type="button" class="grid size-11 place-items-center rounded-xl border border-slate-200 lg:hidden" @click="open = !open" :aria-expanded="open" aria-controls="public-menu" aria-label="Buka menu"><svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
    </nav>
    <div id="public-menu" x-cloak x-show="open" x-transition class="border-t border-slate-100 bg-white px-5 py-5 lg:hidden">
        <div class="mx-auto grid max-w-7xl gap-1 text-sm font-semibold">
            @foreach (['Beranda' => '#beranda', 'Kegiatan' => '#kegiatan', 'Komunitas' => '#komunitas', 'Peluang' => '#peluang', 'Program' => '#program', 'Tentang' => '#tentang'] as $label => $href)<a href="{{ $href }}" @click="open = false" class="rounded-lg px-3 py-2.5 hover:bg-slate-50">{{ $label }}</a>@endforeach
            <div class="mt-3 flex gap-3 border-t border-slate-100 pt-4">@auth<a href="{{ route(\App\Support\Auth\HomeRoute::for(auth()->user())) }}" class="landing-btn-primary flex-1">Ruang Saya</a>@else<a href="{{ route('login') }}" class="btn-secondary flex-1">Masuk</a><a href="{{ route('register') }}" class="landing-btn-primary flex-1">Daftar</a>@endauth</div>
        </div>
    </div>
</header>
<main id="main">@yield('content')</main>
@yield('footer')
</body>
</html>
