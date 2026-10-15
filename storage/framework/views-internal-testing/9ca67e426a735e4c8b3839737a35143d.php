<section class="rounded-2xl bg-white p-6 shadow-sm">
    <div class="flex flex-wrap items-start justify-between gap-4"><div><p class="text-sm font-bold text-orange-600">PROPOSAL PROGRAM</p><h2 class="mt-1 text-xl font-black text-[#14205c]">Pengajuan resmi kepada Verifier</h2></div><?php if($program->latestProposal): ?><?php if (isset($component)) { $__componentOriginal803b8c70aa19f1d9ff8ad8f0f9a25a72 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal803b8c70aa19f1d9ff8ad8f0f9a25a72 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.program.proposal-status-badge','data' => ['status' => $program->latestProposal->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('program.proposal-status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($program->latestProposal->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal803b8c70aa19f1d9ff8ad8f0f9a25a72)): ?>
<?php $attributes = $__attributesOriginal803b8c70aa19f1d9ff8ad8f0f9a25a72; ?>
<?php unset($__attributesOriginal803b8c70aa19f1d9ff8ad8f0f9a25a72); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal803b8c70aa19f1d9ff8ad8f0f9a25a72)): ?>
<?php $component = $__componentOriginal803b8c70aa19f1d9ff8ad8f0f9a25a72; ?>
<?php unset($__componentOriginal803b8c70aa19f1d9ff8ad8f0f9a25a72); ?>
<?php endif; ?><?php endif; ?></div>
    <?php if(!$program->latestProposal): ?>
        <div class="mt-5 rounded-xl border border-dashed border-slate-300 p-6"><p class="font-bold text-[#14205c]">Belum ada Proposal</p><p class="mt-2 text-sm text-slate-500">Siapkan dokumen dan anggaran. Minimal satu Activity harus terhubung sebelum pengajuan.</p><?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $program)): ?><a class="btn-primary mt-4 inline-flex" href="<?php echo e(route('manager.program-proposals.create', [$organization, $program])); ?>">Buat Proposal</a><?php endif; ?></div>
    <?php else: ?>
        <?php ($latest = $program->latestProposal); ?>
        <dl class="mt-5 grid gap-4 rounded-xl bg-slate-50 p-5 sm:grid-cols-3"><div><dt class="text-xs font-bold uppercase text-slate-500">Versi</dt><dd class="mt-1 font-extrabold text-[#14205c]">v<?php echo e($latest->version); ?></dd></div><div><dt class="text-xs font-bold uppercase text-slate-500">Anggaran</dt><dd class="mt-1 font-extrabold text-[#14205c]"><?php echo e($latest->requested_budget !== null ? 'Rp '.number_format((float) $latest->requested_budget, 0, ',', '.') : 'Tidak dicantumkan'); ?></dd></div><div><dt class="text-xs font-bold uppercase text-slate-500">Diajukan</dt><dd class="mt-1 font-extrabold text-[#14205c]"><?php echo e($latest->submitted_at?->translatedFormat('d M Y, H:i') ?? 'Belum diajukan'); ?></dd></div></dl>
        <?php if($latest->review_notes): ?><div class="mt-4 rounded-xl border border-orange-200 bg-orange-50 p-4"><p class="text-xs font-bold uppercase text-orange-700">Catatan Verifier</p><p class="mt-1 text-sm text-slate-700"><?php echo e($latest->review_notes); ?></p></div><?php endif; ?>
        <div class="mt-5 flex flex-wrap gap-3">
            <?php if($latest->proposal_document_path): ?><a class="btn-secondary" target="_blank" href="<?php echo e(route('manager.program-proposals.document', [$organization, $program, $latest])); ?>">Lihat dokumen</a><?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $latest)): ?><a class="btn-secondary" href="<?php echo e(route('manager.program-proposals.edit', [$organization, $program, $latest])); ?>">Edit draft</a><form method="POST" action="<?php echo e(route('manager.program-proposals.submit', [$organization, $program, $latest])); ?>"><?php echo csrf_field(); ?><button class="btn-primary">Ajukan ke Verifier</button></form><?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('revise', $latest)): ?><form method="POST" action="<?php echo e(route('manager.program-proposals.revise', [$organization, $program, $latest])); ?>"><?php echo csrf_field(); ?><button class="btn-primary">Buat versi revisi</button></form><?php endif; ?>
        </div>
        <?php if($program->proposals->count() > 1): ?><div class="mt-6 border-t border-slate-200 pt-5"><h3 class="font-extrabold text-[#14205c]">Riwayat versi</h3><div class="mt-3 space-y-2"><?php $__currentLoopData = $program->proposals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proposal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="flex items-center justify-between rounded-lg border border-slate-200 px-4 py-3"><span class="text-sm font-bold text-slate-700">Versi <?php echo e($proposal->version); ?></span><?php if (isset($component)) { $__componentOriginal803b8c70aa19f1d9ff8ad8f0f9a25a72 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal803b8c70aa19f1d9ff8ad8f0f9a25a72 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.program.proposal-status-badge','data' => ['status' => $proposal->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('program.proposal-status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($proposal->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal803b8c70aa19f1d9ff8ad8f0f9a25a72)): ?>
<?php $attributes = $__attributesOriginal803b8c70aa19f1d9ff8ad8f0f9a25a72; ?>
<?php unset($__attributesOriginal803b8c70aa19f1d9ff8ad8f0f9a25a72); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal803b8c70aa19f1d9ff8ad8f0f9a25a72)): ?>
<?php $component = $__componentOriginal803b8c70aa19f1d9ff8ad8f0f9a25a72; ?>
<?php unset($__componentOriginal803b8c70aa19f1d9ff8ad8f0f9a25a72); ?>
<?php endif; ?></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div></div><?php endif; ?>
    <?php endif; ?>
</section>
<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/manager/programs/_proposal.blade.php ENDPATH**/ ?>