<header x-data="publicNavigation" @keydown.escape.window="open ? closeMobile(true) : closeExplore(true)"
    class="sticky top-0 z-50 border-b border-border bg-white/95 backdrop-blur-xl">
    <nav class="mx-auto flex h-[74px] max-w-7xl items-center justify-between gap-6 px-5" aria-label="Navigasi publik">
        <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="SIPORA beranda">
            <x-brand-logo />
            <span><strong class="block text-xl leading-none tracking-tight text-primary">SIPORA</strong><small
                    class="text-[10px] font-semibold uppercase tracking-[0.13em] text-text-muted">Pemuda
                    Pemalang</small></span>
        </a>
        <div class="hidden h-full items-center gap-6 lg:flex">
            <a href="{{ route('home') }}" @class(['public-nav-link h-full', 'public-nav-active' => request()->routeIs('home')]) @if(request()->routeIs('home')) aria-current="page" @endif>Beranda</a>
            <div class="relative flex h-full items-center" @click.outside="exploreOpen = false">
                <button x-ref="exploreTrigger" type="button" @click="exploreOpen = !exploreOpen"
                    @keydown.arrow-down.prevent="openExploreAndFocus" @class(['public-nav-link h-full gap-1', 'public-nav-active' => request()->routeIs('activities.*', 'communities.*', 'opportunities.*', 'programs.*', 'youth-directory.*', 'portfolio.show', 'ecosystem-map.*')]) :aria-expanded="exploreOpen"
                    aria-haspopup="menu" aria-controls="explore-menu">Jelajahi <x-ui.icon name="chevron-down"
                        class="size-4 transition" x-bind:class="exploreOpen && 'rotate-180'" /></button>
                <div x-ref="exploreMenu" id="explore-menu" x-cloak x-show="exploreOpen" x-transition role="menu"
                    class="absolute left-1/2 top-[calc(100%-4px)] w-64 -translate-x-1/2 rounded-2xl border border-border bg-white p-2 shadow-lg">
                    @foreach ([['Activity', 'Temukan kegiatan pemuda', 'activity', 'activities.index'], ['Community', 'Bertumbuh bersama komunitas', 'building', 'communities.index'], ['Opportunity', 'Jelajahi peluang terkurasi', 'briefcase', 'opportunities.index'], ['Program', 'Lihat program kepemudaan', 'notebook', 'programs.index'], ['Pemuda', 'Lihat Portfolio yang dibagikan', 'users', 'youth-directory.index'], ['Peta Ekosistem', 'Cakupan per kecamatan', 'globe', 'ecosystem-map.index']] as [$label, $description, $icon, $routeName])<a
                        href="{{ route($routeName) }}" @click="exploreOpen = false" @class(['flex min-h-12 items-center gap-3 rounded-xl px-3 py-2.5 transition hover:bg-primary-50', 'bg-primary-50' => request()->routeIs($routeName, str($routeName)->before('.index') . '.show') || ($routeName === 'youth-directory.index' && request()->routeIs('portfolio.show'))])><span
                            class="grid size-9 place-items-center rounded-lg bg-surface-soft text-primary shadow-xs"><x-ui.icon
                                :name="$icon" class="size-4" /></span><span><strong
                                class="block text-sm text-text-primary">{{ $label }}</strong><small
                    class="text-xs text-text-muted">{{ $description }}</small></span></a>@endforeach
                </div>
            </div>
            <a href="{{ route('forum.index') }}" @class(['public-nav-link h-full', 'public-nav-active' => request()->routeIs('forum.*')]) @if(request()->routeIs('forum.*')) aria-current="page" @endif>Forum</a>
            <a href="{{ route('statistics.index') }}" @class(['public-nav-link h-full', 'public-nav-active' => request()->routeIs('statistics.index')]) @if(request()->routeIs('statistics.index')) aria-current="page" @endif>Statistik Pemuda</a>
            <a href="{{ route('about') }}" @class(['public-nav-link h-full', 'public-nav-active' => request()->routeIs('about')]) @if(request()->routeIs('about')) aria-current="page" @endif>Tentang</a>
            <a href="{{ route('contact') }}" @class(['public-nav-link h-full', 'public-nav-active' => request()->routeIs('contact')]) @if(request()->routeIs('contact')) aria-current="page" @endif>Kontak</a>
            <a href="{{ route('search.index') }}" @class(['grid size-10 place-items-center rounded-[10px] text-text-secondary transition hover:bg-primary-50 hover:text-primary', 'bg-primary-50 text-primary' => request()->routeIs('search.index')]) aria-label="Pencarian" title="Pencarian"
                @if(request()->routeIs('search.index')) aria-current="page" @endif><x-ui.icon name="search"
                    class="size-4" /></a>
        </div>
        <div class="flex items-center gap-2 sm:gap-3">
            @auth
                <x-notification-indicator />
                <a href="{{ route(\App\Support\Auth\HomeRoute::for(auth()->user())) }}"
                    class="btn-secondary hidden sm:inline-flex">Ruang Saya</a>
                <a href="{{ auth()->user()->hasRole('youth') ? route('youth.account.show') : route(\App\Support\Auth\HomeRoute::for(auth()->user())) }}" class="hidden size-10 place-items-center rounded-sm border border-border bg-surface text-text-primary sm:grid" aria-label="Akun saya"><x-ui.icon name="user" class="size-5" /></a>
                    <x-theme-toggle /><button x-ref="mobileTrigger"
                type="button"
                class="grid size-11 place-items-center rounded-[10px] border border-border bg-white text-primary shadow-xs lg:hidden"
                @click="open ? closeMobile(true) : openMobile()" :aria-expanded="open" aria-controls="public-menu"
                aria-label="Buka menu"><x-ui.icon name="menu" /></button>
            @else
                <a href="{{ route('login') }}" class="btn-outline hidden !min-h-10 !px-5 sm:inline-flex">Masuk</a><a
                    href="{{ route('register') }}" class="landing-btn-primary hidden !min-h-10 !px-5 sm:inline-flex">Daftar</a>
                    <x-theme-toggle /><button x-ref="mobileTrigger"
                type="button"
                class="grid size-11 place-items-center rounded-[10px] border border-border bg-white text-primary shadow-xs lg:hidden"
                @click="open ? closeMobile(true) : openMobile()" :aria-expanded="open" aria-controls="public-menu"
                aria-label="Buka menu"><x-ui.icon name="menu" /></button>
            @endauth
        </div>

    </nav>
    <div x-ref="mobileMenu" id="public-menu" x-cloak x-show="open" x-transition @keydown.tab="trapMobileFocus($event)"
        class="max-h-[calc(100dvh-74px)] overflow-y-auto border-t border-border bg-white px-5 py-5 lg:hidden">
        <div class="mx-auto grid max-w-7xl gap-1 text-sm font-semibold">
            <div class="mb-2 flex items-center justify-between px-3"><span
                    class="text-xs font-bold uppercase tracking-wider text-text-muted">Navigasi</span><button
                    x-ref="mobileClose" type="button"
                    class="grid size-10 place-items-center rounded-lg text-text-secondary hover:bg-primary-50 hover:text-primary"
                    @click="closeMobile(true)" aria-label="Tutup menu"><x-ui.icon name="x" /></button></div>
            <a href="{{ route('home') }}" @click="open = false" @class(['flex min-h-11 items-center rounded-lg px-3 py-2.5 hover:bg-primary-50 hover:text-primary', 'bg-primary-50 text-primary' => request()->routeIs('home')])>Beranda</a>
            <p class="px-3 pt-3 text-[10px] font-extrabold uppercase tracking-[.16em] text-text-muted">Jelajahi</p>
            @foreach (['Activity' => route('activities.index'), 'Community' => route('communities.index'), 'Opportunity' => route('opportunities.index'), 'Program' => route('programs.index'), 'Pemuda' => route('youth-directory.index'), 'Peta Ekosistem' => route('ecosystem-map.index')] as $label => $href)<a
                href="{{ $href }}" @click="open = false"
            class="flex min-h-11 items-center rounded-lg px-3 py-2.5 hover:bg-primary-50 hover:text-primary">{{ $label }}</a>@endforeach
            <a href="{{ route('statistics.index') }}" @click="open = false" @class(['flex min-h-11 items-center rounded-lg px-3 py-2.5 hover:bg-primary-50 hover:text-primary', 'bg-primary-50 text-primary' => request()->routeIs('statistics.index')])>Statistik Pemuda</a>
            <a href="{{ route('forum.index') }}" @click="open = false" @class(['flex min-h-11 items-center rounded-lg px-3 py-2.5 hover:bg-primary-50 hover:text-primary', 'bg-primary-50 text-primary' => request()->routeIs('forum.*')])>Forum</a>
            <a href="{{ route('about') }}" @click="open = false" @class(['flex min-h-11 items-center rounded-lg px-3 py-2.5 hover:bg-primary-50 hover:text-primary', 'bg-primary-50 text-primary' => request()->routeIs('about')])>Tentang SIPORA</a>
            <a href="{{ route('contact') }}" @click="open = false" @class(['flex min-h-11 items-center rounded-lg px-3 py-2.5 hover:bg-primary-50 hover:text-primary', 'bg-primary-50 text-primary' => request()->routeIs('contact')])>Kontak</a>
            <a href="{{ route('search.index') }}" @click="open = false"
                class="flex min-h-11 items-center gap-2 rounded-lg px-3 py-2.5 hover:bg-primary-50 hover:text-primary"><x-ui.icon
                    name="search" class="size-4" />Pencarian</a>
            @auth<div class="flex flex-wrap gap-3 border-t border-border pt-4"><x-notification-indicator /><a href="{{ auth()->user()->hasRole('youth') ? route('youth.account.show') : route(\App\Support\Auth\HomeRoute::for(auth()->user())) }}" class="btn-secondary flex-1">Akun saya</a></div>@endauth
            <div class="mt-3 flex gap-3 border-t border-border pt-4">@auth<a
                href="{{ route(\App\Support\Auth\HomeRoute::for(auth()->user())) }}"
            class="landing-btn-primary flex-1">Ruang Saya</a>@else<a href="{{ route('login') }}"
                        class="btn-secondary flex-1">Masuk</a><a href="{{ route('register') }}"
                    class="landing-btn-primary flex-1">Daftar</a>@endauth</div>
        </div>
    </div>
</header>
