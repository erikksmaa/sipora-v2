<?php $__env->startSection('title', 'Update Community · SIPORA'); ?>
<?php $__env->startSection('manager-content'); ?>
<div class="space-y-6"><?php if (isset($component)) { $__componentOriginal91a231a9270579fa1ae9246bd51fb785 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91a231a9270579fa1ae9246bd51fb785 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.page-header','data' => ['eyebrow' => 'Community','title' => 'Update Community','description' => 'Bagikan kabar singkat sebagai Community yang sedang dikelola.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Community','title' => 'Update Community','description' => 'Bagikan kabar singkat sebagai Community yang sedang dikelola.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $attributes = $__attributesOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $component = $__componentOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__componentOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?><section class="sipora-card p-5"><form class="space-y-4" method="post" enctype="multipart/form-data" action="<?php echo e(route('manager.social.store', $organization)); ?>"><?php echo csrf_field(); ?><label class="block text-sm font-bold text-text-primary">Update publik<textarea name="body" class="field mt-2 min-h-32 w-full" maxlength="3000" required><?php echo e(old('body')); ?></textarea></label><label class="block text-sm text-text-primary">Gambar opsional · JPG/PNG/WEBP, maks. 3 MB<input class="field mt-2 w-full" type="file" name="image" accept="image/jpeg,image/png,image/webp"></label><button class="btn-primary" type="submit">Bagikan sebagai <?php echo e($organization->name); ?></button></form></section>
<?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><article class="sipora-card p-5"><p class="text-xs text-text-muted"><?php echo e($post->created_at->diffForHumans()); ?> · <?php echo e($post->visibility); ?> <?php if($post->is_hidden): ?> · Disembunyikan Admin <?php endif; ?> <?php if($post->is_removed): ?> · Dihapus pengelola <?php endif; ?></p><p class="mt-3 whitespace-pre-line break-words text-sm leading-7 text-text-secondary"><?php echo e($post->body); ?></p><?php if(!$post->is_hidden && !$post->is_removed): ?><a class="btn-outline mt-3" href="<?php echo e(route('social.edit', $post)); ?>">Kelola post</a><?php endif; ?></article><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p class="text-sm text-text-muted">Belum ada update Community.</p><?php endif; ?><?php echo e($posts->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.manager', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/manager/social/index.blade.php ENDPATH**/ ?>