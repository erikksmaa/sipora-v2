<?php $__env->startSection('title', 'Dashboard '.$organization->name.' · SIPORA'); ?>
<?php $__env->startSection('manager-content'); ?>
<div class="space-y-6">
    <?php if (isset($component)) { $__componentOriginal8e0a96c106aaf9b655be7405167c4255 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8e0a96c106aaf9b655be7405167c4255 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.page-header','data' => ['eyebrow' => 'Operasional Community','title' => 'Dashboard '.e($organization->name).'','description' => 'Pantau anggota, Activity, Program, dan pekerjaan yang perlu ditindaklanjuti.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Operasional Community','title' => 'Dashboard '.e($organization->name).'','description' => 'Pantau anggota, Activity, Program, dan pekerjaan yang perlu ditindaklanjuti.']); ?>
        <a class="btn-secondary" href="<?php echo e(route('manager.programs.create', $organization)); ?>">Buat Program</a>
        <a class="btn-primary" href="<?php echo e(route('manager.activities.create', $organization)); ?>">Buat Activity</a>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8e0a96c106aaf9b655be7405167c4255)): ?>
<?php $attributes = $__attributesOriginal8e0a96c106aaf9b655be7405167c4255; ?>
<?php unset($__attributesOriginal8e0a96c106aaf9b655be7405167c4255); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e0a96c106aaf9b655be7405167c4255)): ?>
<?php $component = $__componentOriginal8e0a96c106aaf9b655be7405167c4255; ?>
<?php unset($__componentOriginal8e0a96c106aaf9b655be7405167c4255); ?>
<?php endif; ?><div class="media-profile-summary"><?php if($organization->logo_path): ?><img data-media-fallback src="<?php echo e(route('youth.communities.logo', $organization)); ?>" alt="Logo <?php echo e($organization->name); ?>"><?php endif; ?><strong><?php echo e($organization->name); ?></strong></div>

    <section class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4" aria-label="Ringkasan Community">
        <?php $__currentLoopData = [
            ['label' => 'Anggota aktif', 'value' => $counts['members'], 'icon' => 'users', 'tone' => 'bg-indigo-50 text-primary', 'url' => route('manager.members.index', $organization)],
            ['label' => 'Permintaan bergabung', 'value' => $counts['pending'], 'icon' => 'user', 'tone' => 'bg-orange-50 text-orange-700', 'url' => route('manager.join-requests.index', $organization)],
            ['label' => 'Activity terbit', 'value' => $counts['published_activities'], 'icon' => 'calendar', 'tone' => 'bg-emerald-50 text-emerald-700', 'url' => route('manager.activities.index', $organization)],
            ['label' => 'Peserta menunggu', 'value' => $counts['pending_participants'], 'icon' => 'clipboard-check', 'tone' => 'bg-amber-50 text-amber-700', 'url' => route('manager.operations.participants', $organization)],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metric): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a class="sipora-card group !p-4 transition hover:border-indigo-200 hover:shadow-md" href="<?php echo e($metric['url']); ?>">
                <span class="grid size-10 place-items-center rounded-xl <?php echo e($metric['tone']); ?>"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => $metric['icon']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($metric['icon'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></span>
                <strong class="mt-3 block text-3xl font-black text-primary-dark"><?php echo e($metric['value']); ?></strong>
                <span class="mt-1 block text-sm font-semibold text-slate-600 group-hover:text-primary"><?php echo e($metric['label']); ?></span>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </section>

    <section class="grid gap-6 xl:grid-cols-[1.4fr_1fr]" aria-labelledby="manager-actions-heading">
        <div class="sipora-card">
            <div class="flex flex-wrap items-center justify-between gap-3"><div><p class="text-xs font-extrabold uppercase tracking-wider text-orange-700">Perlu tindakan</p><h2 id="manager-actions-heading" class="mt-1 text-xl font-bold text-primary-dark">Antrean operasional</h2></div><span class="text-sm text-slate-500">Data aktual Community</span></div>
            <div class="mt-5 divide-y divide-slate-100">
                <?php if($counts['pending'] > 0): ?><a class="flex items-center justify-between gap-3 py-4 text-sm hover:text-primary" href="<?php echo e(route('manager.join-requests.index', $organization)); ?>"><span><strong class="block text-primary-dark"><?php echo e($counts['pending']); ?> permintaan bergabung</strong><span class="text-slate-500">Tinjau calon anggota Community</span></span><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'chevron-right']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-right']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></a><?php endif; ?>
                <?php $__currentLoopData = $activitiesNeedingParticipants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><a class="flex items-center justify-between gap-3 py-4 text-sm hover:text-primary" href="<?php echo e(route('manager.activities.participants.index', [$organization, $activity])); ?>"><span><strong class="block text-primary-dark"><?php echo e($activity->pending_count); ?> peserta menunggu · <?php echo e($activity->title); ?></strong><span class="text-slate-500">Tinjau pendaftaran Activity</span></span><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'chevron-right']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-right']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php $__currentLoopData = $programsNeedingRevision; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><a class="flex items-center justify-between gap-3 py-4 text-sm hover:text-primary" href="<?php echo e(route('manager.programs.show', [$organization, $program])); ?>"><span><strong class="block text-primary-dark"><?php echo e($program->title); ?></strong><span class="text-slate-500">Ada Proposal, Logbook, atau E-LPJ yang perlu revisi</span></span><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'chevron-right']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-right']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($counts['pending'] === 0 && $activitiesNeedingParticipants->isEmpty() && $programsNeedingRevision->isEmpty()): ?><p class="py-6 text-sm text-slate-500">Tidak ada antrean tindakan saat ini.</p><?php endif; ?>
            </div>
        </div>
        <aside class="sipora-card"><h2 class="text-xl font-bold text-primary-dark">Community yang dikelola</h2><div class="mt-4 flex items-center gap-3"><span class="grid size-12 shrink-0 place-items-center overflow-hidden rounded-xl bg-primary text-lg font-bold text-white"><?php if($organization->logo_path): ?><img data-media-fallback class="size-full object-cover" src="<?php echo e(route('youth.communities.logo', $organization)); ?>" alt=""><?php else: ?><?php echo e(mb_substr($organization->name, 0, 1)); ?><?php endif; ?></span><div class="min-w-0"><p class="truncate font-bold text-primary-dark"><?php echo e($organization->name); ?></p><p class="text-xs text-slate-500"><?php echo e($organization->category->name); ?></p></div></div><dl class="mt-5 space-y-3 border-t border-slate-100 pt-4 text-sm"><div class="flex justify-between gap-3"><dt class="text-slate-500">Verifikasi</dt><dd><?php if (isset($component)) { $__componentOriginal14e36fdec6e120ea22b80172b70f253c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal14e36fdec6e120ea22b80172b70f253c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.community.status-badge','data' => ['status' => $organization->review_status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('community.status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($organization->review_status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal14e36fdec6e120ea22b80172b70f253c)): ?>
