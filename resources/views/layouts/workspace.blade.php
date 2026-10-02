@php
    $workspace = $workspace ?? 'admin';
    $isAdmin = $workspace === 'admin';
    $isYouth = $workspace === 'youth';
    $isManager = $workspace === 'manager';
    $workspaceLabel = match ($workspace) { 'youth' => 'Akun Youth', 'manager' => 'Pengelola Community', 'verifier' => 'Verifier Dinas', default => 'Administrator Dindikpora' };
    $workspaceShortLabel = match ($workspace) { 'youth' => 'Youth', 'manager' => 'Manager', 'verifier' => 'Verifier', default => 'Admin' };
    $workspaceDescription = match ($workspace) { 'youth' => 'Ruang Saya', 'manager' => $organization->name, 'verifier' => 'Kurasi & Pengawasan', default => 'Tata Kelola Kepemudaan' };
    $homeRoute = match ($workspace) { 'youth' => route('youth.home'), 'manager' => route('manager.dashboard', $organization), 'verifier' => route('verifier.dashboard'), default => route('admin.dashboard') };
    $identityIcon = $isYouth ? 'user' : ($isManager ? 'building' : 'shield-check');
    $groups = $isManager ? [
        ['label' => 'Overview', 'items' => [
            ['label' => 'Dashboard', 'icon' => 'layout-dashboard', 'route' => route('manager.dashboard', $organization), 'patterns' => ['manager.dashboard']],
        ]],
        ['label' => 'Community', 'items' => [
            ['label' => 'Profil Community', 'icon' => 'building', 'route' => route('manager.profile.edit', $organization), 'patterns' => ['manager.profile.*']],
            ['label' => 'Anggota', 'icon' => 'users', 'route' => route('manager.members.index', $organization), 'patterns' => ['manager.members.*']],
            ['label' => 'Permintaan bergabung', 'icon' => 'users', 'route' => route('manager.members.index', $organization).'#permintaan', 'patterns' => ['manager.join-requests.*']],
        ]],
        ['label' => 'Activity', 'items' => [
            ['label' => 'Semua Activity', 'icon' => 'clipboard-check', 'route' => route('manager.activities.index', $organization), 'patterns' => ['manager.activities.*']],
            ['label' => 'Buat Activity', 'icon' => 'plus', 'route' => route('manager.activities.create', $organization), 'patterns' => []],
        ]],
        ['label' => 'Program', 'items' => [
            ['label' => 'Semua Program', 'icon' => 'file-text', 'route' => route('manager.programs.index', $organization), 'patterns' => ['manager.programs.*', 'manager.program-proposals.*', 'manager.program-logbooks.*', 'manager.financial-reports.*']],
            ['label' => 'Buat Program', 'icon' => 'plus', 'route' => route('manager.programs.create', $organization), 'patterns' => []],
        ]],
        ['label' => 'Account', 'items' => [
            ['label' => 'Notifikasi', 'icon' => 'bell', 'route' => route('notifications.index'), 'patterns' => ['notifications.*']],
            ['label' => 'Ruang Youth', 'icon' => 'user', 'route' => route('youth.home'), 'patterns' => []],
            ['label' => 'Jelajahi SIPORA', 'icon' => 'globe', 'route' => route('home'), 'patterns' => []],
        ]],
    ] : ($isYouth ? [
        ['label' => 'Overview', 'items' => [
            ['label' => 'Dashboard', 'icon' => 'layout-dashboard', 'route' => route('youth.home'), 'patterns' => ['youth.home']],
        ]],
        ['label' => 'Profile', 'items' => [
            ['label' => 'Profil Saya', 'icon' => 'user', 'route' => route('youth.profile.show'), 'patterns' => ['youth.profile.show', 'youth.profile.edit', 'youth.interests.*', 'youth.skills.*', 'youth.educations.*', 'youth.organization-experiences.*', 'youth.achievements.*', 'youth.identity-verification*']],
            ['label' => 'Portfolio', 'icon' => 'file-text', 'route' => route('youth.portfolio.show'), 'patterns' => ['youth.portfolio.show']],
            ['label' => 'Privasi', 'icon' => 'shield-check', 'route' => route('youth.profile.privacy'), 'patterns' => ['youth.profile.privacy']],
        ]],
        ['label' => 'Activity', 'items' => [
            ['label' => 'Pendaftaran Saya', 'icon' => 'clipboard-check', 'route' => route('youth.activities.index'), 'patterns' => ['youth.activities.*']],
            ['label' => 'Activity Passport', 'icon' => 'badge-check', 'route' => route('youth.passport.index'), 'patterns' => ['youth.passport.*']],
            ['label' => 'Sertifikat', 'icon' => 'award', 'route' => route('youth.certificates.index'), 'patterns' => ['youth.certificates.*']],
        ]],
        ['label' => 'Community', 'items' => [
            ['label' => 'Community Saya', 'icon' => 'building', 'route' => route('youth.communities.index'), 'patterns' => ['youth.communities.*']],
        ]],
        ['label' => 'Opportunity', 'items' => [
            ['label' => 'Tersimpan', 'icon' => 'bookmark', 'route' => route('youth.opportunities.bookmarks'), 'patterns' => ['youth.opportunities.*']],
        ]],
        ['label' => 'Account', 'items' => [
            ['label' => 'Notifikasi', 'icon' => 'bell', 'route' => route('notifications.index'), 'patterns' => ['notifications.*']],
        ]],
    ] : ($isAdmin ? [
        ['label' => 'Overview', 'items' => [
            ['label' => 'Dashboard', 'icon' => 'layout-dashboard', 'route' => route('admin.dashboard'), 'patterns' => ['admin.dashboard']],
        ]],
        ['label' => 'Verifikasi', 'items' => [
            ['label' => 'Identitas Pemuda', 'icon' => 'shield-check', 'route' => route('admin.identity-verifications.index'), 'patterns' => ['admin.identity-verifications.*']],
        ]],
        ['label' => 'Konten', 'items' => [
            ['label' => 'Opportunity', 'icon' => 'sparkles', 'route' => route('admin.opportunities.index'), 'patterns' => ['admin.opportunities.*']],
        ]],
        ['label' => 'Sistem', 'items' => [
            ['label' => 'Notifikasi', 'icon' => 'bell', 'route' => route('notifications.index'), 'patterns' => ['notifications.*']],
        ]],
    ] : [
        ['label' => 'Overview', 'items' => [
            ['label' => 'Dashboard', 'icon' => 'layout-dashboard', 'route' => route('verifier.dashboard'), 'patterns' => ['verifier.dashboard']],
        ]],
        ['label' => 'Verifikasi Ekosistem', 'items' => [
            ['label' => 'Community', 'icon' => 'building', 'route' => route('verifier.community-verifications.index'), 'patterns' => ['verifier.community-verifications.*']],
            ['label' => 'Activity', 'icon' => 'clipboard-check', 'route' => route('verifier.activity-verifications.index'), 'patterns' => ['verifier.activity-verifications.*']],
        ]],
        ['label' => 'Program', 'items' => [
            ['label' => 'Proposal', 'icon' => 'file-text', 'route' => route('verifier.program-proposals.index'), 'patterns' => ['verifier.program-proposals.*']],
            ['label' => 'Monitoring & Logbook', 'icon' => 'notebook', 'route' => route('verifier.program-monitoring.index'), 'patterns' => ['verifier.program-monitoring.*']],
            ['label' => 'E-LPJ Keuangan', 'icon' => 'wallet', 'route' => route('verifier.financial-reports.index'), 'patterns' => ['verifier.financial-reports.*']],
            ['label' => 'Evaluasi Akhir', 'icon' => 'badge-check', 'route' => route('verifier.program-evaluations.index'), 'patterns' => ['verifier.program-evaluations.*']],
        ]],
        ['label' => 'Account', 'items' => [
            ['label' => 'Notifikasi', 'icon' => 'bell', 'route' => route('notifications.index'), 'patterns' => ['notifications.*']],
        ]],
    ]));
    $currentNavLabel = collect($groups)
        ->flatMap(fn (array $group) => $group['items'])
        ->first(fn (array $item) => isset($item['patterns']) && request()->routeIs(...$item['patterns']))['label'] ?? 'Dashboard';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $workspaceShortLabel.' · SIPORA')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Big+Shoulders+Display:wght@600;700;800&family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body x-data="workspaceShell" @keydown.escape.window="closeMobile(true)" class="min-h-screen bg-surface-muted text-slate-900 antialiased">
