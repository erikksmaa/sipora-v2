<?php $__env->startSection('title', 'Tinjau Verifikasi Identitas · SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<?php ($applicant = $submission->userIdentity->user); ?>
<div class="space-y-6">
    <?php if (isset($component)) { $__componentOriginal91a231a9270579fa1ae9246bd51fb785 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91a231a9270579fa1ae9246bd51fb785 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.page-header','data' => ['eyebrow' => ''.e($applicant->profile?->full_name ?? $applicant->name).'','title' => 'Tinjau Identitas Pemuda','description' => 'Dokumen dan nomor identitas bersifat privat dan hanya tersedia untuk Admin terotorisasi.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => ''.e($applicant->profile?->full_name ?? $applicant->name).'','title' => 'Tinjau Identitas Pemuda','description' => 'Dokumen dan nomor identitas bersifat privat dan hanya tersedia untuk Admin terotorisasi.']); ?>
         <?php $__env->slot('actions', null, []); ?> <a class="btn-secondary" href="<?php echo e(route('admin.identity-verifications.index')); ?>">Kembali ke antrean</a><?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['tone' => match($submission->status){'verified'=>'success','revision'=>'warning','rejected'=>'danger',default=>'info'}]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(match($submission->status){'verified'=>'success','revision'=>'warning','rejected'=>'danger',default=>'info'})]); ?><?php echo e(str($submission->status)->title()); ?> <?php echo $__env->renderComponent(); ?>
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
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_380px]">
        <main class="space-y-6">
            <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><div class="flex items-center gap-4"><span class="grid size-12 place-items-center rounded-xl bg-primary-50 font-bold text-primary"><?php echo e(str($applicant->name)->substr(0,2)->upper()); ?></span><div><h2 class="text-lg font-bold text-text-primary"><?php echo e($applicant->profile?->full_name ?? $applicant->name); ?></h2><p class="text-sm text-text-secondary"><?php echo e($applicant->email); ?></p></div></div><dl class="mt-5 grid gap-4 border-t border-border pt-5 sm:grid-cols-2"><div><dt class="text-xs font-bold text-text-muted">JENIS DOKUMEN</dt><dd class="mt-1 font-bold uppercase"><?php echo e(str_replace('_',' ',$submission->document_type)); ?></dd></div><div><dt class="text-xs font-bold text-text-muted">DIAJUKAN</dt><dd class="mt-1"><?php echo e($submission->submitted_at->translatedFormat('d M Y, H:i')); ?></dd></div><div><dt class="text-xs font-bold text-text-muted">NOMOR DOKUMEN</dt><dd class="mt-1 break-all font-mono font-bold"><?php echo e($documentNumber); ?></dd></div><div><dt class="text-xs font-bold text-text-muted">DOMISILI PROFIL</dt><dd class="mt-1"><?php echo e($applicant->primaryDomicile?->administrativeArea?->name ?? '—'); ?></dd></div></dl> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal369f34cb52dbf93b481f0965b483488c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal369f34cb52dbf93b481f0965b483488c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.file-card','data' => ['title' => 'Dokumen identitas','description' => 'Dokumen '.str_replace('_',' ',$submission->document_type),'href' => route('admin.identity-verifications.document',$submission),'type' => 'Dokumen privat','icon' => 'shield-check']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.file-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Dokumen identitas','description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Dokumen '.str_replace('_',' ',$submission->document_type)),'href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.identity-verifications.document',$submission)),'type' => 'Dokumen privat','icon' => 'shield-check']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal369f34cb52dbf93b481f0965b483488c)): ?>
<?php $attributes = $__attributesOriginal369f34cb52dbf93b481f0965b483488c; ?>
<?php unset($__attributesOriginal369f34cb52dbf93b481f0965b483488c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal369f34cb52dbf93b481f0965b483488c)): ?>
<?php $component = $__componentOriginal369f34cb52dbf93b481f0965b483488c; ?>
<?php unset($__componentOriginal369f34cb52dbf93b481f0965b483488c); ?>
<?php endif; ?>
            <section class="sipora-card"><h2 class="text-lg font-bold text-text-primary">Pratinjau privat</h2><p class="mt-1 text-sm text-text-secondary">Konten dilayani melalui route Admin terotorisasi tanpa mengekspos path penyimpanan.</p><iframe title="Dokumen identitas pengajuan" class="mt-4 h-[520px] w-full rounded-xl border border-border bg-surface-soft" src="<?php echo e(route('admin.identity-verifications.document',$submission)); ?>"></iframe></section>
            <?php if($submission->review_notes || $previousReview?->review_notes): ?><?php if (isset($component)) { $__componentOriginal52d89e0fa152624786a863357dd18ad1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal52d89e0fa152624786a863357dd18ad1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.review-history','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.review-history'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><article><?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['tone' => $submission->status === 'rejected' ? 'danger' : 'warning']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($submission->status === 'rejected' ? 'danger' : 'warning')]); ?><?php echo e(str($submission->status)->title()); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?><p class="mt-2 whitespace-pre-line text-sm text-text-secondary"><?php echo e($submission->review_notes ?? $previousReview->review_notes); ?></p></article> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal52d89e0fa152624786a863357dd18ad1)): ?>
<?php $attributes = $__attributesOriginal52d89e0fa152624786a863357dd18ad1; ?>
<?php unset($__attributesOriginal52d89e0fa152624786a863357dd18ad1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal52d89e0fa152624786a863357dd18ad1)): ?>
<?php $component = $__componentOriginal52d89e0fa152624786a863357dd18ad1; ?>
<?php unset($__componentOriginal52d89e0fa152624786a863357dd18ad1); ?>
<?php endif; ?><?php endif; ?>
        </main>
        <aside class="h-fit space-y-4 xl:sticky xl:top-24">
            <?php if($submission->status === 'pending'): ?>
                <?php if (isset($component)) { $__componentOriginal797da58f16556f641c23b0e72d0d5590 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal797da58f16556f641c23b0e72d0d5590 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.decision-panel','data' => ['title' => 'Keputusan Admin','action' => route('admin.identity-verifications.review',$submission),'notesName' => 'review_notes','approveLabel' => 'Setujui identitas','approveValue' => 'verified']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.decision-panel'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Keputusan Admin','action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.identity-verifications.review',$submission)),'notes-name' => 'review_notes','approve-label' => 'Setujui identitas','approve-value' => 'verified']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal797da58f16556f641c23b0e72d0d5590)): ?>
<?php $attributes = $__attributesOriginal797da58f16556f641c23b0e72d0d5590; ?>
<?php unset($__attributesOriginal797da58f16556f641c23b0e72d0d5590); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal797da58f16556f641c23b0e72d0d5590)): ?>
<?php $component = $__componentOriginal797da58f16556f641c23b0e72d0d5590; ?>
<?php unset($__componentOriginal797da58f16556f641c23b0e72d0d5590); ?>
<?php endif; ?>
            <?php else: ?>
                <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><h2 class="text-lg font-bold text-text-primary">Keputusan tersimpan</h2><p class="mt-2 text-sm text-text-secondary">Ditinjau <?php echo e($submission->reviewed_at?->translatedFormat('d M Y, H:i')); ?> oleh <?php echo e($submission->reviewer?->name ?? 'Admin SIPORA'); ?>.</p> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
            <?php endif; ?>
        </aside>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/admin/identity-verifications/show.blade.php ENDPATH**/ ?>