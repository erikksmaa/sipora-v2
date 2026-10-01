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
<body class="min-h-screen bg-white text-slate-900 antialiased">
<a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded-lg focus:bg-white focus:p-3">Lewati ke konten</a>
<header x-data="{ open: false }" @keydown.escape.window="open = false" class="sticky top-0 z-50 border-b border-indigo-100 bg-white/90 backdrop-blur-xl">
    <nav class="mx-auto flex h-20 max-w-7xl items-center justify-between gap-6 px-5" aria-label="Navigasi publik">
        <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="SIPORA beranda">
            <span class="grid size-10 place-items-center rounded-xl bg-primary text-sm font-black text-white">SP</span>
            <span><strong class="block text-xl leading-none tracking-tight text-primary">SIPORA</strong><small class="text-[10px] font-semibold uppercase tracking-[0.13em] text-slate-500">Pemuda Pemalang</small></span>
        </a>
        <div class="hidden h-full items-center gap-7 lg:flex">
            @foreach ([['Beranda', 'home'], ['Activity', 'activities.index'], ['Community', 'communities.index'], ['Opportunity', 'opportunities.index'], ['Program', 'programs.index']] as [$label, $routeName])
                <a href="{{ route($routeName) }}" @class(['public-nav-link h-full', 'public-nav-active' => request()->routeIs($routeName, str($routeName)->before('.index').'.show')]) @if(request()->routeIs($routeName, str($routeName)->before('.index').'.show')) aria-current="page" @endif>{{ $label }}</a>
            @endforeach
            <a href="{{ route('search.index') }}" @class(['grid size-11 place-items-center rounded-[10px] text-slate-600 transition hover:bg-indigo-50 hover:text-primary', 'bg-indigo-50 text-primary' => request()->routeIs('search.index')]) aria-label="Pencarian" title="Pencarian" @if(request()->routeIs('search.index')) aria-current="page" @endif><x-ui.icon name="search" /></a>
        </div>
        <div class="hidden items-center gap-3 sm:flex">
            @auth
                <a href="{{ route(\App\Support\Auth\HomeRoute::for(auth()->user())) }}" class="btn-secondary !min-h-10 !rounded-lg !px-4">Ruang Saya</a>
            @else
                <a href="{{ route('login') }}" class="px-3 py-2 text-sm font-bold text-primary">Masuk</a><a href="{{ route('register') }}" class="landing-btn-primary !min-h-10 !px-5">Daftar</a>
            @endauth
        </div>
        <button type="button" class="grid size-11 place-items-center rounded-[10px] border border-slate-200 bg-white text-primary shadow-sm lg:hidden" @click="open = !open" :aria-expanded="open" aria-controls="public-menu" aria-label="Buka menu"><x-ui.icon name="menu" /></button>
    </nav>
    <div id="public-menu" x-cloak x-show="open" x-transition class="border-t border-slate-100 bg-white px-5 py-5 lg:hidden">
        <div class="mx-auto grid max-w-7xl gap-1 text-sm font-semibold">
            @foreach (['Beranda' => route('home'), 'Activity' => route('activities.index'), 'Community' => route('communities.index'), 'Opportunity' => route('opportunities.index'), 'Program' => route('programs.index'), 'Pencarian' => route('search.index')] as $label => $href)<a href="{{ $href }}" @click="open = false" class="flex min-h-11 items-center rounded-lg px-3 py-2.5 hover:bg-indigo-50 hover:text-primary">{{ $label }}</a>@endforeach
            <div class="mt-3 flex gap-3 border-t border-slate-100 pt-4">@auth<a href="{{ route(\App\Support\Auth\HomeRoute::for(auth()->user())) }}" class="landing-btn-primary flex-1">Ruang Saya</a>@else<a href="{{ route('login') }}" class="btn-secondary flex-1">Masuk</a><a href="{{ route('register') }}" class="landing-btn-primary flex-1">Daftar</a>@endauth</div>
        </div>
    </div>
</header>
<main id="main">@yield('content')</main>
@hasSection('footer')@yield('footer')@else<x-public.footer/>@endif
</body>
</html>