<a href="#main" class="sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:block focus:rounded-lg focus:bg-white focus:p-3">Lewati ke konten</a>
<div x-cloak x-show="mobileOpen" x-transition.opacity @click="closeMobile(true)" class="fixed inset-0 z-40 bg-primary-dark/45 backdrop-blur-sm lg:hidden" aria-hidden="true"></div>
<div class="flex min-h-screen">
    <aside x-ref="sidebar" @keydown.tab="trapMobileFocus($event)" id="workspace-sidebar" :class="[mobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0', collapsed ? 'lg:w-[76px]' : 'lg:w-[264px]']" class="fixed inset-y-0 left-0 z-50 flex w-[264px] shrink-0 flex-col border-r border-indigo-100 bg-white shadow-xl transition-[width,transform] duration-200 lg:sticky lg:top-0 lg:h-screen lg:shadow-none" aria-label="Navigasi {{ $workspaceShortLabel }}">
        <div class="flex h-20 shrink-0 items-center gap-3 border-b border-indigo-100 px-4" :class="collapsed ? 'lg:justify-center' : ''">
            <a href="{{ $homeRoute }}" class="flex min-w-0 items-center gap-3" aria-label="SIPORA {{ $workspaceShortLabel }}"><span class="grid size-10 shrink-0 place-items-center rounded-xl bg-primary text-white shadow-sm"><x-ui.icon :name="$identityIcon" class="size-5" /></span><span x-show="!collapsed" class="min-w-0"><strong class="block text-lg font-extrabold leading-none text-primary">SIPORA</strong><small class="mt-1 block truncate text-[10px] font-bold uppercase tracking-[0.12em] text-slate-500">{{ $workspaceDescription }}</small></span></a>
            <button type="button" @click="closeMobile(true)" class="ml-auto grid size-10 place-items-center rounded-lg text-slate-500 hover:bg-slate-100 lg:hidden" aria-label="Tutup navigasi"><x-ui.icon name="x" /></button>
        </div>
        @if($isManager)
            @php($actorRole = $organization->memberships()->where('user_id', auth()->id())->where('membership_status', \App\Models\OrganizationMembership::STATUS_ACTIVE)->value('access_role'))
            <div class="mx-3 mt-4 rounded-xl border border-indigo-100 bg-indigo-50/80 p-3" :class="collapsed ? 'lg:p-2' : ''">
                <div class="flex items-center gap-3"><span class="grid size-9 shrink-0 place-items-center overflow-hidden rounded-lg bg-primary text-sm font-bold text-white">@if($organization->logo_path)<img src="{{ route('communities.logo', $organization) }}" alt="" class="size-full object-cover">@else{{ mb_substr($organization->name, 0, 1) }}@endif</span><div x-show="!collapsed" class="min-w-0"><p class="truncate text-xs font-bold text-primary" title="{{ $organization->name }}">{{ $organization->name }}</p><p class="text-[11px] capitalize text-slate-600">{{ $actorRole }} · peran Community</p></div></div>
                @if(($managedCommunities ?? collect())->count() > 1)<div x-show="!collapsed" class="mt-3"><label for="community-switcher" class="sr-only">Ganti Community</label><select id="community-switcher" class="field !min-h-10 w-full text-xs" @change="window.location.assign($event.target.value)">@foreach($managedCommunities as $managedCommunity)<option value="{{ route('manager.dashboard', $managedCommunity) }}" @selected($managedCommunity->is($organization))>{{ $managedCommunity->name }}</option>@endforeach</select></div>@endif
            </div>
        @else
            <div class="mx-3 mt-4 rounded-xl border border-indigo-100 bg-indigo-50/80 p-3" :class="collapsed ? 'lg:p-2' : ''"><div class="flex items-center gap-2"><span class="relative grid size-8 shrink-0 place-items-center rounded-lg bg-white text-primary"><x-ui.icon name="badge-check" class="size-4" /><span class="absolute -right-0.5 -top-0.5 size-2 rounded-full bg-emerald-500 ring-2 ring-white"></span></span><div x-show="!collapsed"><p class="text-[10px] font-extrabold uppercase tracking-wider text-primary">{{ $workspaceLabel }}</p><p class="mt-0.5 text-[10px] font-semibold text-emerald-700">Workspace aktif</p></div></div></div>
        @endif
        <nav class="flex-1 space-y-6 overflow-y-auto px-3 py-5" aria-label="Menu {{ $workspaceShortLabel }}">
            @foreach($groups as $group)<section><p x-show="!collapsed" class="mb-2 px-3 text-[10px] font-extrabold uppercase tracking-[0.16em] text-slate-400">{{ $group['label'] }}</p><div class="space-y-1">
                @foreach($group['items'] as $item) @php($active = isset($item['patterns']) && request()->routeIs(...$item['patterns']))
                    @if($item['disabled'] ?? false)<span class="workspace-nav-link workspace-nav-disabled" title="{{ $item['label'] }} — belum tersedia"><x-ui.icon :name="$item['icon']" /><span x-show="!collapsed">{{ $item['label'] }}</span></span>
                    @else<a href="{{ $item['route'] }}" @class(['workspace-nav-link', 'workspace-nav-active' => $active]) @if($active) aria-current="page" @endif title="{{ $item['label'] }}" @click="closeMobile"><x-ui.icon :name="$item['icon']" /><span x-show="!collapsed">{{ $item['label'] }}</span></a>@endif
                @endforeach
            </div></section>@endforeach
        </nav>
        <div class="hidden border-t border-slate-100 p-3 lg:block"><button type="button" @click="toggleCollapsed" class="workspace-nav-link w-full" :aria-label="collapsed ? 'Perluas sidebar' : 'Ciutkan sidebar'" :title="collapsed ? 'Perluas sidebar' : 'Ciutkan sidebar'"><x-ui.icon name="panel-left" /><span x-show="!collapsed">Ciutkan sidebar</span></button></div>
    </aside>
    <div class="min-w-0 flex-1">
        <header class="sticky top-0 z-30 flex h-20 items-center justify-between border-b border-indigo-100 bg-white/90 px-4 backdrop-blur-xl sm:px-6 lg:px-8"><div class="flex min-w-0 items-center gap-3"><button x-ref="mobileTrigger" type="button" class="grid size-11 shrink-0 place-items-center rounded-[10px] border border-slate-200 bg-white text-primary shadow-sm lg:hidden" @click="openMobile" aria-label="Buka navigasi" aria-controls="workspace-sidebar" :aria-expanded="mobileOpen"><x-ui.icon name="menu" /></button><div class="min-w-0"><p class="truncate text-xs font-extrabold uppercase tracking-[0.12em] text-slate-400">{{ $isYouth ? 'SIPORA Pemuda Pemalang' : ($isManager ? 'Workspace Community' : 'Dindikpora Kabupaten Pemalang') }}</p><p class="truncate text-sm font-bold text-primary-dark sm:text-base">{{ $workspaceDescription }}</p></div></div><div class="flex items-center gap-2 sm:gap-3">@if($isYouth || $isManager)<a class="btn-ghost !px-3" href="{{ route('home') }}" aria-label="Jelajahi SIPORA"><x-ui.icon name="globe" /><span class="hidden md:inline">Jelajahi</span></a>@endif @if($isManager)<a class="btn-ghost !px-3" href="{{ route('youth.home') }}" aria-label="Buka Ruang Youth"><x-ui.icon name="user" /><span class="hidden xl:inline">Ruang Youth</span></a>@endif<x-notification-indicator/><div class="hidden text-right text-sm md:block"><strong class="block max-w-48 truncate text-primary-dark">{{ auth()->user()->name }}</strong><small class="text-slate-500">{{ $workspaceShortLabel }}</small></div><form method="post" action="{{ route('logout') }}">@csrf<button class="btn-ghost !px-3" type="submit"><x-ui.icon name="log-out" /><span class="hidden sm:inline">Keluar</span></button></form></div></header>
        <noscript><nav class="border-b border-amber-200 bg-amber-50 px-4 py-3 text-sm lg:hidden" aria-label="Navigasi tanpa JavaScript">JavaScript tidak aktif. <a class="font-bold text-primary underline" href="{{ $homeRoute }}">Buka dashboard</a>.</nav></noscript>
        <main id="main" class="mx-auto max-w-[1600px] p-4 sm:p-6 lg:p-8"><nav aria-label="Breadcrumb" class="mb-5 flex items-center gap-2 text-xs font-semibold text-slate-500"><span>{{ $workspaceShortLabel }}</span><x-ui.icon name="chevron-right" class="size-3.5 text-slate-400" /><span aria-current="page" class="text-primary">{{ $currentNavLabel }}</span></nav>@if(session('status'))<div role="status" class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800">{{ session('status') }}</div>@endif @if($errors->any())<div role="alert" class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800"><p class="font-bold">Periksa kembali data berikut:</p><ul class="mt-2 list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif @yield('workspace-content')</main>
    </div>
</div>
</body>
</html>
