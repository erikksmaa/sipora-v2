<?php $__env->startSection('title', $activity->title.' · SIPORA'); ?>
<?php $__env->startSection('meta_description', str($activity->description ?: 'Activity publik SIPORA.')->squish()->limit(155)); ?>
<?php $__env->startSection('canonical', route('activities.show', $activity)); ?>
<?php $__env->startSection('content'); ?>
<article class="bg-surface-soft">
    <section class="public-page-intro public-page-intro--activity"><div class="mx-auto grid max-w-7xl items-center gap-8 lg:grid-cols-[1fr_1.05fr]"><div><nav class="mb-5 flex items-center gap-2 text-sm text-white/85" aria-label="Breadcrumb"><a class="font-bold hover:text-white" href="<?php echo e(route('activities.index')); ?>">Activity</a><span>/</span><span class="truncate" aria-current="page"><?php echo e($activity->title); ?></span></nav><div class="flex flex-wrap gap-2"><?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['tone' => 'orange']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => 'orange']); ?><?php echo e($activity->category->name); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?><?php if($publicProgram): ?><a href="<?php echo e(route('programs.show', $publicProgram)); ?>"><?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['tone' => 'blue','icon' => 'notebook']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => 'blue','icon' => 'notebook']); ?>Bagian dari <?php echo e($publicProgram->title); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?></a><?php endif; ?></div><h1 class="font-heading mt-4 text-4xl font-bold tracking-tight sm:text-5xl"><?php echo e($activity->title); ?></h1><p class="mt-4 text-white/85">Diselenggarakan oleh <a class="font-bold text-white underline hover:text-accent-300" href="<?php echo e(route('communities.show', $activity->organization)); ?>"><?php echo e($activity->organization->name); ?></a></p><dl class="mt-6 grid gap-3 sm:grid-cols-2"><div class="flex items-center gap-3 rounded-xl border border-white/10 bg-white/10 p-3"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'calendar','class' => 'size-5 text-accent-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'calendar','class' => 'size-5 text-accent-300']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?><div><dt class="text-xs text-white/70">Jadwal utama</dt><dd class="font-bold text-white"><?php echo e($activity->start_at->translatedFormat('d M Y, H:i')); ?></dd></div></div><div class="flex items-center gap-3 rounded-xl border border-white/10 bg-white/10 p-3"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'map-pin','class' => 'size-5 text-accent-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'map-pin','class' => 'size-5 text-accent-300']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?><div><dt class="text-xs text-white/70">Lokasi</dt><dd class="font-bold text-white"><?php echo e($activity->venue_name ?: 'Daring'); ?></dd></div></div></dl></div><div class="h-72 overflow-hidden rounded-2xl border-4 border-white/20 shadow-lg sm:h-96"><?php if($activity->poster_path): ?><img data-media-fallback class="h-full w-full object-cover" src="<?php echo e(route('activities.poster',$activity)); ?>" alt="Poster <?php echo e($activity->title); ?>"><?php else: ?><?php if (isset($component)) { $__componentOriginal90c45f6e59f555e103ea685642b6737c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal90c45f6e59f555e103ea685642b6737c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.media-fallback','data' => ['label' => $activity->category->name,'icon' => 'activity']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.media-fallback'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activity->category->name),'icon' => 'activity']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal90c45f6e59f555e103ea685642b6737c)): ?>
