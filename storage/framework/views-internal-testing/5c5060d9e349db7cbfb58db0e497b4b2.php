<?php $__env->startSection('title', 'Antrean Verifikasi Identitas · SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <?php if (isset($component)) { $__componentOriginal91a231a9270579fa1ae9246bd51fb785 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91a231a9270579fa1ae9246bd51fb785 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.page-header','data' => ['eyebrow' => 'Verifikasi identitas','title' => 'Antrean Identitas Pemuda','description' => 'Tinjau informasi minimum di antrean. Data identitas lengkap hanya tersedia pada detail privat.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Verifikasi identitas','title' => 'Antrean Identitas Pemuda','description' => 'Tinjau informasi minimum di antrean. Data identitas lengkap hanya tersedia pada detail privat.']); ?>
         <?php $__env->slot('actions', null, []); ?> <?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['tone' => 'info','icon' => 'shield-check']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => 'info','icon' => 'shield-check']); ?><?php echo e($counts['pending'] ?? 0); ?> menunggu <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?> <?php $__env->endSlot(); ?>
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
    <section class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4" aria-label="Ringkasan status">
        <?php $__currentLoopData = ['pending' => ['Menunggu', 'info'], 'revision' => ['Perlu revisi', 'warning'], 'verified' => ['Disetujui', 'success'], 'rejected' => ['Ditolak', 'danger']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => [$label, $tone]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if (isset($component)) { $__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.metric-card','data' => ['title' => $label,'value' => number_format($counts[$key] ?? 0),'icon' => 'shield-check','tone' => $tone === 'warning' ? 'accent' : $tone,'href' => route('admin.identity-verifications.index', ['status' => $key]),'action' => 'Lihat antrean']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.metric-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($label),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($counts[$key] ?? 0)),'icon' => 'shield-check','tone' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tone === 'warning' ? 'accent' : $tone),'href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.identity-verifications.index', ['status' => $key])),'action' => 'Lihat antrean']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe)): ?>
<?php $attributes = $__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe; ?>
<?php unset($__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe)): ?>
<?php $component = $__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe; ?>
<?php unset($__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe); ?>
<?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </section>
    <?php if (isset($component)) { $__componentOriginalf27517909e1bf5f5d5b4857961084a12 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf27517909e1bf5f5d5b4857961084a12 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.filter-bar','data' => ['resetHref' => route('admin.identity-verifications.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.filter-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['reset-href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.identity-verifications.index'))]); ?>
        <label><span class="sr-only">Cari nama atau email</span><input class="field" type="search" name="search" value="<?php echo e($search); ?>" placeholder="Cari nama atau email Youth"></label>
        <label><span class="sr-only">Filter status</span><select class="field" name="status"><?php $__currentLoopData = ['pending'=>'Menunggu','revision'=>'Perlu revisi','verified'=>'Disetujui','rejected'=>'Ditolak','all'=>'Semua status']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($value); ?>" <?php if($status === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf27517909e1bf5f5d5b4857961084a12)): ?>
<?php $attributes = $__attributesOriginalf27517909e1bf5f5d5b4857961084a12; ?>
<?php unset($__attributesOriginalf27517909e1bf5f5d5b4857961084a12); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf27517909e1bf5f5d5b4857961084a12)): ?>
<?php $component = $__componentOriginalf27517909e1bf5f5d5b4857961084a12; ?>
<?php unset($__componentOriginalf27517909e1bf5f5d5b4857961084a12); ?>
<?php endif; ?>
    <section class="table-shell">
        <div class="overflow-x-auto"><table class="w-full min-w-[760px]"><thead><tr><th>Youth</th><th>Dokumen</th><th>Diajukan</th><th>Status</th><th class="text-right">Aksi</th></tr></thead><tbody><?php $__empty_1 = true; $__currentLoopData = $submissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php ($applicant = $submission->userIdentity->user); ?><tr class="border-t border-border hover:bg-surface-soft"><td><strong class="block text-text-primary"><?php echo e($applicant->profile?->full_name ?? $applicant->name); ?></strong><span class="text-text-secondary"><?php echo e($applicant->email); ?></span></td><td class="font-bold uppercase"><?php echo e(str_replace('_', ' ', $submission->document_type)); ?></td><td class="text-text-secondary"><?php echo e($submission->submitted_at->translatedFormat('d M Y, H:i')); ?></td><td><?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['tone' => match($submission->status){'verified'=>'success','revision'=>'warning','rejected'=>'danger',default=>'info'}]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(match($submission->status){'verified'=>'success','revision'=>'warning','rejected'=>'danger',default=>'info'})]); ?><?php echo e(str($submission->status)->replace('_',' ')->title()); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?></td><td class="text-right"><a class="font-bold text-primary hover:text-accent" href="<?php echo e(route('admin.identity-verifications.show', $submission)); ?>">Tinjau</a></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="5"><?php if (isset($component)) { $__componentOriginal3607a477fdef7402bc742abad5df9c51 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3607a477fdef7402bc742abad5df9c51 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.empty-state','data' => ['icon' => 'shield-check','title' => 'Tidak ada pengajuan','description' => 'Tidak ada pengajuan identitas untuk filter ini.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'shield-check','title' => 'Tidak ada pengajuan','description' => 'Tidak ada pengajuan identitas untuk filter ini.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $attributes = $__attributesOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__attributesOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $component = $__componentOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__componentOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?></td></tr><?php endif; ?></tbody></table></div>
    </section>
    <?php echo e($submissions->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/admin/identity-verifications/index.blade.php ENDPATH**/ ?>