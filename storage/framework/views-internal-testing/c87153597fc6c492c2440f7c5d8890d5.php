<?php
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
            ['label' => 'Update Community', 'icon' => 'users', 'route' => route('manager.social.index', $organization), 'patterns' => ['manager.social.*']],
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
            ['label' => 'Social Feed', 'icon' => 'users', 'route' => route('social.index'), 'patterns' => ['social.*']],
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
        ['label' => 'Data Ekosistem', 'items' => [
            ['label' => 'Pemuda', 'icon' => 'users', 'route' => route('admin.directory.youth.index'), 'patterns' => ['admin.directory.youth.*']],
            ['label' => 'Community', 'icon' => 'building', 'route' => route('admin.directory.communities.index'), 'patterns' => ['admin.directory.communities.*']],
            ['label' => 'Activity', 'icon' => 'clipboard-check', 'route' => route('admin.directory.activities.index'), 'patterns' => ['admin.directory.activities.*']],
            ['label' => 'Program', 'icon' => 'file-text', 'route' => route('admin.directory.programs.index'), 'patterns' => ['admin.directory.programs.*']],
        ]],
        ['label' => 'Verifikasi', 'items' => [
            ['label' => 'Identitas Pemuda', 'icon' => 'shield-check', 'route' => route('admin.identity-verifications.index'), 'patterns' => ['admin.identity-verifications.*']],
        ]],
        ['label' => 'Konten', 'items' => [
            ['label' => 'Moderasi Feed', 'icon' => 'users', 'route' => route('admin.social.index'), 'patterns' => ['admin.social.*']],
            ['label' => 'Moderasi Forum', 'icon' => 'users', 'route' => route('admin.forum.index'), 'patterns' => ['admin.forum.*']],
            ['label' => 'Opportunity', 'icon' => 'sparkles', 'route' => route('admin.opportunities.index'), 'patterns' => ['admin.opportunities.*']],
        ]],
        ['label' => 'Sistem', 'items' => [
            ['label' => 'Pengguna Internal', 'icon' => 'users', 'route' => route('admin.internal-users.index'), 'patterns' => ['admin.internal-users.index', 'admin.internal-users.create', 'admin.internal-users.store', 'admin.internal-users.show', 'admin.internal-users.edit', 'admin.internal-users.update', 'admin.internal-users.active', 'admin.internal-users.temporary', 'admin.internal-users.reset-link', 'admin.internal-users.verification']],
            ['label' => 'Kelola Password', 'icon' => 'shield-check', 'route' => route('admin.account.password.edit'), 'patterns' => ['admin.account.password.*']],
            ['label' => 'Notifikasi', 'icon' => 'bell', 'route' => route('notifications.index'), 'patterns' => ['notifications.*']],
            ['label' => 'Log Aktivitas', 'icon' => 'notebook', 'route' => route('admin.internal-users.activity-log'), 'patterns' => ['admin.internal-users.activity-log']],
        ]],
    ] : [
        ['label' => 'Overview', 'items' => [
            ['label' => 'Dashboard', 'icon' => 'layout-dashboard', 'route' => route('verifier.dashboard'), 'patterns' => ['verifier.dashboard']],
        ]],
        ['label' => 'Data Ekosistem', 'items' => [
            ['label' => 'Pemuda Terdaftar', 'icon' => 'users', 'route' => route('verifier.directory.youth.index'), 'patterns' => ['verifier.directory.youth.*']],
            ['label' => 'Community Terdaftar', 'icon' => 'building', 'route' => route('verifier.directory.communities.index'), 'patterns' => ['verifier.directory.communities.*']],
            ['label' => 'Activity Terdaftar', 'icon' => 'clipboard-check', 'route' => route('verifier.directory.activities.index'), 'patterns' => ['verifier.directory.activities.*']],
            ['label' => 'Program Terdaftar', 'icon' => 'file-text', 'route' => route('verifier.directory.programs.index'), 'patterns' => ['verifier.directory.programs.*']],
        ]],
        ['label' => 'Verifikasi Ekosistem', 'items' => [
            ['label' => 'Verifikasi Community', 'icon' => 'building', 'route' => route('verifier.community-verifications.index'), 'patterns' => ['verifier.community-verifications.*']],
            ['label' => 'Verifikasi Activity', 'icon' => 'clipboard-check', 'route' => route('verifier.activity-verifications.index'), 'patterns' => ['verifier.activity-verifications.*']],
        ]],
        ['label' => 'Program', 'items' => [
            ['label' => 'Proposal', 'icon' => 'file-text', 'route' => route('verifier.program-proposals.index'), 'patterns' => ['verifier.program-proposals.*']],
            ['label' => 'Pendaftaran Program', 'icon' => 'users', 'route' => route('verifier.programs.intake.index'), 'patterns' => ['verifier.programs.*']],
            ['label' => 'Monitoring & Logbook', 'icon' => 'notebook', 'route' => route('verifier.program-monitoring.index'), 'patterns' => ['verifier.program-monitoring.*']],
            ['label' => 'E-LPJ Keuangan', 'icon' => 'wallet', 'route' => route('verifier.financial-reports.index'), 'patterns' => ['verifier.financial-reports.*']],
            ['label' => 'Evaluasi Akhir', 'icon' => 'badge-check', 'route' => route('verifier.program-evaluations.index'), 'patterns' => ['verifier.program-evaluations.*']],
        ]],
        ['label' => 'Akun', 'items' => [
            ['label' => 'Kelola Password', 'icon' => 'shield-check', 'route' => route('verifier.account.password.edit'), 'patterns' => ['verifier.account.password.*']],
            ['label' => 'Notifikasi', 'icon' => 'bell', 'route' => route('notifications.index'), 'patterns' => ['notifications.*']],
        ]],
    ]));
    $verifierMenuCounts = $workspace === 'verifier'
        ? app(\App\Services\VerifierQueueSummaryService::class)->navigation(auth()->user(), $queues ?? null)
        : [];
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
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', $workspaceShortLabel.' · SIPORA'); ?></title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('logo.png')); ?>">
    <?php if (isset($component)) { $__componentOriginalff3018920f514a2a3356c625e214876e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalff3018920f514a2a3356c625e214876e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.theme-init','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('theme-init'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalff3018920f514a2a3356c625e214876e)): ?>
