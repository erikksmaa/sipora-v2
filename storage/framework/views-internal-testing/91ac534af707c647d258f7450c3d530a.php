
<?php $__env->startSection('title', $program->title); ?>
<?php $__env->startSection('manager-content'); ?>
<div class="space-y-6">
    <div class="sipora-card flex flex-wrap gap-3"><a class="btn-outline" href="<?php echo e(route('manager.programs.applications.index',[$organization,$program])); ?>">Pendaftar & Peserta Program</a><?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('configureIntake',$program)): ?><a class="btn-accent" href="<?php echo e(route('manager.programs.intake.edit',[$organization,$program])); ?>">Pengaturan Pendaftaran</a><?php endif; ?></div>
    <?php if (isset($component)) { $__componentOriginal8e0a96c106aaf9b655be7405167c4255 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8e0a96c106aaf9b655be7405167c4255 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.page-header','data' => ['eyebrow' => 'Program Community','title' => $program->title,'description' => $organization->name]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Program Community','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($program->title),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($organization->name)]); ?>
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
    <?php if (isset($component)) { $__componentOriginal6936dc520652c52aa45e41e68a9b74a8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6936dc520652c52aa45e41e68a9b74a8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.media.representative','data' => ['domain' => 'programs','identity' => $program->slug]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('media.representative'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['domain' => 'programs','identity' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($program->slug)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6936dc520652c52aa45e41e68a9b74a8)): ?>
<?php $attributes = $__attributesOriginal6936dc520652c52aa45e41e68a9b74a8; ?>
<?php unset($__attributesOriginal6936dc520652c52aa45e41e68a9b74a8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6936dc520652c52aa45e41e68a9b74a8)): ?>
<?php $component = $__componentOriginal6936dc520652c52aa45e41e68a9b74a8; ?>
<?php unset($__componentOriginal6936dc520652c52aa45e41e68a9b74a8); ?>
<?php endif; ?><nav class="flex gap-2 overflow-x-auto rounded-xl border border-indigo-100 bg-white p-2 text-sm font-semibold" aria-label="Bagian Program"><a class="btn-ghost shrink-0" href="#proposal">Proposal</a><a class="btn-ghost shrink-0" href="#activities">Activity</a><a class="btn-ghost shrink-0" href="#logbook">Logbook</a><a class="btn-ghost shrink-0" href="#financial">E-LPJ</a><a class="btn-ghost shrink-0" href="#evaluation">Evaluasi</a></nav>
    <ol class="grid gap-2 sm:grid-cols-5" aria-label="Tahapan Program"><li class="rounded-xl bg-indigo-50 p-3 text-xs font-semibold text-primary">1 · Proposal<br><span class="font-normal"><?php echo e(str($program->latestProposal?->status ?? 'Belum ada')->replace('_', ' ')->title()); ?></span></li><li class="rounded-xl bg-indigo-50 p-3 text-xs font-semibold text-primary">2 · Eksekusi<br><span class="font-normal"><?php echo e(str($program->execution_status)->title()); ?></span></li><li class="rounded-xl bg-indigo-50 p-3 text-xs font-semibold text-primary">3 · Logbook<br><span class="font-normal"><?php echo e($program->logbooks->count()); ?> catatan</span></li><li class="rounded-xl bg-indigo-50 p-3 text-xs font-semibold text-primary">4 · E-LPJ<br><span class="font-normal"><?php echo e(str($program->latestFinancialReport?->status ?? 'Belum ada')->replace('_', ' ')->title()); ?></span></li><li class="rounded-xl bg-indigo-50 p-3 text-xs font-semibold text-primary">5 · Evaluasi<br><span class="font-normal"><?php echo e($program->latestEvaluation ? str($program->latestEvaluation->decision)->replace('_', ' ')->title() : 'Belum ada'); ?></span></li></ol>
    <section class="overflow-hidden rounded-2xl bg-white shadow-sm"><div class="border-b border-indigo-100 bg-gradient-to-r from-[#eef0ff] to-white p-6"><div class="flex flex-wrap items-start justify-between gap-4"><div><p class="text-sm font-bold text-orange-600"><?php echo e($program->category?->name); ?></p><h2 class="mt-1 text-xl font-bold text-[#14205c]">Ringkasan Program</h2><p class="mt-2 text-sm font-semibold text-slate-600"><?php echo e($organization->name); ?></p></div><?php if (isset($component)) { $__componentOriginal08ab5d8782f78718f24293961a718c3b = $component; } ?>
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
<?php endif; ?></div></div>
        <div class="p-6"><dl class="grid gap-4 rounded-xl bg-slate-50 p-5 sm:grid-cols-3"><div><dt class="text-xs font-bold uppercase text-slate-500">Periode</dt><dd class="mt-1 font-semibold text-[#14205c]"><?php echo e($program->start_date?->translatedFormat('d M Y') ?? 'Belum ditentukan'); ?><?php if($program->end_date): ?> – <?php echo e($program->end_date->translatedFormat('d M Y')); ?><?php endif; ?></dd></div><div><dt class="text-xs font-bold uppercase text-slate-500">Activity terhubung</dt><dd class="mt-1 font-semibold text-[#14205c]"><?php echo e($program->activities->count()); ?></dd></div><div><dt class="text-xs font-bold uppercase text-slate-500">Dibuat oleh</dt><dd class="mt-1 font-semibold text-[#14205c]"><?php echo e($program->creator->name); ?></dd></div></dl>
            <div class="mt-6 grid gap-6 lg:grid-cols-2"><div><h2 class="font-extrabold text-[#14205c]">Deskripsi Program</h2><p class="mt-3 whitespace-pre-line leading-7 text-slate-600"><?php echo e($program->description ?: 'Belum ada deskripsi.'); ?></p></div><div><h2 class="font-extrabold text-[#14205c]">Maksud & Tujuan</h2><p class="mt-3 whitespace-pre-line leading-7 text-slate-600"><?php echo e($program->objectives ?: 'Belum ada tujuan yang dicatat.'); ?></p></div></div>
            <div class="mt-6 flex flex-wrap gap-3"><?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $program)): ?><a class="btn-secondary" href="<?php echo e(route('manager.programs.edit', [$organization, $program])); ?>">Edit rencana</a><?php endif; ?> <?php if($program->execution_status!=='completed'): ?><a class="btn-primary" href="<?php echo e(route('manager.activities.create', ['organization' => $organization, 'program_id' => $program->uuid()])); ?>">+ Tambah Activity</a><?php endif; ?></div>
        </div>
    </section>
    <section id="activities" class="scroll-mt-28 rounded-2xl bg-white p-6 shadow-sm"><div class="flex flex-wrap items-end justify-between gap-3"><div><p class="text-sm font-bold text-orange-600">ACTIVITY TERKAIT</p><h2 class="mt-1 text-xl font-black text-[#14205c]">Rangkaian kegiatan resmi</h2></div><a class="text-sm font-bold text-[#243378] hover:underline" href="<?php echo e(route('manager.activities.index', $organization)); ?>">Lihat semua Activity</a></div>
        <div class="mt-5 space-y-3"><?php $__empty_1 = true; $__currentLoopData = $program->activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><a href="<?php echo e(route('manager.activities.show', [$organization, $activity])); ?>" class="grid gap-3 rounded-xl border border-slate-200 p-4 transition hover:border-indigo-300 hover:bg-indigo-50/30 sm:grid-cols-[1fr_auto] sm:items-center"><div><h3 class="font-extrabold text-[#14205c]"><?php echo e($activity->title); ?></h3><p class="mt-1 text-sm text-slate-500"><?php echo e($activity->category?->name); ?> · <?php echo e($activity->start_at->translatedFormat('d M Y, H:i')); ?></p></div><div class="flex flex-wrap gap-2"><?php if (isset($component)) { $__componentOriginal846b770b82a03494b6267b1ec4369c07 = $component; } ?>
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
<?php endif; ?><?php if (isset($component)) { $__componentOriginal846b770b82a03494b6267b1ec4369c07 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal846b770b82a03494b6267b1ec4369c07 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.activity.status-badge','data' => ['status' => $activity->publication_status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('activity.status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activity->publication_status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal846b770b82a03494b6267b1ec4369c07)): ?>
<?php $attributes = $__attributesOriginal846b770b82a03494b6267b1ec4369c07; ?>
<?php unset($__attributesOriginal846b770b82a03494b6267b1ec4369c07); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal846b770b82a03494b6267b1ec4369c07)): ?>
<?php $component = $__componentOriginal846b770b82a03494b6267b1ec4369c07; ?>
<?php unset($__componentOriginal846b770b82a03494b6267b1ec4369c07); ?>
<?php endif; ?></div></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><div class="rounded-xl border border-dashed border-slate-300 p-8 text-center"><p class="font-bold text-[#14205c]">Belum ada Activity</p><p class="mt-2 text-sm text-slate-500">Tambahkan minimal satu Activity sebelum Program dapat diajukan pada fase Proposal.</p></div><?php endif; ?></div>
    </section>
    <div id="proposal" class="scroll-mt-28"><?php echo $__env->make('manager.programs._proposal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
    <div id="logbook" class="scroll-mt-28"><?php echo $__env->make('manager.programs._monitoring', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
    <div id="financial" class="scroll-mt-28"><?php echo $__env->make('manager.programs._financial', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
    <div id="evaluation" class="scroll-mt-28"><?php echo $__env->make('manager.programs._evaluation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.manager', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/manager/programs/show.blade.php ENDPATH**/ ?>