<?php $attributes = $__attributesOriginal14e36fdec6e120ea22b80172b70f253c; ?>
<?php unset($__attributesOriginal14e36fdec6e120ea22b80172b70f253c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal14e36fdec6e120ea22b80172b70f253c)): ?>
<?php $component = $__componentOriginal14e36fdec6e120ea22b80172b70f253c; ?>
<?php unset($__componentOriginal14e36fdec6e120ea22b80172b70f253c); ?>
<?php endif; ?></dd></div><div class="flex justify-between gap-3"><dt class="text-slate-500">Operasional</dt><dd class="font-bold capitalize text-primary-dark"><?php echo e($organization->operational_status); ?></dd></div><div class="flex justify-between gap-3"><dt class="text-slate-500">Profil</dt><dd class="font-bold text-primary-dark"><?php echo e($completion['percentage']); ?>%</dd></div><div class="flex justify-between gap-3"><dt class="text-slate-500">Program berjalan</dt><dd class="font-bold text-primary-dark"><?php echo e($counts['running_programs']); ?></dd></div></dl><div class="mt-5 flex flex-wrap gap-2"><a class="btn-secondary" href="<?php echo e(route('manager.profile.edit', $organization)); ?>">Kelola profil</a><a class="btn-ghost" href="<?php echo e(route('communities.show', $organization)); ?>">Halaman publik</a></div></aside>
    </section>

    <div class="grid gap-6 xl:grid-cols-2">
        <section class="sipora-card"><div class="flex items-center justify-between gap-3"><h2 class="text-xl font-bold text-primary-dark">Activity mendatang</h2><a class="text-sm font-bold text-primary hover:underline" href="<?php echo e(route('manager.activities.index', $organization)); ?>">Semua Activity</a></div><div class="mt-4 divide-y divide-slate-100"><?php $__empty_1 = true; $__currentLoopData = $upcomingActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><a class="flex items-center gap-4 py-3 hover:text-primary" href="<?php echo e(route('manager.activities.show', [$organization, $activity])); ?>"><span class="grid size-12 shrink-0 place-items-center rounded-xl bg-indigo-50 text-xs font-bold text-primary"><?php echo e($activity->start_at->translatedFormat('d M')); ?></span><span class="min-w-0 flex-1"><strong class="block truncate text-sm text-primary-dark"><?php echo e($activity->title); ?></strong><span class="text-xs text-slate-500"><?php echo e($activity->start_at->translatedFormat('d M Y, H:i')); ?></span></span><?php if (isset($component)) { $__componentOriginal846b770b82a03494b6267b1ec4369c07 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal846b770b82a03494b6267b1ec4369c07 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.activity.status-badge','data' => ['status' => $activity->execution_status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('activity.status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activity->execution_status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal846b770b82a03494b6267b1ec4369c07)): ?>
<?php $attributes = $__attributesOriginal846b770b82a03494b6267b1ec4369c07; ?>
<?php unset($__attributesOriginal846b770b82a03494b6267b1ec4369c07); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal846b770b82a03494b6267b1ec4369c07)): ?>
<?php $component = $__componentOriginal846b770b82a03494b6267b1ec4369c07; ?>
<?php unset($__componentOriginal846b770b82a03494b6267b1ec4369c07); ?>
<?php endif; ?></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p class="py-6 text-sm text-slate-500">Belum ada Activity mendatang.</p><?php endif; ?></div></section>
        <section class="sipora-card"><div class="flex items-center justify-between gap-3"><h2 class="text-xl font-bold text-primary-dark">Program berjalan</h2><a class="text-sm font-bold text-primary hover:underline" href="<?php echo e(route('manager.programs.index', $organization)); ?>">Semua Program</a></div><div class="mt-4 divide-y divide-slate-100"><?php $__empty_1 = true; $__currentLoopData = $runningPrograms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><a class="flex items-center justify-between gap-3 py-4" href="<?php echo e(route('manager.programs.show', [$organization, $program])); ?>"><span class="min-w-0"><strong class="block truncate text-sm text-primary-dark"><?php echo e($program->title); ?></strong><span class="text-xs text-slate-500">Proposal <?php echo e($program->latestProposal?->status ?? 'belum ada'); ?> · E-LPJ <?php echo e($program->latestFinancialReport?->status ?? 'belum ada'); ?></span></span><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'chevron-right']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-right']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p class="py-6 text-sm text-slate-500">Belum ada Program berjalan.</p><?php endif; ?></div></section>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.manager', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/manager/dashboard.blade.php ENDPATH**/ ?>