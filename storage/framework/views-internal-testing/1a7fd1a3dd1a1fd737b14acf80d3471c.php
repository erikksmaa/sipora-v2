<?php $__env->startSection('title', $proposal->exists ? 'Edit Proposal Program' : 'Buat Proposal Program'); ?>
<?php $__env->startSection('manager-content'); ?>
<div class="mx-auto max-w-3xl space-y-6">
    <?php if (isset($component)) { $__componentOriginal8e0a96c106aaf9b655be7405167c4255 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8e0a96c106aaf9b655be7405167c4255 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.page-header','data' => ['eyebrow' => 'Program · Proposal','title' => $proposal->exists ? 'Edit draft Proposal v'.$proposal->version : 'Buat draft Proposal','description' => $program->title]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Program · Proposal','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($proposal->exists ? 'Edit draft Proposal v'.$proposal->version : 'Buat draft Proposal'),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($program->title)]); ?>
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
    <form method="POST" enctype="multipart/form-data" action="<?php echo e($proposal->exists ? route('manager.program-proposals.update', [$organization, $program, $proposal]) : route('manager.program-proposals.store', [$organization, $program])); ?>" class="space-y-6 rounded-2xl bg-white p-6 shadow-sm">
        <?php echo csrf_field(); ?> <?php if($proposal->exists): ?> <?php echo method_field('PATCH'); ?> <?php endif; ?>
        <div><label for="requested_budget" class="label">Anggaran yang diajukan (Rp)</label><input id="requested_budget" name="requested_budget" type="number" min="0" step="0.01" class="field" value="<?php echo e(old('requested_budget', $proposal->requested_budget)); ?>"><?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('requested_budget')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('requested_budget'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?></div>
        <div><label for="proposal_document" class="label">Dokumen Proposal</label><input id="proposal_document" name="proposal_document" type="file" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" class="field"><p class="mt-2 text-xs text-slate-500">PDF, DOC, atau DOCX. Maksimal 10 MB. Dokumen disimpan privat.</p><?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('proposal_document')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('proposal_document'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?><?php if($proposal->exists && $proposal->proposal_document_path): ?><a class="mt-3 inline-flex text-sm font-bold text-[#243378] hover:underline" href="<?php echo e(route('manager.program-proposals.document', [$organization, $program, $proposal])); ?>" target="_blank">Lihat dokumen saat ini</a><?php endif; ?></div>
        <div class="flex flex-wrap gap-3"><button class="btn-primary">Simpan draft</button><a class="btn-secondary" href="<?php echo e(route('manager.programs.show', [$organization, $program])); ?>">Kembali</a></div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.manager', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/manager/program-proposals/form.blade.php ENDPATH**/ ?>