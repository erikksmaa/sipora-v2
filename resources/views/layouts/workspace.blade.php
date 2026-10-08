@php
    $workspace = $workspace ?? 'admin';
    $isAdmin = $workspace === 'admin';
    $isYouth = $workspace === 'youth';
    $isManager = $workspace === 'manager';
    $workspaceLabel = match ($workspace) { 'youth' => 'Akun Youth', 'manager' => 'Pengelola Community', 'verifier' => 'Verifier Dinas', default => 'Administrator Dindikpora' };
    $workspaceShortLabel = match ($workspace) { 'youth' => 'Youth', 'manager' => 'Community', 'verifier' => 'Verifier', default => 'Admin' };
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
            ['label' => 'Permintaan Bergabung', 'icon' => 'users', 'route' => route('manager.join-requests.index', $organization), 'patterns' => ['manager.join-requests.*']],
        ]],
        ['label' => 'Activity', 'items' => [
            ['label' => 'Activity', 'icon' => 'clipboard-check', 'route' => route('manager.activities.index', $organization), 'patterns' => ['manager.activities.index', 'manager.activities.create', 'manager.activities.show', 'manager.activities.edit']],
            ['label' => 'Sesi', 'icon' => 'calendar', 'route' => route('manager.operations.sessions', $organization), 'patterns' => ['manager.operations.sessions', 'manager.activities.sessions.*']],
            ['label' => 'Peserta', 'icon' => 'users', 'route' => route('manager.operations.participants', $organization), 'patterns' => ['manager.operations.participants', 'manager.activities.participants.*']],
            ['label' => 'Presensi', 'icon' => 'clipboard-check', 'route' => route('manager.operations.attendance', $organization), 'patterns' => ['manager.operations.attendance', 'manager.activities.attendance.*']],
        ]],
        ['label' => 'Program', 'items' => [
            ['label' => 'Program', 'icon' => 'file-text', 'route' => route('manager.programs.index', $organization), 'patterns' => ['manager.programs.*']],
            ['label' => 'Proposal', 'icon' => 'file-text', 'route' => route('manager.operations.proposals', $organization), 'patterns' => ['manager.operations.proposals', 'manager.program-proposals.*']],
            ['label' => 'Logbook', 'icon' => 'notebook', 'route' => route('manager.operations.logbooks', $organization), 'patterns' => ['manager.operations.logbooks', 'manager.program-logbooks.*']],
            ['label' => 'E-LPJ', 'icon' => 'wallet', 'route' => route('manager.operations.financial-reports', $organization), 'patterns' => ['manager.operations.financial-reports', 'manager.financial-reports.*']],
        ]],
        ['label' => 'Akun', 'items' => [
            ['label' => 'Notifikasi', 'icon' => 'bell', 'route' => route('manager.notifications.index', $organization), 'patterns' => ['manager.notifications.*']],
        ]],
    ] : ($isYouth ? [
        ['label' => 'Overview', 'items' => [
            ['label' => 'Dashboard', 'icon' => 'layout-dashboard', 'route' => route('youth.home'), 'patterns' => ['youth.home']],
        ]],
        ['label' => 'Persiapan Akun', 'items' => [
            ['label' => 'Kesiapan Profil', 'icon' => 'badge-check', 'route' => route('youth.onboarding'), 'patterns' => ['youth.onboarding', 'youth.identity-verification*']],
            ['label' => 'Biodata', 'icon' => 'user', 'route' => route('youth.biodata.edit'), 'patterns' => ['youth.biodata.*']],
            ['label' => 'Profil', 'icon' => 'user', 'route' => route('youth.profile.show'), 'patterns' => ['youth.profile.show', 'youth.profile.edit'], 'requires' => 'profile'],
        ]],
        ['label' => 'Portfolio', 'items' => [
            ['label' => 'Portfolio Saya', 'icon' => 'file-text', 'route' => route('youth.portfolio.show'), 'patterns' => ['youth.portfolio.show'], 'requires' => 'portfolio'],
            ['label' => 'Keahlian', 'icon' => 'badge-check', 'route' => route('youth.skills.edit'), 'patterns' => ['youth.skills.*'], 'requires' => 'portfolio'],
            ['label' => 'Minat', 'icon' => 'sparkles', 'route' => route('youth.interests.edit'), 'patterns' => ['youth.interests.*'], 'requires' => 'portfolio'],
            ['label' => 'Pendidikan', 'icon' => 'file-text', 'route' => route('youth.portfolio.sections.show', 'education'), 'patterns' => ['youth.portfolio.sections.show'], 'section' => 'education', 'requires' => 'portfolio'],
            ['label' => 'Pengalaman', 'icon' => 'building', 'route' => route('youth.portfolio.sections.show', 'experience'), 'patterns' => ['youth.portfolio.sections.show'], 'section' => 'experience', 'requires' => 'portfolio'],
            ['label' => 'Prestasi', 'icon' => 'award', 'route' => route('youth.portfolio.sections.show', 'achievements'), 'patterns' => ['youth.portfolio.sections.show'], 'section' => 'achievements', 'requires' => 'portfolio'],
            ['label' => 'Pelatihan & Sertifikasi', 'icon' => 'graduation-cap', 'route' => route('youth.portfolio.sections.show', 'training'), 'patterns' => ['youth.portfolio.sections.show'], 'section' => 'training', 'requires' => 'portfolio'],
            ['label' => 'Privasi Portfolio', 'icon' => 'shield-check', 'route' => route('youth.profile.privacy'), 'patterns' => ['youth.profile.privacy'], 'requires' => 'portfolio'],
        ]],
        ['label' => 'Aktivitas Saya', 'items' => [
            ['label' => 'Forum Community', 'icon' => 'users', 'route' => route('forum.index'), 'patterns' => ['forum.*']],
            ['label' => 'Development Pathway', 'icon' => 'sparkles', 'route' => route('youth.development-pathway.index'), 'patterns' => ['youth.development-pathway.*'], 'requires' => 'portfolio'],
            ['label' => 'Activity Saya', 'icon' => 'clipboard-check', 'route' => route('youth.activities.index'), 'patterns' => ['youth.activities.*'], 'requires' => 'complete'],
            ['label' => 'Community Saya', 'icon' => 'building', 'route' => route('youth.communities.index'), 'patterns' => ['youth.communities.*'], 'requires' => 'complete'],
            ['label' => 'Opportunity Tersimpan', 'icon' => 'bookmark', 'route' => route('youth.opportunities.bookmarks'), 'patterns' => ['youth.opportunities.*'], 'requires' => 'complete'],
            ['label' => 'Passport', 'icon' => 'badge-check', 'route' => route('youth.passport.index'), 'patterns' => ['youth.passport.*'], 'requires' => 'complete'],
            ['label' => 'Sertifikat SIPORA', 'icon' => 'award', 'route' => route('youth.certificates.index'), 'patterns' => ['youth.certificates.*'], 'requires' => 'complete'],
        ]],
        ['label' => 'Akun', 'items' => [
            ['label' => 'Informasi Akun', 'icon' => 'user', 'route' => route('youth.account.show'), 'patterns' => ['youth.account.show']],
            ['label' => 'Keamanan', 'icon' => 'shield-check', 'route' => route('youth.account.security'), 'patterns' => ['youth.account.security*']],
            ['label' => 'Notifikasi', 'icon' => 'bell', 'route' => route('notifications.index'), 'patterns' => ['notifications.*']],
        ]],
    ] : ($isAdmin ? [
        ['label' => 'Overview', 'items' => [
            ['label' => 'Dashboard', 'icon' => 'layout-dashboard', 'route' => route('admin.dashboard'), 'patterns' => ['admin.dashboard']],
            ['label' => 'Peta Ekosistem', 'icon' => 'globe', 'route' => route('admin.ecosystem-map.index'), 'patterns' => ['admin.ecosystem-map.*']],
        ]],
        ['label' => 'Verifikasi', 'items' => [
            ['label' => 'Identitas Pemuda', 'icon' => 'shield-check', 'route' => route('admin.identity-verifications.index'), 'patterns' => ['admin.identity-verifications.*']],
        ]],
        ['label' => 'Konten', 'items' => [
            ['label' => 'Moderasi Forum', 'icon' => 'users', 'route' => route('admin.forum.index'), 'patterns' => ['admin.forum.*']],
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
    if ($isYouth) {
        $onboardingService = app(\App\Services\Youth\YouthOnboardingService::class);
        $youthOnboarding = $onboardingService->state(auth()->user());
        foreach ($groups as &$group) {
            foreach ($group['items'] as &$item) {
                if (isset($item['requires']) && ! $onboardingService->allows($youthOnboarding, $item['requires'])) {
                    $item['disabled'] = true;
                    $item['lockedReason'] = $onboardingService->lockedReason($youthOnboarding);
                }
            }
            unset($item);
        }
        unset($group);
    }
    $currentNavLabel = collect($groups)
        ->flatMap(fn (array $group) => $group['items'])
        ->first(fn (array $item) => isset($item['patterns']) && request()->routeIs(...$item['patterns']) && (!isset($item['section']) || request()->route('section') === $item['section']))['label'] ?? 'Dashboard';
    $currentGroupLabel = collect($groups)->first(fn (array $group) => collect($group['items'])->contains(fn (array $item) => isset($item['patterns']) && request()->routeIs(...$item['patterns']) && (!isset($item['section']) || request()->route('section') === $item['section'])))['label'] ?? 'Overview';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $workspaceShortLabel.' · SIPORA')</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <x-theme-init />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;600&family=Pixelify+Sans:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Press+Start+2P&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body x-data="workspaceShell" data-workspace="{{ $workspace }}" data-current-group="{{ $currentGroupLabel }}" @keydown.escape.window="closeMobile(true)" class="workspace-{{ $workspace }} min-h-screen bg-bg text-text-primary antialiased">
<a href="#main" class="sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:block focus:rounded-lg focus:bg-white focus:p-3">Lewati ke konten</a>
<div x-cloak x-show="mobileOpen" x-transition.opacity @click="closeMobile(true)" class="fixed inset-0 z-40 bg-primary-dark/45 backdrop-blur-sm lg:hidden" aria-hidden="true"></div>
<div class="flex min-h-screen">
    <aside x-ref="sidebar" @keydown.tab="trapMobileFocus($event)" id="workspace-sidebar" :class="[mobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0', collapsed ? 'lg:w-[76px]' : 'lg:w-[264px]']" class="fixed inset-y-0 left-0 z-50 flex w-[264px] shrink-0 flex-col border-r border-border bg-white shadow-lg transition-[width,transform] duration-200 lg:sticky lg:top-0 lg:h-screen lg:shadow-none" aria-label="Navigasi {{ $workspaceShortLabel }}">
        <div class="flex h-20 shrink-0 items-center gap-3 border-b border-border px-4" :class="collapsed ? 'lg:justify-center' : ''">
            <a href="{{ $homeRoute }}" class="flex min-w-0 items-center gap-3" aria-label="SIPORA {{ $workspaceShortLabel }}"><x-brand-logo /><span x-show="!collapsed" class="min-w-0"><strong class="block text-lg font-extrabold leading-none text-primary">SIPORA</strong><small class="mt-1 block truncate text-[10px] font-bold uppercase tracking-[0.12em] text-text-muted">{{ $workspaceDescription }}</small></span></a>
            <button type="button" @click="closeMobile(true)" class="ml-auto grid size-10 place-items-center rounded-lg text-text-secondary hover:bg-surface-soft lg:hidden" aria-label="Tutup navigasi"><x-ui.icon name="x" /></button>
        </div>
        @if($isManager)
            @php($actorRole = $organization->memberships()->where('user_id', auth()->id())->where('membership_status', \App\Models\OrganizationMembership::STATUS_ACTIVE)->value('access_role'))
            <div class="mx-3 mt-4 rounded-xl border border-border bg-primary-50 p-3" :class="collapsed ? 'lg:p-2' : ''">
                <div class="flex items-center gap-3"><span class="grid size-9 shrink-0 place-items-center overflow-hidden rounded-lg bg-primary text-sm font-bold text-white">@if($organization->logo_path)<img src="{{ route('communities.logo', $organization) }}" alt="" class="size-full object-cover">@else{{ mb_substr($organization->name, 0, 1) }}@endif</span><div x-show="!collapsed" class="min-w-0"><p class="truncate text-xs font-bold text-primary" title="{{ $organization->name }}">{{ $organization->name }}</p><p class="text-[11px] capitalize text-text-secondary">{{ $actorRole }} · peran Community</p></div></div>
                @if(($managedCommunities ?? collect())->count() > 1)<div x-show="!collapsed" class="mt-3"><label for="community-switcher" class="sr-only">Ganti Community</label><select id="community-switcher" class="field !min-h-10 w-full text-xs" @change="window.location.assign($event.target.value)">@foreach($managedCommunities as $managedCommunity)<option value="{{ route('manager.dashboard', $managedCommunity) }}" @selected($managedCommunity->is($organization))>{{ $managedCommunity->name }}</option>@endforeach</select></div>@endif
            </div>
        @else
            <div class="mx-3 mt-4 rounded-xl border border-border bg-primary-50 p-3" :class="collapsed ? 'lg:p-2' : ''"><div class="flex items-center gap-2"><span class="relative grid size-8 shrink-0 place-items-center rounded-lg bg-white text-primary"><x-ui.icon name="badge-check" class="size-4" /><span class="absolute -right-0.5 -top-0.5 size-2 rounded-full bg-emerald-500 ring-2 ring-white"></span></span><div x-show="!collapsed"><p class="text-[10px] font-extrabold uppercase tracking-wider text-primary">{{ $workspaceLabel }}</p><p class="mt-0.5 text-[10px] font-semibold text-emerald-700">Workspace aktif</p></div></div></div>
        @endif
        <nav class="flex-1 space-y-6 overflow-y-auto px-3 py-5" aria-label="Menu {{ $workspaceShortLabel }}">
            @foreach($groups as $group)<section>
                @if(($isYouth || $isManager) && $group['label'] !== 'Overview')
                    <button type="button" @click="toggleGroup(@js($group['label']))" :aria-expanded="openGroup === @js($group['label'])" aria-controls="workspace-nav-{{ $loop->index }}" class="workspace-group-toggle w-full" :class="collapsed ? 'lg:justify-center' : ''"><span x-show="!collapsed" class="truncate">{{ $group['label'] }}</span><x-ui.icon name="chevron-down" class="size-4 transition-transform" x-bind:class="openGroup === @js($group['label']) ? 'rotate-180' : ''" /></button>
                @else
                    <p x-show="!collapsed" class="mb-2 px-3 text-[10px] font-extrabold uppercase tracking-[0.16em] text-text-muted">{{ $group['label'] }}</p>
                @endif
                <div id="workspace-nav-{{ $loop->index }}" @if(($isYouth || $isManager) && $group['label'] !== 'Overview') x-show="openGroup === @js($group['label']) || collapsed" x-cloak @endif class="space-y-1">
                @foreach($group['items'] as $item) @php($active = isset($item['patterns']) && request()->routeIs(...$item['patterns']) && (!isset($item['section']) || request()->route('section') === $item['section']))
                    @if($item['disabled'] ?? false)<span class="workspace-nav-link workspace-nav-disabled" aria-disabled="true" title="{{ $item['label'] }} — {{ $item['lockedReason'] ?? 'Belum tersedia' }}"><x-ui.icon :name="$item['icon']" /><span x-show="!collapsed">{{ $item['label'] }}</span><span class="sr-only">{{ $item['lockedReason'] ?? 'Belum tersedia' }}</span></span>
                    @else<a href="{{ $item['route'] }}" @class(['workspace-nav-link', 'workspace-nav-active' => $active]) @if($active) aria-current="page" @endif title="{{ $item['label'] }}" @click="closeMobile"><x-ui.icon :name="$item['icon']" /><span x-show="!collapsed">{{ $item['label'] }}</span></a>@endif
                @endforeach
            </div></section>@endforeach
        </nav>
        <div class="hidden border-t border-border p-3 lg:block"><button type="button" @click="toggleCollapsed" class="workspace-nav-link w-full" :aria-label="collapsed ? 'Perluas sidebar' : 'Ciutkan sidebar'" :title="collapsed ? 'Perluas sidebar' : 'Ciutkan sidebar'"><x-ui.icon name="panel-left" /><span x-show="!collapsed">Ciutkan sidebar</span></button></div>
    </aside>
    <div class="min-w-0 flex-1" :inert="mobileOpen">
        <header class="sticky top-0 z-30 flex h-20 items-center justify-between border-b border-border bg-white/90 px-4 backdrop-blur-xl sm:px-6 lg:px-8"><div class="flex min-w-0 items-center gap-3"><button x-ref="mobileTrigger" type="button" class="grid size-11 shrink-0 place-items-center rounded-[10px] border border-border bg-white text-primary shadow-sm lg:hidden" @click="openMobile" aria-label="Buka navigasi" aria-controls="workspace-sidebar" :aria-expanded="mobileOpen"><x-ui.icon name="menu" /></button><div class="min-w-0"><p class="truncate text-xs font-extrabold uppercase tracking-[0.12em] text-text-muted">{{ $isYouth ? 'SIPORA Pemuda Pemalang' : ($isManager ? 'Workspace Community' : 'Dindikpora Kabupaten Pemalang') }}</p><p class="truncate text-sm font-bold text-text-primary sm:text-base">{{ $workspaceDescription }}</p></div></div><div class="flex items-center gap-2 sm:gap-3">@if($isYouth || $isManager)<a class="btn-ghost !px-3" href="{{ route('home') }}" aria-label="Jelajahi SIPORA"><x-ui.icon name="globe" /><span class="hidden md:inline">Jelajahi</span></a>@endif<x-theme-toggle /><x-notification-indicator :href="$isManager ? route('manager.notifications.index', $organization) : null"/><div class="hidden text-right text-sm md:block"><strong class="block max-w-48 truncate text-text-primary">{{ auth()->user()->name }}</strong><small class="text-text-muted">{{ $workspaceShortLabel }}</small></div><form method="post" action="{{ route('logout') }}">@csrf<button class="btn-ghost !px-3" type="submit"><x-ui.icon name="log-out" /><span class="hidden sm:inline">Keluar</span></button></form></div></header>
        <noscript><nav class="border-b border-amber-200 bg-amber-50 px-4 py-3 text-sm lg:hidden" aria-label="Navigasi tanpa JavaScript">JavaScript tidak aktif. <a class="font-bold text-primary underline" href="{{ $homeRoute }}">Buka dashboard</a>.</nav></noscript>
        <main id="main" tabindex="-1" class="mx-auto max-w-[1600px] p-3 sm:p-5 lg:p-7">
            <div class="workspace-stage">
                <nav aria-label="Breadcrumb" class="mb-5 flex min-w-0 items-center gap-2 text-xs font-semibold text-text-muted"><span class="shrink-0">{{ $workspaceShortLabel }}</span><x-ui.icon name="chevron-right" class="size-3.5 shrink-0 text-text-muted" /><span aria-current="page" class="truncate text-primary" title="{{ $currentNavLabel }}">{{ $currentNavLabel }}</span></nav>
                @if($errors->any())<div role="alert" aria-live="assertive" class="mb-6 rounded-xl border border-danger/25 bg-danger-soft p-4 text-sm text-danger"><p class="font-bold">Periksa kembali data berikut:</p><ul class="mt-2 list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
                <div class="relative z-10">@yield('workspace-content')</div>
            </div>
        </main>
    </div>
</div>
    <x-alerts />
</body>
</html>
