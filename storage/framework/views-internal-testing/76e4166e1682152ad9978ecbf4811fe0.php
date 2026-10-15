<?php $__env->startSection('title','Evaluasi Akhir Program'); ?>
<?php $__env->startSection('verifier-content'); ?>
<div class="space-y-6"><?php if (isset($component)) { $__componentOriginal91a231a9270579fa1ae9246bd51fb785 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91a231a9270579fa1ae9246bd51fb785 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.page-header','data' => ['eyebrow' => 'Program','title' => 'Evaluasi Akhir','description' => 'Tinjau kesiapan bukti Program sebelum keputusan final.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Program','title' => 'Evaluasi Akhir','description' => 'Tinjau kesiapan bukti Program sebelum keputusan final.']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.filter-bar','data' => ['resetHref' => route('verifier.program-evaluations.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.filter-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['reset-href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('verifier.program-evaluations.index'))]); ?><label class="md:col-span-2"><span class="sr-only">Cari Program</span><input class="field" name="search" value="<?php echo e($search); ?>" placeholder="Cari Program atau Community"></label> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf27517909e1bf5f5d5b4857961084a12)): ?>
<?php $attributes = $__attributesOriginalf27517909e1bf5f5d5b4857961084a12; ?>
<?php unset($__attributesOriginalf27517909e1bf5f5d5b4857961084a12); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf27517909e1bf5f5d5b4857961084a12)): ?>
<?php $component = $__componentOriginalf27517909e1bf5f5d5b4857961084a12; ?>
<?php unset($__componentOriginalf27517909e1bf5f5d5b4857961084a12); ?>
<?php endif; ?>
<section class="table-shell"><div class="overflow-x-auto"><table class="w-full min-w-[720px]"><thead><tr><th>Program</th><th>Community</th><th>Status</th><th>Kesiapan</th><th>Aksi</th></tr></thead><tbody><?php $__empty_1 = true; $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php ($assessment=$assessments[$program->uuid()]); ?><tr class="border-t border-border hover:bg-surface-soft"><td class="font-bold text-text-primary"><?php echo e($program->title); ?></td><td><?php echo e($program->organization->name); ?></td><td><?php if (isset($component)) { $__componentOriginal08ab5d8782f78718f24293961a718c3b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal08ab5d8782f78718f24293961a718c3b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.program.status-badge','data' => ['status' => $program->execution_status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('program.status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($program->execution_status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal08ab5d8782f78718f24293961a718c3b)): ?>
<?php $attributes = $__attributesOriginal08ab5d8782f78718f24293961a718c3b; ?>
<?php unset($__attributesOriginal08ab5d8782f78718f24293961a718c3b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal08ab5d8782f78718f24293961a718c3b)): ?>
<?php $component = $__componentOriginal08ab5d8782f78718f24293961a718c3b; ?>
<?php unset($__componentOriginal08ab5d8782f78718f24293961a718c3b); ?>
<?php endif; ?></td><td><?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['tone' => $assessment['eligible']?'success':'warning']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($assessment['eligible']?'success':'warning')]); ?><?php echo e($assessment['eligible']?'Siap dievaluasi':($program->execution_status==='completed'?'Selesai':'Belum siap')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?></td><td><a class="font-bold text-primary hover:text-accent" href="<?php echo e(route('verifier.program-evaluations.show',$program)); ?>">Buka</a></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="5"><?php if (isset($component)) { $__componentOriginal3607a477fdef7402bc742abad5df9c51 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3607a477fdef7402bc742abad5df9c51 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.empty-state','data' => ['icon' => 'badge-check','title' => 'Tidak ada Program','description' => 'Belum ada Program untuk dievaluasi.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'badge-check','title' => 'Tidak ada Program','description' => 'Belum ada Program untuk dievaluasi.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $attributes = $__attributesOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__attributesOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $component = $__componentOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__componentOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?></td></tr><?php endif; ?></tbody></table></div></section><?php echo e($programs->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.verifier', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/verifier/program-evaluations/index.blade.php ENDPATH**/ ?>