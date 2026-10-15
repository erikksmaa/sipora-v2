<?php $__env->startSection('title', 'Edit Pengajuan Komunitas · SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<div class="mx-auto max-w-4xl"><div class="mb-6 flex flex-wrap items-end justify-between gap-4"><div><a class="text-sm font-bold text-[#243378]" href="<?php echo e(route('youth.communities.show', $organization)); ?>">← Kembali ke detail</a><h1 class="mt-3 text-3xl font-extrabold text-[#243378]">Edit pengajuan</h1></div><?php if (isset($component)) { $__componentOriginal14e36fdec6e120ea22b80172b70f253c = $component; } ?>
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
<?php endif; ?></div><?php if($organization->review_status === 'revision' && $organization->latestVerificationRequest?->review_notes): ?><div class="mb-6 rounded-xl border border-orange-300 bg-orange-50 p-4"><p class="font-bold text-orange-900">Catatan revisi</p><p class="mt-1 text-sm text-orange-800"><?php echo e($organization->latestVerificationRequest->review_notes); ?></p></div><?php endif; ?> <?php echo $__env->make('youth.communities._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.youth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/youth/communities/edit.blade.php ENDPATH**/ ?>