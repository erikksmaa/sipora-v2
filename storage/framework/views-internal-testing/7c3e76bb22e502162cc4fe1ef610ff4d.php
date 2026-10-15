<?php $__env->startSection('title', 'Halaman tidak ditemukan · SIPORA'); ?>
<?php $__env->startSection('meta_description', 'Halaman yang diminta tidak ditemukan di SIPORA.'); ?>
<?php $__env->startSection('content'); ?>
<section class="grid min-h-[65vh] place-items-center bg-surface-soft px-5 py-16 text-center"><div class="max-w-xl"><span class="mx-auto grid size-16 place-items-center rounded-2xl bg-primary-50 text-primary"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'search','class' => 'size-8']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'search','class' => 'size-8']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></span><p class="mt-5 font-mono text-sm font-bold text-accent-700">404</p><h1 class="font-heading mt-2 text-4xl font-bold text-text-primary">Halaman tidak ditemukan</h1><p class="mt-4 leading-7 text-text-secondary">Tautan mungkin sudah berubah atau konten belum tersedia untuk publik.</p><div class="mt-8 flex flex-wrap justify-center gap-3"><a class="landing-btn-primary" href="<?php echo e(route('home')); ?>">Kembali ke beranda</a><a class="btn-secondary" href="<?php echo e(route('search.index')); ?>">Cari di SIPORA</a></div></div></section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/errors/404.blade.php ENDPATH**/ ?>