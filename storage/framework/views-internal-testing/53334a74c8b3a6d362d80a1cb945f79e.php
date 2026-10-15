<?php $__env->startSection('title','Verifikasi Activity · SIPORA'); ?>
<?php $__env->startSection('verifier-content'); ?>
<div class="space-y-6"><?php if (isset($component)) { $__componentOriginal91a231a9270579fa1ae9246bd51fb785 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91a231a9270579fa1ae9246bd51fb785 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.page-header','data' => ['eyebrow' => 'Verifikasi ekosistem','title' => 'Antrean Activity','description' => 'Tinjau Activity Community sebelum dapat dipublikasikan.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Verifikasi ekosistem','title' => 'Antrean Activity','description' => 'Tinjau Activity Community sebelum dapat dipublikasikan.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $attributes = $__attributesOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $component = $__componentOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__componentOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?>
<?php if (isset($component)) { $__componentOriginalf27517909e1bf5f5d5b4857961084a12 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf27517909e1bf5f5d5b4857961084a12 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.filter-bar','data' => ['resetHref' => route('verifier.activity-verifications.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.filter-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['reset-href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('verifier.activity-verifications.index'))]); ?><label><span class="sr-only">Cari Activity</span><input class="field" name="search" value="<?php echo e($search); ?>" placeholder="Cari Activity atau Community"></label><label><span class="sr-only">Status</span><select class="field" name="status"><?php $__currentLoopData = ['pending_review'=>'Menunggu review','revision'=>'Revisi','rejected'=>'Ditolak','approved'=>'Disetujui','all'=>'Semua status']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($value); ?>" <?php if($status===$value): echo 'selected'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf27517909e1bf5f5d5b4857961084a12)): ?>
<?php $attributes = $__attributesOriginalf27517909e1bf5f5d5b4857961084a12; ?>
<?php unset($__attributesOriginalf27517909e1bf5f5d5b4857961084a12); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf27517909e1bf5f5d5b4857961084a12)): ?>
<?php $component = $__componentOriginalf27517909e1bf5f5d5b4857961084a12; ?>
<?php unset($__componentOriginalf27517909e1bf5f5d5b4857961084a12); ?>
<?php endif; ?>
<section class="table-shell"><div class="overflow-x-auto"><table class="w-full min-w-[760px]"><thead><tr><th>Activity</th><th>Community</th><th>Kategori</th><th>Diajukan</th><th>Status</th><th>Aksi</th></tr></thead><tbody><?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr class="border-t border-border hover:bg-surface-soft"><td class="font-bold text-text-primary"><?php echo e($activity->title); ?></td><td><?php echo e($activity->organization->name); ?></td><td><?php echo e($activity->category->name); ?></td><td><?php echo e($activity->created_at->translatedFormat('d M Y')); ?></td><td><?php if (isset($component)) { $__componentOriginal846b770b82a03494b6267b1ec4369c07 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal846b770b82a03494b6267b1ec4369c07 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.activity.status-badge','data' => ['status' => $activity->review_status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('activity.status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activity->review_status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal846b770b82a03494b6267b1ec4369c07)): ?>
<?php $attributes = $__attributesOriginal846b770b82a03494b6267b1ec4369c07; ?>
<?php unset($__attributesOriginal846b770b82a03494b6267b1ec4369c07); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal846b770b82a03494b6267b1ec4369c07)): ?>
<?php $component = $__componentOriginal846b770b82a03494b6267b1ec4369c07; ?>
<?php unset($__componentOriginal846b770b82a03494b6267b1ec4369c07); ?>
<?php endif; ?></td><td><a class="font-bold text-primary hover:text-accent" href="<?php echo e(route('verifier.activity-verifications.show',$activity)); ?>">Tinjau</a></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="6"><?php if (isset($component)) { $__componentOriginal3607a477fdef7402bc742abad5df9c51 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3607a477fdef7402bc742abad5df9c51 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.empty-state','data' => ['icon' => 'clipboard-check','title' => 'Tidak ada Activity','description' => 'Tidak ada Activity untuk filter ini.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'clipboard-check','title' => 'Tidak ada Activity','description' => 'Tidak ada Activity untuk filter ini.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $attributes = $__attributesOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__attributesOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $component = $__componentOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__componentOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?></td></tr><?php endif; ?></tbody></table></div></section><?php echo e($activities->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.verifier', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/verifier/activities/index.blade.php ENDPATH**/ ?>