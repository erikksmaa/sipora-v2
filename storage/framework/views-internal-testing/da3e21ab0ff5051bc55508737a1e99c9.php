
<?php $__env->startSection('title', 'Ruang Saya · SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-7">
    <?php if (isset($component)) { $__componentOriginal8e0a96c106aaf9b655be7405167c4255 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8e0a96c106aaf9b655be7405167c4255 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.page-header','data' => ['eyebrow' => 'Overview','title' => 'Selamat datang, '.($user->profile?->full_name ?? $user->name),'description' => 'Kelola identitas, partisipasi, dan rekam pengalaman SIPORA dari satu ruang.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Overview','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Selamat datang, '.($user->profile?->full_name ?? $user->name)),'description' => 'Kelola identitas, partisipasi, dan rekam pengalaman SIPORA dari satu ruang.']); ?><a class="btn-secondary" href="<?php echo e(route('home')); ?>"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
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
<?php endif; ?>Jelajahi SIPORA</a> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8e0a96c106aaf9b655be7405167c4255)): ?>
<?php $attributes = $__attributesOriginal8e0a96c106aaf9b655be7405167c4255; ?>
<?php unset($__attributesOriginal8e0a96c106aaf9b655be7405167c4255); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e0a96c106aaf9b655be7405167c4255)): ?>
<?php $component = $__componentOriginal8e0a96c106aaf9b655be7405167c4255; ?>
<?php unset($__componentOriginal8e0a96c106aaf9b655be7405167c4255); ?>
<?php endif; ?>

    <?php if(! $onboarding['onboardingComplete']): ?>
        <?php ($finished = collect([$onboarding['emailVerified'], $onboarding['biodataComplete'], $onboarding['identityVerified'], $onboarding['profileComplete'], $onboarding['portfolioBaselineComplete']])->filter()->count()); ?>
        <section class="sipora-card">
            <p class="text-xs font-extrabold uppercase tracking-widest text-interactive">Perjalanan Youth</p>
            <h2 class="mt-2 text-2xl font-extrabold text-text-primary"><?php echo e($finished); ?> dari 5 tahap selesai</h2>
            <p class="mt-2 text-text-secondary">Selesaikan pengaturan akun untuk membuka pendaftaran Activity dan bergabung Community.</p>
            <div class="mt-5 h-3 overflow-hidden rounded-full bg-surface-soft" role="progressbar" aria-valuenow="<?php echo e($finished); ?>" aria-valuemin="0" aria-valuemax="5" aria-label="Tahap onboarding selesai"><div class="h-full bg-interactive" style="width: <?php echo e($finished * 20); ?>%"></div></div>
            <div class="mt-6 flex flex-wrap gap-3"><a class="btn" href="<?php echo e(route($onboarding['nextAction']['route'])); ?>"><?php echo e($onboarding['nextAction']['label']); ?></a><a class="btn-secondary" href="<?php echo e(route('youth.onboarding')); ?>">Lihat semua tahap</a><a class="btn-secondary" href="<?php echo e(route('home')); ?>">Jelajahi publik</a></div>
        </section>
        <?php if($onboarding['identityStatus'] === 'pending'): ?><p class="rounded-xl border border-warning/30 bg-warning-soft p-4 text-sm text-text-primary">Pengajuan identitas sedang ditinjau Admin. Profil dan Portfolio terbuka setelah disetujui.</p><?php endif; ?>
    <?php else: ?>

    <div class="media-profile-summary"><?php if($user->profile?->profile_photo_path): ?><img data-media-fallback src="<?php echo e(route('youth.profile.photo')); ?>" alt="Foto profil Anda"><?php endif; ?><div><strong><?php echo e($user->profile?->full_name ?? $user->name); ?></strong><p class="text-sm text-text-muted">Profil pribadi · kelola visibilitas melalui pengaturan privasi</p></div></div><section class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3" aria-label="Ringkasan Ruang Saya">
        <?php $__currentLoopData = [['Profil lengkap',$completion['percentage'].'%','user','bg-indigo-50 text-primary',route('youth.profile.show')],['Pendaftaran aktif',$activeRegistrationCount,'activity','bg-blue-50 text-blue-700',route('youth.activities.index')],['Activity selesai',$passportCount,'badge-check','bg-emerald-50 text-emerald-700',route('youth.passport.index')],['Sertifikat',$certificateCount,'award','bg-orange-50 text-orange-700',route('youth.certificates.index')],['Community aktif',$communityCount,'building','bg-violet-50 text-violet-700',route('youth.communities.index')],['Opportunity tersimpan',$bookmarkCount,'bookmark','bg-amber-50 text-amber-700',route('youth.opportunities.bookmarks')]]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label,$value,$icon,$tone,$href]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a class="group rounded-2xl border border-indigo-100 bg-white p-4 shadow-card transition hover:-translate-y-0.5 hover:border-indigo-200" href="<?php echo e($href); ?>"><div class="flex items-center gap-4"><span class="grid size-11 shrink-0 place-items-center rounded-xl <?php echo e($tone); ?>"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => $icon]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($icon)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></span><div><p class="font-heading text-2xl font-bold leading-none text-text-primary"><?php echo e($value); ?></p><p class="mt-1 text-sm font-semibold text-text-secondary group-hover:text-primary"><?php echo e($label); ?></p></div></div></a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </section>

    <div class="grid gap-7 xl:grid-cols-[minmax(0,1.35fr)_minmax(300px,.65fr)]">
        <div class="min-w-0 space-y-7">
            <section><div class="flex flex-wrap items-end justify-between gap-4"><div><p class="text-xs font-extrabold uppercase tracking-wider text-interactive">Activity</p><h2 class="mt-1 text-xl font-extrabold text-text-primary">Pendaftaran terbaru</h2></div><a class="public-section-link" href="<?php echo e(route('youth.activities.index')); ?>">Lihat semua <?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'chevron-right','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-right','class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></a></div><div class="mt-4 divide-y divide-slate-100 rounded-2xl border border-indigo-100 bg-white px-5 shadow-card"><?php $__empty_1 = true; $__currentLoopData = $recentParticipations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $participation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><a class="flex min-h-20 items-center justify-between gap-4 py-4" href="<?php echo e(route('activities.show', $participation->activity)); ?>"><div class="min-w-0"><p class="truncate font-bold text-text-primary"><?php echo e($participation->activity->title); ?></p><p class="mt-1 truncate text-xs text-text-secondary"><?php echo e($participation->activity->organization->name); ?> · <?php echo e($participation->requested_at?->diffForHumans()); ?></p></div><?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['tone' => $participation->registration_status === 'accepted' ? 'success' : ($participation->registration_status === 'pending' ? 'warning' : 'neutral')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($participation->registration_status === 'accepted' ? 'success' : ($participation->registration_status === 'pending' ? 'warning' : 'neutral'))]); ?><?php echo e(ucfirst($participation->registration_status)); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><div class="py-9 text-center text-sm text-slate-500">Belum ada pendaftaran Activity.</div><?php endif; ?></div></section>
            <section><div class="flex flex-wrap items-end justify-between gap-4"><div><p class="text-xs font-extrabold uppercase tracking-wider text-interactive">Verified experience</p><h2 class="mt-1 text-xl font-extrabold text-text-primary">Activity Passport terbaru</h2></div><a class="public-section-link" href="<?php echo e(route('youth.passport.index')); ?>">Buka Passport <?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'chevron-right','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-right','class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></a></div><div class="mt-4 grid gap-3 sm:grid-cols-3"><?php $__empty_1 = true; $__currentLoopData = $recentPassportEntries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><a class="rounded-2xl border border-border bg-surface p-4 transition hover:border-border-strong" href="<?php echo e(route('youth.passport.show', $entry)); ?>"><?php if (isset($component)) { $__componentOriginalc2a653d0f44ebc115de2e0e2b2511155 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2a653d0f44ebc115de2e0e2b2511155 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.media.activity','data' => ['activity' => $entry->activity,'class' => 'mb-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('media.activity'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['activity' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($entry->activity),'class' => 'mb-3']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2a653d0f44ebc115de2e0e2b2511155)): ?>
<?php $attributes = $__attributesOriginalc2a653d0f44ebc115de2e0e2b2511155; ?>
<?php unset($__attributesOriginalc2a653d0f44ebc115de2e0e2b2511155); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2a653d0f44ebc115de2e0e2b2511155)): ?>
<?php $component = $__componentOriginalc2a653d0f44ebc115de2e0e2b2511155; ?>
<?php unset($__componentOriginalc2a653d0f44ebc115de2e0e2b2511155); ?>
<?php endif; ?><?php if (isset($component)) { $__componentOriginal77427818d7088fcafb4732ee4ea7535a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal77427818d7088fcafb4732ee4ea7535a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portfolio.provenance-badge','data' => ['verified' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portfolio.provenance-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['verified' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal77427818d7088fcafb4732ee4ea7535a)): ?>
<?php $attributes = $__attributesOriginal77427818d7088fcafb4732ee4ea7535a; ?>
<?php unset($__attributesOriginal77427818d7088fcafb4732ee4ea7535a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal77427818d7088fcafb4732ee4ea7535a)): ?>
<?php $component = $__componentOriginal77427818d7088fcafb4732ee4ea7535a; ?>
<?php unset($__componentOriginal77427818d7088fcafb4732ee4ea7535a); ?>
<?php endif; ?><p class="mt-3 font-bold text-text-primary"><?php echo e($entry->activity->title); ?></p><p class="mt-1 text-xs text-slate-600"><?php echo e($entry->activity->organization->name); ?></p></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><div class="sm:col-span-3"><?php if (isset($component)) { $__componentOriginal3607a477fdef7402bc742abad5df9c51 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3607a477fdef7402bc742abad5df9c51 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.empty-state','data' => ['title' => 'Passport masih kosong','description' => 'Activity selesai yang telah disahkan akan muncul di sini.','icon' => 'badge-check']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Passport masih kosong','description' => 'Activity selesai yang telah disahkan akan muncul di sini.','icon' => 'badge-check']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $attributes = $__attributesOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__attributesOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $component = $__componentOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__componentOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?></div><?php endif; ?></div></section>
        </div>
        <aside class="min-w-0 space-y-6">
            <section class="rounded-2xl border border-indigo-100 bg-white p-5 shadow-card"><div class="flex items-center justify-between gap-3"><h2 class="font-extrabold text-text-primary">Kelengkapan profil</h2><span class="font-heading text-2xl font-bold text-primary"><?php echo e($completion['percentage']); ?>%</span></div><div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full bg-gradient-to-r from-primary to-interactive" style="width: <?php echo e($completion['percentage']); ?>%"></div></div><?php if($completion['missing']): ?><p class="mt-4 text-xs leading-5 text-slate-500">Masih perlu: <?php echo e(collect($completion['missing'])->take(3)->map(fn($item) => str_replace('_', ' ', $item))->implode(', ')); ?>.</p><?php endif; ?><div class="mt-4 flex gap-2"><a class="btn-secondary !min-h-9 !px-3 text-sm" href="<?php echo e(route('youth.profile.show')); ?>">Lihat profil</a><a class="btn-secondary !min-h-9 !px-3 text-sm" href="<?php echo e(route('youth.profile.privacy')); ?>">Privasi</a></div></section>
            <section><div class="flex items-center justify-between"><h2 class="font-extrabold text-text-primary">Terbaru</h2><a class="text-xs font-bold text-primary" href="<?php echo e(route('notifications.index')); ?>">Semua notifikasi</a></div><div class="mt-3 space-y-2"><?php $__empty_1 = true; $__currentLoopData = $recentNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><article class="rounded-xl border p-3 <?php echo e($notification->read_at ? 'border-slate-200 bg-white' : 'border-indigo-200 bg-indigo-50'); ?>"><div class="flex gap-3"><span class="mt-1 size-2 shrink-0 rounded-full <?php echo e($notification->read_at ? 'bg-slate-300' : 'bg-orange-500'); ?>"></span><div><p class="text-sm font-bold text-text-primary"><?php echo e($notification->title); ?></p><p class="mt-1 line-clamp-2 text-xs leading-5 text-slate-500"><?php echo e($notification->body); ?></p></div></div></article><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p class="rounded-xl border border-dashed border-slate-200 p-5 text-center text-sm text-slate-500">Belum ada notifikasi.</p><?php endif; ?></div></section>
        </aside>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.youth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/youth/home.blade.php ENDPATH**/ ?>