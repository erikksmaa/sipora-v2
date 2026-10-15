<?php $__env->startSection('title','Verifikasi Community · SIPORA'); ?>
<?php $__env->startSection('verifier-content'); ?>
<div class="space-y-6"><?php if (isset($component)) { $__componentOriginal91a231a9270579fa1ae9246bd51fb785 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91a231a9270579fa1ae9246bd51fb785 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.page-header','data' => ['eyebrow' => 'Verifikasi ekosistem','title' => 'Antrean Community','description' => 'Tinjau pengajuan Community yang telah dikirim oleh Youth.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Verifikasi ekosistem','title' => 'Antrean Community','description' => 'Tinjau pengajuan Community yang telah dikirim oleh Youth.']); ?> <?php $__env->slot('actions', null, []); ?> <?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['tone' => 'info']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => 'info']); ?><?php echo e($counts['pending_review'] ?? 0); ?> menunggu <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?> <?php $__env->endSlot(); ?> <?php echo $__env->renderComponent(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.filter-bar','data' => ['resetHref' => route('verifier.community-verifications.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.filter-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['reset-href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('verifier.community-verifications.index'))]); ?><label><span class="sr-only">Cari Community atau pemohon</span><input class="field" name="search" value="<?php echo e($search); ?>" placeholder="Cari Community atau pemohon"></label><label><span class="sr-only">Status</span><select class="field" name="status"><?php $__currentLoopData = ['pending_review'=>'Menunggu review','revision'=>'Revisi','approved'=>'Disetujui','rejected'=>'Ditolak','all'=>'Semua status']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($value); ?>" <?php if($status===$value): echo 'selected'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf27517909e1bf5f5d5b4857961084a12)): ?>
<?php $attributes = $__attributesOriginalf27517909e1bf5f5d5b4857961084a12; ?>
<?php unset($__attributesOriginalf27517909e1bf5f5d5b4857961084a12); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf27517909e1bf5f5d5b4857961084a12)): ?>
<?php $component = $__componentOriginalf27517909e1bf5f5d5b4857961084a12; ?>
<?php unset($__componentOriginalf27517909e1bf5f5d5b4857961084a12); ?>
<?php endif; ?>
<section class="table-shell"><div class="overflow-x-auto"><table class="w-full min-w-[820px]"><thead><tr><th>Community</th><th>Kategori</th><th>Lokasi</th><th>Pemohon</th><th>Diajukan</th><th>Status</th><th>Aksi</th></tr></thead><tbody><?php $__empty_1 = true; $__currentLoopData = $communities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $community): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr class="border-t border-border hover:bg-surface-soft"><td class="font-bold text-text-primary"><?php echo e($community->name); ?></td><td><?php echo e($community->category->name); ?></td><td><?php echo e($community->administrativeArea?->name ?? '—'); ?></td><td><span class="block font-semibold"><?php echo e($community->creator?->profile?->full_name ?? $community->creator?->name ?? '—'); ?></span><span class="text-xs text-text-muted"><?php echo e($community->creator?->email); ?></span></td><td><?php echo e($community->latestVerificationRequest?->submitted_at?->translatedFormat('d M Y, H:i') ?? '—'); ?></td><td><?php if (isset($component)) { $__componentOriginal14e36fdec6e120ea22b80172b70f253c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal14e36fdec6e120ea22b80172b70f253c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.community.status-badge','data' => ['status' => $community->review_status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('community.status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($community->review_status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal14e36fdec6e120ea22b80172b70f253c)): ?>
<?php $attributes = $__attributesOriginal14e36fdec6e120ea22b80172b70f253c; ?>
<?php unset($__attributesOriginal14e36fdec6e120ea22b80172b70f253c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal14e36fdec6e120ea22b80172b70f253c)): ?>
<?php $component = $__componentOriginal14e36fdec6e120ea22b80172b70f253c; ?>
<?php unset($__componentOriginal14e36fdec6e120ea22b80172b70f253c); ?>
<?php endif; ?></td><td><a class="font-bold text-primary hover:text-accent" href="<?php echo e(route('verifier.community-verifications.show',$community)); ?>">Tinjau</a></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="7"><?php if (isset($component)) { $__componentOriginal3607a477fdef7402bc742abad5df9c51 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3607a477fdef7402bc742abad5df9c51 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.empty-state','data' => ['icon' => 'building','title' => 'Tidak ada pengajuan Community','description' => 'Tidak ada pengajuan Community untuk filter ini.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'building','title' => 'Tidak ada pengajuan Community','description' => 'Tidak ada pengajuan Community untuk filter ini.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $attributes = $__attributesOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__attributesOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $component = $__componentOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__componentOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?></td></tr><?php endif; ?></tbody></table></div></section><?php echo e($communities->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.verifier', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/verifier/communities/index.blade.php ENDPATH**/ ?>