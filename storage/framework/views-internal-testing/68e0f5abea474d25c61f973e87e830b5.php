<?php $__env->startSection('title', 'Permintaan Bergabung · '.$organization->name); ?>
<?php $__env->startSection('manager-content'); ?>
<div class="space-y-6">
    <?php if (isset($component)) { $__componentOriginal8e0a96c106aaf9b655be7405167c4255 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8e0a96c106aaf9b655be7405167c4255 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.page-header','data' => ['eyebrow' => 'Community','title' => 'Permintaan Bergabung','description' => 'Tinjau permintaan bergabung ke '.$organization->name.'.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Community','title' => 'Permintaan Bergabung','description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Tinjau permintaan bergabung ke '.$organization->name.'.')]); ?>
        <a class="btn-secondary" href="<?php echo e(route('manager.members.index', $organization)); ?>">Lihat anggota</a>
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
    <section class="sipora-card">
        <div class="flex items-center justify-between gap-3"><h2 class="text-xl font-bold text-primary">Menunggu keputusan</h2><span class="rounded-full bg-orange-100 px-3 py-1 text-sm font-bold text-orange-800"><?php echo e($pending->total()); ?> pending</span></div>
        <div class="mt-5 space-y-3">
            <?php $__empty_1 = true; $__currentLoopData = $pending; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $joinRequest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <article class="flex flex-col justify-between gap-4 rounded-sm border border-border bg-surface-soft p-4 sm:flex-row sm:items-center">
                    <div><h3 class="font-bold text-text-primary"><?php echo e($joinRequest->user->profile?->full_name ?? $joinRequest->user->name); ?></h3><p class="text-sm text-text-secondary"><?php echo e($joinRequest->user->email); ?></p><p class="mt-1 text-xs text-text-muted">Diajukan <?php echo e($joinRequest->requested_at?->translatedFormat('d M Y, H:i')); ?></p></div>
                    <div class="flex flex-wrap gap-2"><form method="post" action="<?php echo e(route('manager.join-requests.review', [$organization, $joinRequest])); ?>"><?php echo csrf_field(); ?><input type="hidden" name="decision" value="accepted"><button class="btn-primary" type="submit">Terima</button></form><form method="post" action="<?php echo e(route('manager.join-requests.review', [$organization, $joinRequest])); ?>"><input type="hidden" name="decision" value="rejected"><?php echo csrf_field(); ?><button class="btn-secondary !text-red-700" type="submit">Tolak</button></form></div>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <?php if (isset($component)) { $__componentOriginal3607a477fdef7402bc742abad5df9c51 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3607a477fdef7402bc742abad5df9c51 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.empty-state','data' => ['title' => 'Tidak ada permintaan pending','description' => 'Permintaan baru akan tampil di sini.','icon' => 'users']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Tidak ada permintaan pending','description' => 'Permintaan baru akan tampil di sini.','icon' => 'users']); ?>
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
        <div class="mt-5"><?php echo e($pending->links()); ?></div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.manager', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/manager/members/join-requests.blade.php ENDPATH**/ ?>