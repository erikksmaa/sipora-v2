<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'SIPORA menghubungkan pemuda Pemalang dengan Activity, Community, Opportunity, Program, dan Youth Portfolio.')">
    @hasSection('canonical')<link rel="canonical" href="@yield('canonical')">@endif
    <title>@yield('title', 'SIPORA — Ruang Tumbuh Pemuda Pemalang')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Big+Shoulders+Display:wght@600;700;800&family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-bg text-text-primary antialiased">
<a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded-lg focus:bg-white focus:p-3">Lewati ke konten</a>
<header x-data="{ open: false, exploreOpen: false }" @keydown.escape.window="open = false; exploreOpen = false" class="sticky top-0 z-50 border-b border-border bg-white/95 backdrop-blur-xl">
    <nav class="mx-auto flex h-[74px] max-w-7xl items-center justify-between gap-6 px-5" aria-label="Navigasi publik">
        <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="SIPORA beranda">
            <span class="grid size-10 place-items-center rounded-xl bg-primary text-sm font-black text-white shadow-xs">SP</span>
            <span><strong class="block text-xl leading-none tracking-tight text-primary">SIPORA</strong><small class="text-[10px] font-semibold uppercase tracking-[0.13em] text-text-muted">Pemuda Pemalang</small></span>
        </a>
        <div class="hidden h-full items-center gap-6 lg:flex">
            <a href="{{ route('home') }}" @class(['public-nav-link h-full', 'public-nav-active' => request()->routeIs('home')]) @if(request()->routeIs('home')) aria-current="page" @endif>Beranda</a>
            <div class="relative flex h-full items-center" @click.outside="exploreOpen = false">
                <button type="button" @click="exploreOpen = !exploreOpen" class="public-nav-link h-full gap-1" :aria-expanded="exploreOpen" aria-controls="explore-menu">Jelajahi <x-ui.icon name="chevron-down" class="size-4 transition" x-bind:class="exploreOpen && 'rotate-180'" /></button>
                <div id="explore-menu" x-cloak x-show="exploreOpen" x-transition class="absolute left-1/2 top-[calc(100%-4px)] w-64 -translate-x-1/2 rounded-2xl border border-border bg-white p-2 shadow-lg">
                    @foreach ([['Activity', 'Temukan kegiatan pemuda', 'activity', 'activities.index'], ['Community', 'Bertumbuh bersama komunitas', 'building', 'communities.index'], ['Opportunity', 'Jelajahi peluang terkurasi', 'briefcase', 'opportunities.index'], ['Program', 'Lihat program kepemudaan', 'notebook', 'programs.index'], ['Pemuda', 'Lihat Portfolio yang dibagikan', 'users', 'youth-directory.index']] as [$label, $description, $icon, $routeName])<a href="{{ route($routeName) }}" @click="exploreOpen = false" @class(['flex min-h-12 items-center gap-3 rounded-xl px-3 py-2.5 transition hover:bg-primary-50', 'bg-primary-50' => request()->routeIs($routeName, str($routeName)->before('.index').'.show') || ($routeName === 'youth-directory.index' && request()->routeIs('portfolio.show'))])><span class="grid size-9 place-items-center rounded-lg bg-surface-soft text-primary shadow-xs"><x-ui.icon :name="$icon" class="size-4" /></span><span><strong class="block text-sm text-text-primary">{{ $label }}</strong><small class="text-xs text-text-muted">{{ $description }}</small></span></a>@endforeach
                </div>
            </div>
            <a href="{{ route('about') }}" @class(['public-nav-link h-full', 'public-nav-active' => request()->routeIs('about')]) @if(request()->routeIs('about')) aria-current="page" @endif>Tentang</a>
            <a href="{{ route('contact') }}" @class(['public-nav-link h-full', 'public-nav-active' => request()->routeIs('contact')]) @if(request()->routeIs('contact')) aria-current="page" @endif>Kontak</a>
            <a href="{{ route('search.index') }}" @class(['grid size-10 place-items-center rounded-[10px] text-text-secondary transition hover:bg-primary-50 hover:text-primary', 'bg-primary-50 text-primary' => request()->routeIs('search.index')]) aria-label="Pencarian" title="Pencarian" @if(request()->routeIs('search.index')) aria-current="page" @endif><x-ui.icon name="search" class="size-4" /></a>
        </div>
        <div class="hidden items-center gap-3 sm:flex">
            @auth
                <a href="{{ route(\App\Support\Auth\HomeRoute::for(auth()->user())) }}" class="btn-secondary !min-h-10 !rounded-lg !px-4">Ruang Saya</a>
            @else
                <a href="{{ route('login') }}" class="px-3 py-2 text-sm font-bold text-primary transition-colors hover:text-accent">Masuk</a><a href="{{ route('register') }}" class="landing-btn-primary !min-h-10 !px-5">Daftar</a>
            @endauth
        </div>
        <button type="button" class="grid size-11 place-items-center rounded-[10px] border border-border bg-white text-primary shadow-xs lg:hidden" @click="open = !open" :aria-expanded="open" aria-controls="public-menu" aria-label="Buka menu"><x-ui.icon name="menu" /></button>
    </nav>
    <div id="public-menu" x-cloak x-show="open" x-transition class="border-t border-border bg-white px-5 py-5 lg:hidden">
        <div class="mx-auto grid max-w-7xl gap-1 text-sm font-semibold">
            <a href="{{ route('home') }}" @click="open = false" @class(['flex min-h-11 items-center rounded-lg px-3 py-2.5 hover:bg-primary-50 hover:text-primary', 'bg-primary-50 text-primary' => request()->routeIs('home')])>Beranda</a><p class="px-3 pt-3 text-[10px] font-extrabold uppercase tracking-[.16em] text-text-muted">Jelajahi</p>
            @foreach (['Activity' => route('activities.index'), 'Community' => route('communities.index'), 'Opportunity' => route('opportunities.index'), 'Program' => route('programs.index'), 'Pemuda' => route('youth-directory.index')] as $label => $href)<a href="{{ $href }}" @click="open = false" class="flex min-h-11 items-center rounded-lg px-3 py-2.5 hover:bg-primary-50 hover:text-primary">{{ $label }}</a>@endforeach
            <a href="{{ route('about') }}" @click="open = false" @class(['flex min-h-11 items-center rounded-lg px-3 py-2.5 hover:bg-primary-50 hover:text-primary','bg-primary-50 text-primary'=>request()->routeIs('about')])>Tentang SIPORA</a>
            <a href="{{ route('contact') }}" @click="open = false" @class(['flex min-h-11 items-center rounded-lg px-3 py-2.5 hover:bg-primary-50 hover:text-primary','bg-primary-50 text-primary'=>request()->routeIs('contact')])>Kontak</a>
            <a href="{{ route('search.index') }}" @click="open = false" class="flex min-h-11 items-center gap-2 rounded-lg px-3 py-2.5 hover:bg-primary-50 hover:text-primary"><x-ui.icon name="search" class="size-4" />Pencarian</a>
            <div class="mt-3 flex gap-3 border-t border-border pt-4">@auth<a href="{{ route(\App\Support\Auth\HomeRoute::for(auth()->user())) }}" class="landing-btn-primary flex-1">Ruang Saya</a>@else<a href="{{ route('login') }}" class="btn-secondary flex-1">Masuk</a><a href="{{ route('register') }}" class="landing-btn-primary flex-1">Daftar</a>@endauth</div>
        </div>
    </div>
</header>
<main id="main">@yield('content')</main>
@hasSection('footer')@yield('footer')@else<x-public.footer/>@endif
</body>
</html>
