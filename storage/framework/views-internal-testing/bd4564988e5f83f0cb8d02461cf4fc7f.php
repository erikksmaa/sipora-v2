<?php $__env->startSection('title', 'Detail '.$title.' · SIPORA'); ?>
<?php $__env->startSection($workspace === 'admin' ? 'content' : 'verifier-content'); ?>
<div class="space-y-6">
    <?php if (isset($component)) { $__componentOriginal91a231a9270579fa1ae9246bd51fb785 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91a231a9270579fa1ae9246bd51fb785 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.page-header','data' => ['eyebrow' => 'Data Ekosistem','title' => $record['name'],'description' => $title.' · Ringkasan baca saja']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Data Ekosistem','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($record['name']),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($title.' · Ringkasan baca saja')]); ?> <?php $__env->slot('actions', null, []); ?> <a class="btn-outline" href="<?php echo e(route($workspace.'.directory.'.$kind.'.index')); ?>">Kembali ke direktori</a><?php if($record['public_url']): ?><a class="btn-primary" href="<?php echo e($record['public_url']); ?>">Lihat profil publik</a><?php endif; ?> <?php $__env->endSlot(); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $attributes = $__attributesOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $component = $__componentOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__componentOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?>
    <section class="sipora-card">
        <?php if($record['photo']): ?><img data-media-fallback src="<?php echo e($record['photo']); ?>" alt="" class="mb-5 size-20 rounded border border-border bg-white object-contain"><?php endif; ?>
        <dl class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3"><?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index=>$column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="min-w-0 rounded border border-border bg-surface-soft p-4"><dt class="text-xs font-semibold text-text-secondary"><?php echo e($column); ?></dt><dd class="mt-2 break-words font-semibold text-text-primary"><?php echo e($record['cells'][$index]); ?></dd></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></dl>
    </section>
    <p class="text-sm text-text-secondary">Direktori tidak mengubah status atau memberikan hak pengelolaan. Verifikasi tetap dilakukan melalui antrean dan kewenangan existing.</p>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($workspace === 'admin' ? 'layouts.admin' : 'layouts.verifier', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/government/directory/show.blade.php ENDPATH**/ ?>