<?php $__env->startSection('title', 'Verifikasi Sertifikat · SIPORA'); ?>
<?php $__env->startSection('meta_description', 'Periksa keaslian sertifikat Activity yang diterbitkan melalui SIPORA Kabupaten Pemalang.'); ?>
<?php $__env->startSection('canonical', request()->url()); ?>
<?php $__env->startSection('content'); ?>
<section class="min-h-[70vh] bg-surface-muted px-5 py-14"><div class="mx-auto max-w-3xl"><?php if($certificate): ?><article class="overflow-hidden rounded-3xl border border-emerald-200 bg-white shadow-card"><header class="relative overflow-hidden bg-primary-dark px-6 py-8 text-white sm:px-10"><div class="absolute inset-0 opacity-15" style="background-image:linear-gradient(rgb(255 255 255 / .3) 1px,transparent 1px),linear-gradient(90deg,rgb(255 255 255 / .3) 1px,transparent 1px);background-size:36px 36px"></div><div class="relative flex items-center gap-4"><span class="grid size-14 place-items-center rounded-2xl bg-emerald-400/20 text-emerald-200"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'badge-check','class' => 'size-7']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'badge-check','class' => 'size-7']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></span><div><p class="text-xs font-extrabold uppercase tracking-wider text-orange-300">Verifikasi Sertifikat SIPORA</p><h1 class="font-heading mt-1 text-3xl font-bold">Sertifikat valid</h1></div></div></header><div class="p-6 sm:p-10"><div class="mb-7 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-900"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'shield-check','class' => 'mt-0.5 text-emerald-700']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'shield-check','class' => 'mt-0.5 text-emerald-700']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?><div><p class="font-bold">Data ditemukan dan terverifikasi</p><p class="mt-1 text-sm">Rekaman ini diterbitkan dari partisipasi Activity SIPORA.</p></div></div><dl class="grid gap-5 sm:grid-cols-2"><div><dt class="text-sm text-slate-500">Penerima</dt><dd class="mt-1 font-bold"><?php echo e($certificate->user->profile?->full_name ?: $certificate->user->name); ?></dd></div><div><dt class="text-sm text-slate-500">Activity</dt><dd class="mt-1 font-bold"><?php if($activityUrl): ?><a class="text-primary underline" href="<?php echo e($activityUrl); ?>"><?php echo e($certificate->name); ?></a><?php else: ?><?php echo e($certificate->name); ?><?php endif; ?></dd></div><div><dt class="text-sm text-slate-500">Penyelenggara</dt><dd class="mt-1 font-bold"><?php if($communityUrl): ?><a class="text-primary underline" href="<?php echo e($communityUrl); ?>"><?php echo e($certificate->issuer_name); ?></a><?php else: ?><?php echo e($certificate->issuer_name); ?><?php endif; ?></dd></div><div><dt class="text-sm text-slate-500">Tanggal terbit</dt><dd class="mt-1 font-bold"><?php echo e($certificate->issued_at->translatedFormat('d F Y')); ?></dd></div><div class="rounded-xl bg-slate-50 p-4 sm:col-span-2"><dt class="text-sm text-slate-500">Nomor sertifikat</dt><dd class="mt-1 break-all font-mono text-sm font-bold text-primary"><?php echo e($certificate->certificate_number); ?></dd></div><div class="sm:col-span-2"><dt class="text-sm text-slate-500">Kode verifikasi</dt><dd class="mt-1 break-all font-mono text-xs"><?php echo e($certificate->verification_code); ?></dd></div></dl></div></article><?php else: ?><article class="rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-card sm:p-12"><span class="mx-auto grid size-14 place-items-center rounded-2xl bg-slate-100 text-slate-600"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'search','class' => 'size-7']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'search','class' => 'size-7']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></span><p class="mt-5 text-xs font-extrabold uppercase tracking-wider text-orange-600">Verifikasi Sertifikat SIPORA</p><h1 class="font-heading mt-2 text-3xl font-bold text-text-primary">Sertifikat tidak valid</h1><p class="mx-auto mt-3 max-w-lg text-slate-600">Kode sertifikat tidak ditemukan atau rekamannya belum terverifikasi. Periksa kembali kode yang digunakan.</p></article><?php endif; ?><div class="mt-6 text-center"><a class="public-link" href="<?php echo e(route('activities.index')); ?>">Jelajahi Activity publik <?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
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
<?php endif; ?></a></div></div></section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/public/certificates/verify.blade.php ENDPATH**/ ?>