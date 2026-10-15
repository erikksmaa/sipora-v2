<?php $__env->startSection('title', 'Pendaftaran Activity Saya · SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <?php if (isset($component)) { $__componentOriginal8e0a96c106aaf9b655be7405167c4255 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8e0a96c106aaf9b655be7405167c4255 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.page-header','data' => ['eyebrow' => 'Activity','title' => 'Pendaftaran Saya','description' => 'Pantau status pendaftaran dan pengalaman Activity milikmu.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Activity','title' => 'Pendaftaran Saya','description' => 'Pantau status pendaftaran dan pengalaman Activity milikmu.']); ?>
        <a class="btn-secondary" href="<?php echo e(route('activities.index')); ?>"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
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
<?php endif; ?>Jelajahi Activity</a>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8e0a96c106aaf9b655be7405167c4255)): ?>
<?php $attributes = $__attributesOriginal8e0a96c106aaf9b655be7405167c4255; ?>
<?php unset($__attributesOriginal8e0a96c106aaf9b655be7405167c4255); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e0a96c106aaf9b655be7405167c4255)): ?>
<?php $component = $__componentOriginal8e0a96c106aaf9b655be7405167c4255; ?>
<?php unset($__componentOriginal8e0a96c106aaf9b655be7405167c4255); ?>
<?php endif; ?>

    <?php
        $tabs = ['' => 'Semua', 'pending' => 'Menunggu', 'accepted' => 'Diterima', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'];
    ?>
    <nav class="flex gap-2 overflow-x-auto border-b border-slate-200 pb-3" aria-label="Filter pendaftaran Activity">
        <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('youth.activities.index', $value ? ['status' => $value] : [])); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['min-h-10 whitespace-nowrap rounded-lg px-4 py-2 text-sm font-bold transition', 'bg-primary text-white' => $status === $value, 'text-slate-600 hover:bg-indigo-50 hover:text-primary' => $status !== $value]); ?>" <?php if($status === $value): ?> aria-current="page" <?php endif; ?>><?php echo e($label); ?></a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </nav>

    <div class="grid gap-4 xl:grid-cols-2">
        <?php $__empty_1 = true; $__currentLoopData = $participations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $participation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $activity = $participation->activity;
                $registrationLabel = match($participation->registration_status) { 'pending' => 'Menunggu', 'accepted' => 'Diterima', 'rejected' => 'Ditolak', 'cancelled' => 'Dibatalkan', default => ucfirst($participation->registration_status) };
                $tone = match($participation->registration_status) { 'accepted' => 'success', 'pending' => 'warning', 'rejected', 'cancelled' => 'danger', default => 'neutral' };
            ?>
            <article class="overflow-hidden rounded-2xl border border-indigo-100 bg-white shadow-card">
                <div class="grid sm:grid-cols-[150px_1fr]">
                    <?php if (isset($component)) { $__componentOriginal90c45f6e59f555e103ea685642b6737c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal90c45f6e59f555e103ea685642b6737c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.media-fallback','data' => ['icon' => 'activity','label' => $activity->category?->name ?? 'Activity','class' => 'min-h-36 rounded-none']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.media-fallback'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'activity','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activity->category?->name ?? 'Activity'),'class' => 'min-h-36 rounded-none']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal90c45f6e59f555e103ea685642b6737c)): ?>
<?php $attributes = $__attributesOriginal90c45f6e59f555e103ea685642b6737c; ?>
<?php unset($__attributesOriginal90c45f6e59f555e103ea685642b6737c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal90c45f6e59f555e103ea685642b6737c)): ?>
<?php $component = $__componentOriginal90c45f6e59f555e103ea685642b6737c; ?>
<?php unset($__componentOriginal90c45f6e59f555e103ea685642b6737c); ?>
<?php endif; ?>
                    <div class="p-5">
                        <div class="flex flex-wrap items-start justify-between gap-3"><div><p class="text-xs font-extrabold uppercase tracking-wider text-interactive"><?php echo e($activity->category?->name ?? 'Activity'); ?></p><h2 class="mt-1 text-lg font-extrabold text-text-primary"><?php echo e($activity->title); ?></h2><p class="mt-1 text-sm text-text-secondary"><?php echo e($activity->organization->name); ?></p></div><?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['tone' => $tone]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tone)]); ?><?php echo e($registrationLabel); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?></div>
                        <div class="mt-4 flex flex-wrap gap-x-5 gap-y-2 text-xs font-semibold text-slate-500"><span class="inline-flex items-center gap-1.5"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'calendar','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'calendar','class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?><?php echo e($activity->start_at->translatedFormat('d M Y')); ?></span><span>Completion: <?php echo e($participation->completion_status === 'completed' ? 'Selesai' : ($participation->completion_status === 'no_show' ? 'Tidak hadir' : 'Berjalan')); ?></span></div>
                        <div class="mt-5 flex flex-wrap gap-2"><a class="btn-secondary !min-h-9 !px-3 text-sm" href="<?php echo e(route('activities.show', $activity)); ?>">Lihat Activity</a><?php if($participation->completion_status === 'completed'): ?><a class="btn-secondary !min-h-9 !px-3 text-sm" href="<?php echo e(route('youth.passport.show', $participation)); ?>">Lihat Passport</a><?php endif; ?> <?php if($participation->certificate): ?><a class="btn-primary !min-h-9 !px-3 text-sm" href="<?php echo e(route('youth.certificates.show', $participation->certificate)); ?>">Lihat Sertifikat</a><?php endif; ?></div>
                    </div>
                </div>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <?php if (isset($component)) { $__componentOriginal3607a477fdef7402bc742abad5df9c51 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3607a477fdef7402bc742abad5df9c51 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.empty-state','data' => ['class' => 'xl:col-span-2','title' => 'Belum ada pendaftaran','description' => 'Activity yang kamu daftar akan muncul di ruang ini.','icon' => 'activity','href' => route('activities.index'),'action' => 'Jelajahi Activity']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'xl:col-span-2','title' => 'Belum ada pendaftaran','description' => 'Activity yang kamu daftar akan muncul di ruang ini.','icon' => 'activity','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('activities.index')),'action' => 'Jelajahi Activity']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $attributes = $__attributesOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__attributesOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $component = $__componentOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__componentOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?>
        <?php endif; ?>
    </div>
    <?php echo e($participations->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.youth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/youth/activities/index.blade.php ENDPATH**/ ?>