<?php $attributes = $__attributesOriginal90c45f6e59f555e103ea685642b6737c; ?>
<?php unset($__attributesOriginal90c45f6e59f555e103ea685642b6737c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal90c45f6e59f555e103ea685642b6737c)): ?>
<?php $component = $__componentOriginal90c45f6e59f555e103ea685642b6737c; ?>
<?php unset($__componentOriginal90c45f6e59f555e103ea685642b6737c); ?>
<?php endif; ?><?php endif; ?></div></div></section>
    <div class="mx-auto grid max-w-7xl gap-8 px-5 py-10 lg:grid-cols-[minmax(0,1fr)_360px]"><div class="space-y-8"><section class="public-panel"><p class="text-xs font-bold uppercase tracking-wider text-accent-600">Tentang Activity</p><h2 class="public-section-title mt-1">Deskripsi kegiatan</h2><p class="mt-5 whitespace-pre-line text-base leading-8 text-text-secondary"><?php echo e($activity->description); ?></p></section><section class="public-panel"><div class="flex items-center gap-3"><span class="grid size-11 place-items-center rounded-xl bg-primary-50 text-primary shadow-xs"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'calendar','class' => 'size-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'calendar','class' => 'size-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></span><div><p class="text-xs font-bold uppercase tracking-wider text-text-muted">Jadwal</p><h2 class="text-xl font-bold text-text-primary">Sesi Activity</h2></div></div><div class="mt-5 space-y-3"><?php $__empty_1 = true; $__currentLoopData = $activity->sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><article class="grid gap-3 rounded-xl border border-border p-4 sm:grid-cols-[48px_1fr]"><span class="grid size-12 place-items-center rounded-xl bg-primary-50 font-mono font-bold text-primary"><?php echo e($session->session_number); ?></span><div><h3 class="font-bold text-text-primary"><?php echo e($session->title); ?></h3><p class="public-meta mt-1"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'clock','class' => 'size-4 text-text-muted']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'clock','class' => 'size-4 text-text-muted']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?><?php echo e($session->start_at->translatedFormat('d M Y, H:i')); ?>–<?php echo e($session->end_at->format('H:i')); ?></p><p class="public-meta mt-1"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'map-pin','class' => 'size-4 text-text-muted']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'map-pin','class' => 'size-4 text-text-muted']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?><?php echo e($session->venue_name ?: 'Daring'); ?></p><?php if($session->description): ?><p class="mt-2 text-sm leading-6 text-text-secondary"><?php echo e($session->description); ?></p><?php endif; ?></div></article><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if (isset($component)) { $__componentOriginal3607a477fdef7402bc742abad5df9c51 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3607a477fdef7402bc742abad5df9c51 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.empty-state','data' => ['title' => 'Jadwal belum tersedia','description' => 'Jadwal sesi belum dipublikasikan oleh penyelenggara.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Jadwal belum tersedia','description' => 'Jadwal sesi belum dipublikasikan oleh penyelenggara.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $attributes = $__attributesOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__attributesOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $component = $__componentOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__componentOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?><?php endif; ?></div></section></div>
        <aside class="space-y-5 lg:sticky lg:top-24 lg:self-start"><section class="rounded-2xl border border-orange-200 bg-[#FFF6DF]/60 p-6 shadow-xs"><p class="text-xs font-bold uppercase tracking-wider text-accent-700">Pendaftaran</p><h2 class="mt-1 text-xl font-bold text-text-primary"><?php echo e($activity->registration_mode === 'open' ? 'Pendaftaran terbuka' : 'Memerlukan persetujuan'); ?></h2><p class="mt-3 text-sm leading-6 text-text-secondary">Diterima <?php echo e($acceptedCount); ?> / <?php echo e($activity->quota ?? 'tanpa batas'); ?></p><p class="mt-1 text-xs text-text-muted">Batas <?php echo e($activity->registration_close_at?->translatedFormat('d M Y H:i') ?? $activity->start_at->translatedFormat('d M Y H:i')); ?></p><?php if($participation): ?><div class="mt-4"><?php if (isset($component)) { $__componentOriginal5ea0e4c370a0edd3f192dc4a63843075 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ea0e4c370a0edd3f192dc4a63843075 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.activity.registration-badge','data' => ['status' => $participation->registration_status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('activity.registration-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($participation->registration_status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5ea0e4c370a0edd3f192dc4a63843075)): ?>
<?php $attributes = $__attributesOriginal5ea0e4c370a0edd3f192dc4a63843075; ?>
<?php unset($__attributesOriginal5ea0e4c370a0edd3f192dc4a63843075); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5ea0e4c370a0edd3f192dc4a63843075)): ?>
<?php $component = $__componentOriginal5ea0e4c370a0edd3f192dc4a63843075; ?>
<?php unset($__componentOriginal5ea0e4c370a0edd3f192dc4a63843075); ?>
<?php endif; ?></div><?php endif; ?> <?php $__errorArgs = ['registration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-4 rounded-xl border border-red-200 bg-danger-soft p-3 text-sm font-semibold text-danger"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> <?php if(auth()->guard()->check()): ?> <?php if (\Illuminate\Support\Facades\Blade::check('role', 'youth')): ?> <?php if(!$participation): ?><form class="mt-5 space-y-3" method="POST" action="<?php echo e(route('activities.register',$activity)); ?>"><?php echo csrf_field(); ?><label><span class="label">Catatan pendaftaran (opsional)</span><textarea class="field" name="registration_notes" maxlength="2000" placeholder="Motivasi atau informasi untuk pengelola"></textarea></label><button class="landing-btn-primary w-full">Daftar Activity</button></form><?php elseif(in_array($participation->registration_status,['pending','accepted'],true)): ?><form class="mt-5" method="POST" action="<?php echo e(route('activities.registration.destroy',$activity)); ?>"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="btn-secondary w-full">Batalkan pendaftaran</button></form><?php endif; ?> <?php endif; ?> <?php else: ?><a class="landing-btn-primary mt-5 w-full" href="<?php echo e(route('login')); ?>">Masuk untuk mendaftar</a><?php endif; ?></section><section class="public-panel"><p class="text-xs font-bold uppercase tracking-wider text-text-muted">Penyelenggara</p><a class="mt-3 flex items-center gap-3 transition-colors hover:text-accent" href="<?php echo e(route('communities.show', $activity->organization)); ?>"><span class="grid size-11 place-items-center rounded-xl bg-primary text-white shadow-xs"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'building','class' => 'size-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'building','class' => 'size-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></span><span><strong class="block text-text-primary"><?php echo e($activity->organization->name); ?></strong><small class="text-text-muted">Lihat profil Community</small></span></a></section></aside>
    </div>
</article>
<?php if (isset($component)) { $__componentOriginalbbbad3fe46bb04893c0b28ca83872dc6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbbbad3fe46bb04893c0b28ca83872dc6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.media.demo-gallery','data' => ['domain' => 'activities','identity' => $activity->slug]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('media.demo-gallery'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['domain' => 'activities','identity' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activity->slug)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbbbad3fe46bb04893c0b28ca83872dc6)): ?>
<?php $attributes = $__attributesOriginalbbbad3fe46bb04893c0b28ca83872dc6; ?>
<?php unset($__attributesOriginalbbbad3fe46bb04893c0b28ca83872dc6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbbbad3fe46bb04893c0b28ca83872dc6)): ?>
<?php $component = $__componentOriginalbbbad3fe46bb04893c0b28ca83872dc6; ?>
<?php unset($__componentOriginalbbbad3fe46bb04893c0b28ca83872dc6); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/public/activities/show.blade.php ENDPATH**/ ?>