@php
    $primaryLinks = [
        ['Beranda', 'home', ['home']],
        ['Pemuda', 'youth-directory.index', ['youth-directory.*', 'portfolio.show']],
        ['Komunitas', 'communities.index', ['communities.*']],
        ['Kegiatan', 'activities.index', ['activities.*']],
        ['Program', 'programs.index', ['programs.*']],
        ['Peluang', 'opportunities.index', ['opportunities.*']],
        ['Forum', 'forum.index', ['forum.*']],
    ];
    $secondaryGroups = [
        'Jelajahi' => [['Kegiatan', 'activities.index'], ['Peluang', 'opportunities.index']],
        'Data Ekosistem' => [['Statistik', 'statistics.index'], ['Peta Ekosistem', 'ecosystem-map.index']],
        'Interaksi' => [['Social Feed', 'social.index'], ['Chat Global', 'chat.global']],
        'Informasi' => [['Tentang SIPORA', 'about'], ['Kontak', 'contact']],
    ];
@endphp
<header x-data="publicNavigation" @keydown.escape.window="open ? closeMobile(true) : closeExplore(true)"
    class="public-header sticky top-0 z-50 border-b border-border bg-white/95 backdrop-blur-xl">
    <div class="public-institution-bar border-b border-border">
        <div class="mx-auto flex min-h-12 max-w-7xl items-center justify-between gap-3 px-5">
            <span class="institution-identity">
                <img src="{{ asset('dindikpora-pemalang-logo.png') }}" alt="Lambang Kabupaten Pemalang" class="institution-logo" width="27" height="36">
                <span class="institution-identity-text"><strong>DINDIKPORA</strong><small>Kabupaten Pemalang</small></span>
            </span>
            <div class="flex min-w-0 items-center gap-2">
                @auth<x-notification-indicator /><x-account-dropdown />@else<a href="{{ route('login') }}" class="public-login-link">Masuk</a>@endauth
            </div>
        </div>
    </div>
    <nav class="public-primary-nav mx-auto flex h-[68px] max-w-7xl items-center justify-between gap-5 px-5" aria-label="Navigasi publik">
        <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-3" aria-label="SIPORA beranda">
            <x-brand-logo /><span><strong class="block text-xl leading-none tracking-tight text-primary">SIPORA</strong></span>
        </a>
        <div class="hidden h-full items-center gap-5 xl:flex">
            @foreach($primaryLinks as [$label, $routeName, $patterns])
                <a href="{{ route($routeName) }}" @class(['public-nav-link h-full', 'public-nav-active' => request()->routeIs(...$patterns)]) @if(request()->routeIs(...$patterns)) aria-current="page" @endif>{{ $label }}</a>
            @endforeach
            <div class="relative flex h-full items-center" @click.outside="exploreOpen = false">
                <button x-ref="exploreTrigger" type="button" @click="exploreOpen = !exploreOpen" @keydown.arrow-down.prevent="openExploreAndFocus"
                    @class(['public-nav-link h-full gap-1', 'public-nav-active' => request()->routeIs('statistics.*', 'ecosystem-map.*', 'social.*', 'chat.*', 'about', 'contact')]) :aria-expanded="exploreOpen" aria-controls="explore-menu">Jelajahi<x-ui.icon name="chevron-down" class="size-4 transition" x-bind:class="exploreOpen && 'rotate-180'" /></button>
                <div x-ref="exploreMenu" id="explore-menu" x-cloak x-show="exploreOpen" x-transition.opacity.duration.150ms class="absolute right-0 top-full w-60 rounded-xl border border-border bg-white p-2 shadow-lg">
                    @foreach($secondaryGroups as $groupLabel => $links)
                        @if($groupLabel !== 'Jelajahi')
                            @foreach($links as [$label, $routeName])<a href="{{ route($routeName) }}" class="account-menu-link" @click="closeExplore()">{{ $label }}</a>@endforeach
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="flex shrink-0 items-center gap-2">
            <a href="{{ route('search.index') }}" class="grid size-11 place-items-center rounded-lg text-text-secondary hover:bg-primary-50" aria-label="Pencarian"><x-ui.icon name="search" class="size-5" /></a>
            <x-theme-toggle />
            <button x-ref="mobileTrigger" type="button" class="grid size-11 place-items-center rounded-lg border border-border bg-white text-primary xl:hidden"
                @click="open ? closeMobile(true) : openMobile()" :aria-expanded="open" aria-controls="public-menu" aria-label="Buka menu"><x-ui.icon name="menu" /></button>
        </div>
    </nav>
    <div x-ref="mobileMenu" id="public-menu" x-cloak x-show="open" x-transition.opacity.duration.150ms @keydown.tab="trapMobileFocus($event)" class="overflow-y-auto border-t border-border bg-white p-4 xl:hidden">
        <div class="mx-auto grid max-w-7xl gap-1 text-sm font-semibold">
            <div class="flex items-center justify-between px-3"><strong>Menu SIPORA</strong><button x-ref="mobileClose" type="button" class="grid size-11 place-items-center" @click="closeMobile(true)" aria-label="Tutup menu"><x-ui.icon name="x" /></button></div>
            <div class="public-drawer-primary">
                @foreach($primaryLinks as [$label, $routeName, $patterns])<a href="{{ route($routeName) }}" class="account-menu-link" @if(request()->routeIs(...$patterns)) aria-current="page" @endif>{{ $label }}</a>@endforeach
            </div>
            @foreach($secondaryGroups as $groupLabel => $links)
                <p class="px-3 pt-3 text-[10px] font-extrabold uppercase tracking-[.16em] text-text-muted">{{ $groupLabel }}</p>
                @foreach($links as [$label, $routeName])<a href="{{ route($routeName) }}" class="account-menu-link" @click="closeMobile()">{{ $label }}</a>@endforeach
            @endforeach
            @auth<p class="px-3 pt-3 text-[10px] font-extrabold uppercase tracking-[.16em] text-text-muted">Akun</p><a href="{{ route(\App\Support\Auth\HomeRoute::for(auth()->user())) }}" class="account-menu-link">Buka Workspace</a>@endauth
        </div>
    </div>
</header>
