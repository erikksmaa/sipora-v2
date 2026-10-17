@php
    $active = match (true) {
        request()->routeIs('home') => 'Beranda',
        request()->routeIs('youth-directory.*', 'portfolio.show') => 'Pemuda',
        request()->routeIs('communities.*') => 'Komunitas',
        request()->routeIs('programs.*') => 'Program',
        request()->routeIs('forum.*') => 'Forum',
        default => null,
    };
    $items = [
        ['Beranda', 'home', 'home'],
        ['Pemuda', 'user', 'youth-directory.index'],
        ['Komunitas', 'users', 'communities.index'],
        ['Program', 'notebook', 'programs.index'],
        ['Forum', 'message-circle', 'forum.index'],
    ];
@endphp
<nav class="mobile-bottom-nav" aria-label="Navigasi utama mobile">
    @foreach($items as [$label, $icon, $routeName])
        <a href="{{ route($routeName) }}" class="mobile-bottom-nav__item" @if($active === $label) aria-current="page" @endif>
            <x-ui.icon :name="$icon" class="size-5" /><span>{{ $label }}</span>
        </a>
    @endforeach
</nav>