<?php $attributes = $__attributesOriginalff3018920f514a2a3356c625e214876e; ?>
<?php unset($__attributesOriginalff3018920f514a2a3356c625e214876e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalff3018920f514a2a3356c625e214876e)): ?>
<?php $component = $__componentOriginalff3018920f514a2a3356c625e214876e; ?>
<?php unset($__componentOriginalff3018920f514a2a3356c625e214876e); ?>
<?php endif; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('head'); ?>
</head>
<body x-data="workspaceShell" data-workspace="<?php echo e($workspace); ?>" data-current-group="<?php echo e($currentGroupLabel); ?>" @keydown.escape.window="closeMobile(true)" class="workspace-<?php echo e($workspace); ?> min-h-screen bg-bg text-text-primary antialiased">
<a href="#main" class="sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:block focus:rounded-lg focus:bg-white focus:p-3">Lewati ke konten</a>
<div x-cloak x-show="mobileOpen" x-transition.opacity @click="closeMobile(true)" class="fixed inset-0 z-40 bg-primary-dark/45 backdrop-blur-sm lg:hidden" aria-hidden="true"></div>
<div class="flex min-h-screen">
    <aside x-ref="sidebar" @keydown.tab="trapMobileFocus($event)" id="workspace-sidebar" :class="[mobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0', collapsed ? 'lg:w-[76px]' : 'lg:w-[264px]']" class="fixed inset-y-0 left-0 z-50 flex w-[264px] shrink-0 flex-col border-r border-border bg-white shadow-lg transition-[width,transform] duration-200 lg:sticky lg:top-0 lg:h-screen lg:shadow-none" aria-label="Navigasi <?php echo e($workspaceShortLabel); ?>">
        <div class="flex h-20 shrink-0 items-center gap-3 border-b border-border px-4" :class="collapsed ? 'lg:justify-center' : ''">
            <a href="<?php echo e($homeRoute); ?>" class="flex min-w-0 items-center gap-3" aria-label="SIPORA <?php echo e($workspaceShortLabel); ?>"><?php if (isset($component)) { $__componentOriginal8741a05e11b0c77d19ec61b6b35b26b3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8741a05e11b0c77d19ec61b6b35b26b3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.brand-logo','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('brand-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8741a05e11b0c77d19ec61b6b35b26b3)): ?>
<?php $attributes = $__attributesOriginal8741a05e11b0c77d19ec61b6b35b26b3; ?>
<?php unset($__attributesOriginal8741a05e11b0c77d19ec61b6b35b26b3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8741a05e11b0c77d19ec61b6b35b26b3)): ?>
<?php $component = $__componentOriginal8741a05e11b0c77d19ec61b6b35b26b3; ?>
<?php unset($__componentOriginal8741a05e11b0c77d19ec61b6b35b26b3); ?>
<?php endif; ?><span x-show="!collapsed" class="min-w-0"><strong class="block text-lg font-extrabold leading-none text-primary">SIPORA</strong><small class="mt-1 block truncate text-[10px] font-bold uppercase tracking-[0.12em] text-text-muted"><?php echo e($workspaceDescription); ?></small></span></a>
            <button type="button" @click="closeMobile(true)" class="ml-auto grid size-10 place-items-center rounded-lg text-text-secondary hover:bg-surface-soft lg:hidden" aria-label="Tutup navigasi"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'x']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'x']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></button>
        </div>
        <?php if($isManager): ?>
            <?php ($actorRole = $organization->memberships()->where('user_id', auth()->id())->where('membership_status', \App\Models\OrganizationMembership::STATUS_ACTIVE)->value('access_role')); ?>
            <div class="mx-3 mt-4 rounded-xl border border-border bg-primary-50 p-3" :class="collapsed ? 'lg:p-2' : ''">
                <div class="flex items-center gap-3"><span class="grid size-9 shrink-0 place-items-center overflow-hidden rounded-lg bg-primary text-sm font-bold text-white"><?php if($organization->logo_path): ?><img src="<?php echo e(route('communities.logo', $organization)); ?>" alt="" class="size-full object-cover"><?php else: ?><?php echo e(mb_substr($organization->name, 0, 1)); ?><?php endif; ?></span><div x-show="!collapsed" class="min-w-0"><p class="truncate text-xs font-bold text-primary" title="<?php echo e($organization->name); ?>"><?php echo e($organization->name); ?></p><p class="text-[11px] capitalize text-text-secondary"><?php echo e($actorRole); ?> · peran Community</p></div></div>
                <?php if(($managedCommunities ?? collect())->count() > 1): ?><div x-show="!collapsed" class="mt-3"><label for="community-switcher" class="sr-only">Ganti Community</label><select id="community-switcher" class="field !min-h-10 w-full text-xs" @change="window.location.assign($event.target.value)"><?php $__currentLoopData = $managedCommunities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $managedCommunity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e(route('manager.dashboard', $managedCommunity)); ?>" <?php if($managedCommunity->is($organization)): echo 'selected'; endif; ?>><?php echo e($managedCommunity->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div><?php endif; ?>
            </div>
        <?php else: ?>
            <div class="mx-3 mt-4 rounded-xl border border-border bg-primary-50 p-3" :class="collapsed ? 'lg:p-2' : ''"><div class="flex items-center gap-2"><span class="relative grid size-8 shrink-0 place-items-center rounded-lg bg-white text-primary"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'badge-check','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'badge-check','class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?><span class="absolute -right-0.5 -top-0.5 size-2 rounded-full bg-emerald-500 ring-2 ring-white"></span></span><div x-show="!collapsed"><p class="text-[10px] font-extrabold uppercase tracking-wider text-primary"><?php echo e($workspaceLabel); ?></p><p class="mt-0.5 text-[10px] font-semibold text-emerald-700">Workspace aktif</p></div></div></div>
        <?php endif; ?>
        <nav class="flex-1 space-y-6 overflow-y-auto px-3 py-5" aria-label="Menu <?php echo e($workspaceShortLabel); ?>">
            <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><section>
                <?php if(($isYouth || $isManager) && $group['label'] !== 'Overview'): ?>
                    <button type="button" @click="toggleGroup(<?php echo \Illuminate\Support\Js::from($group['label'])->toHtml() ?>)" :aria-expanded="openGroup === <?php echo \Illuminate\Support\Js::from($group['label'])->toHtml() ?>" aria-controls="workspace-nav-<?php echo e($loop->index); ?>" class="workspace-group-toggle w-full" :class="collapsed ? 'lg:justify-center' : ''"><span x-show="!collapsed" class="truncate"><?php echo e($group['label']); ?></span><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'chevron-down','class' => 'size-4 transition-transform','xBind:class' => 'openGroup === @js($group[\'label\']) ? \'rotate-180\' : \'\'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-down','class' => 'size-4 transition-transform','x-bind:class' => 'openGroup === @js($group[\'label\']) ? \'rotate-180\' : \'\'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></button>
                <?php else: ?>
                    <p x-show="!collapsed" class="mb-2 px-3 text-[10px] font-extrabold uppercase tracking-[0.16em] text-text-muted"><?php echo e($group['label']); ?></p>
                <?php endif; ?>
                <div id="workspace-nav-<?php echo e($loop->index); ?>" <?php if(($isYouth || $isManager) && $group['label'] !== 'Overview'): ?> x-show="openGroup === <?php echo \Illuminate\Support\Js::from($group['label'])->toHtml() ?> || collapsed" x-cloak <?php endif; ?> class="space-y-1">
                <?php $__currentLoopData = $group['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php ($active = isset($item['patterns']) && request()->routeIs(...$item['patterns']) && (!isset($item['section']) || request()->route('section') === $item['section'])); ?>
                    <?php if($item['disabled'] ?? false): ?><span class="workspace-nav-link workspace-nav-disabled" aria-disabled="true" title="<?php echo e($item['label']); ?> — <?php echo e($item['lockedReason'] ?? 'Belum tersedia'); ?>"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => $item['icon']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['icon'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?><span x-show="!collapsed"><?php echo e($item['label']); ?></span><span class="sr-only"><?php echo e($item['lockedReason'] ?? 'Belum tersedia'); ?></span></span>
                    <?php else: ?><a href="<?php echo e($item['route']); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['workspace-nav-link relative', 'workspace-nav-active' => $active]); ?>" <?php if($active): ?> aria-current="page" <?php endif; ?> title="<?php echo e($item['label']); ?><?php echo e(($verifierMenuCounts[$item['route']] ?? 0) > 0 ? ' — '.($verifierMenuCounts[$item['route']]).' perlu ditindaklanjuti' : ''); ?>" @click="closeMobile"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => $item['icon']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['icon'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?><span x-show="!collapsed"><?php echo e($item['label']); ?></span><?php if(($verifierMenuCounts[$item['route']] ?? 0) > 0): ?><?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['tone' => 'warning','xShow' => '!collapsed','class' => 'ml-auto shrink-0 tabular-nums','dataQueueIndicator' => ''.e($item['route']).'','title' => ''.e($verifierMenuCounts[$item['route']]).' perlu ditindaklanjuti']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => 'warning','x-show' => '!collapsed','class' => 'ml-auto shrink-0 tabular-nums','data-queue-indicator' => ''.e($item['route']).'','title' => ''.e($verifierMenuCounts[$item['route']]).' perlu ditindaklanjuti']); ?><span aria-hidden="true"><?php echo e($verifierMenuCounts[$item['route']] > 99 ? '99+' : $verifierMenuCounts[$item['route']]); ?></span><span class="sr-only"><?php echo e($verifierMenuCounts[$item['route']]); ?> perlu ditindaklanjuti</span> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?><span x-cloak x-show="collapsed" class="absolute right-1 top-1 size-2 rounded-full bg-warning" aria-hidden="true"></span><?php endif; ?></a><?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div></section><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </nav>
        <div class="hidden border-t border-border p-3 lg:block"><button type="button" @click="toggleCollapsed" class="workspace-nav-link w-full" :aria-label="collapsed ? 'Perluas sidebar' : 'Ciutkan sidebar'" :title="collapsed ? 'Perluas sidebar' : 'Ciutkan sidebar'"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'panel-left']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'panel-left']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?><span x-show="!collapsed">Ciutkan sidebar</span></button></div>
    </aside>
    <div class="min-w-0 flex-1" :inert="mobileOpen">
        <header class="sticky top-0 z-30 flex h-20 items-center justify-between border-b border-border bg-white/90 px-4 backdrop-blur-xl sm:px-6 lg:px-8"><div class="flex min-w-0 items-center gap-3"><button x-ref="mobileTrigger" type="button" class="grid size-11 shrink-0 place-items-center rounded-[10px] border border-border bg-white text-primary shadow-sm lg:hidden" @click="openMobile" aria-label="Buka navigasi" aria-controls="workspace-sidebar" :aria-expanded="mobileOpen"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'menu']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'menu']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></button><div class="min-w-0"><p class="truncate text-xs font-extrabold uppercase tracking-[0.12em] text-text-muted"><?php echo e($isYouth ? 'SIPORA Pemuda Pemalang' : ($isManager ? 'Workspace Community' : 'Dindikpora Kabupaten Pemalang')); ?></p><p class="truncate text-sm font-bold text-text-primary sm:text-base"><?php echo e($workspaceDescription); ?></p></div></div><div class="flex items-center gap-2 sm:gap-3"><?php if($isYouth || $isManager): ?><a class="btn-ghost !px-3" href="<?php echo e(route('home')); ?>" aria-label="Jelajahi SIPORA"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'globe']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'globe']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?><span class="hidden md:inline">Jelajahi</span></a><?php endif; ?><?php if (isset($component)) { $__componentOriginal2090438866f3dcdb76cd8b070bcc302d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2090438866f3dcdb76cd8b070bcc302d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.theme-toggle','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('theme-toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2090438866f3dcdb76cd8b070bcc302d)): ?>
<?php $attributes = $__attributesOriginal2090438866f3dcdb76cd8b070bcc302d; ?>
<?php unset($__attributesOriginal2090438866f3dcdb76cd8b070bcc302d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2090438866f3dcdb76cd8b070bcc302d)): ?>
<?php $component = $__componentOriginal2090438866f3dcdb76cd8b070bcc302d; ?>
<?php unset($__componentOriginal2090438866f3dcdb76cd8b070bcc302d); ?>
<?php endif; ?><?php if (isset($component)) { $__componentOriginale5ab1a1e4d63e1839cb092c6e5714a22 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale5ab1a1e4d63e1839cb092c6e5714a22 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.notification-indicator','data' => ['href' => $isManager ? route('manager.notifications.index', $organization) : null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('notification-indicator'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($isManager ? route('manager.notifications.index', $organization) : null)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale5ab1a1e4d63e1839cb092c6e5714a22)): ?>
<?php $attributes = $__attributesOriginale5ab1a1e4d63e1839cb092c6e5714a22; ?>
<?php unset($__attributesOriginale5ab1a1e4d63e1839cb092c6e5714a22); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale5ab1a1e4d63e1839cb092c6e5714a22)): ?>
<?php $component = $__componentOriginale5ab1a1e4d63e1839cb092c6e5714a22; ?>
<?php unset($__componentOriginale5ab1a1e4d63e1839cb092c6e5714a22); ?>
<?php endif; ?><div class="hidden text-right text-sm md:block"><strong class="block max-w-48 truncate text-text-primary"><?php echo e(auth()->user()->name); ?></strong><small class="text-text-muted"><?php echo e($workspaceShortLabel); ?></small></div><form method="post" action="<?php echo e(route('logout')); ?>"><?php echo csrf_field(); ?><button class="btn-ghost !px-3" type="submit"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'log-out']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'log-out']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?><span class="hidden sm:inline">Keluar</span></button></form></div></header>
        <noscript><nav class="border-b border-amber-200 bg-amber-50 px-4 py-3 text-sm lg:hidden" aria-label="Navigasi tanpa JavaScript">JavaScript tidak aktif. <a class="font-bold text-primary underline" href="<?php echo e($homeRoute); ?>">Buka dashboard</a>.</nav></noscript>
        <main id="main" tabindex="-1" class="mx-auto max-w-[1600px] p-3 sm:p-5 lg:p-7">
            <div class="workspace-stage">
                <nav aria-label="Breadcrumb" class="mb-5 flex min-w-0 items-center gap-2 text-xs font-semibold text-text-muted"><span class="shrink-0"><?php echo e($workspaceShortLabel); ?></span><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'chevron-right','class' => 'size-3.5 shrink-0 text-text-muted']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-right','class' => 'size-3.5 shrink-0 text-text-muted']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?><span aria-current="page" class="truncate text-primary" title="<?php echo e($currentNavLabel); ?>"><?php echo e($currentNavLabel); ?></span></nav>
                <?php if($errors->any()): ?><div role="alert" aria-live="assertive" class="mb-6 rounded-xl border border-danger/25 bg-danger-soft p-4 text-sm text-danger"><p class="font-bold">Periksa kembali data berikut:</p><ul class="mt-2 list-inside list-disc"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div><?php endif; ?>
                <div class="relative z-10"><?php echo $__env->yieldContent('workspace-content'); ?></div>
            </div>
        </main>
    </div>
</div>
    <?php if (isset($component)) { $__componentOriginalc5711d836f933e61eafca8928e9a27a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc5711d836f933e61eafca8928e9a27a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.alerts','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('alerts'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc5711d836f933e61eafca8928e9a27a5)): ?>
<?php $attributes = $__attributesOriginalc5711d836f933e61eafca8928e9a27a5; ?>
<?php unset($__attributesOriginalc5711d836f933e61eafca8928e9a27a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc5711d836f933e61eafca8928e9a27a5)): ?>
<?php $component = $__componentOriginalc5711d836f933e61eafca8928e9a27a5; ?>
<?php unset($__componentOriginalc5711d836f933e61eafca8928e9a27a5); ?>
<?php endif; ?>
</body>
</html>
<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/layouts/workspace.blade.php ENDPATH**/ ?>