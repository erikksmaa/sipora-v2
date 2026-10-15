<?php $__env->startSection('title','Evaluasi '.$program->title); ?>
<?php $__env->startSection('verifier-content'); ?>
<div class="space-y-6"><?php if (isset($component)) { $__componentOriginal91a231a9270579fa1ae9246bd51fb785 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91a231a9270579fa1ae9246bd51fb785 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.page-header','data' => ['eyebrow' => 'Evaluasi akhir Program','title' => $program->title,'description' => $program->organization->name.' · '.$program->category?->name]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Evaluasi akhir Program','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($program->title),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($program->organization->name.' · '.$program->category?->name)]); ?> <?php $__env->slot('actions', null, []); ?> <a class="btn-secondary" href="<?php echo e(route('verifier.program-evaluations.index')); ?>">Kembali</a><?php if (isset($component)) { $__componentOriginal08ab5d8782f78718f24293961a718c3b = $component; } ?>
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
<?php if (isset($component)) { $__componentOriginal535b7a988c73133d0c15a2dfec19c0ac = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal535b7a988c73133d0c15a2dfec19c0ac = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.eligibility-panel','data' => ['eligible' => $assessment['eligible'],'reasons' => $assessment['reasons']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.eligibility-panel'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eligible' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($assessment['eligible']),'reasons' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($assessment['reasons'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal535b7a988c73133d0c15a2dfec19c0ac)): ?>
<?php $attributes = $__attributesOriginal535b7a988c73133d0c15a2dfec19c0ac; ?>
<?php unset($__attributesOriginal535b7a988c73133d0c15a2dfec19c0ac); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal535b7a988c73133d0c15a2dfec19c0ac)): ?>
<?php $component = $__componentOriginal535b7a988c73133d0c15a2dfec19c0ac; ?>
<?php unset($__componentOriginal535b7a988c73133d0c15a2dfec19c0ac); ?>
<?php endif; ?>
<section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4"><?php if (isset($component)) { $__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.metric-card','data' => ['title' => 'Proposal terbaru','value' => 'v'.($assessment['latest_proposal']?->version ?? '—'),'icon' => 'file-text']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.metric-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Proposal terbaru','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('v'.($assessment['latest_proposal']?->version ?? '—')),'icon' => 'file-text']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe)): ?>
<?php $attributes = $__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe; ?>
<?php unset($__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe)): ?>
<?php $component = $__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe; ?>
<?php unset($__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe); ?>
<?php endif; ?><?php if (isset($component)) { $__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.metric-card','data' => ['title' => 'Activity selesai','value' => $metrics['activity_completed'].'/'.$metrics['activity_total'],'icon' => 'clipboard-check','tone' => 'info']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.metric-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Activity selesai','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($metrics['activity_completed'].'/'.$metrics['activity_total']),'icon' => 'clipboard-check','tone' => 'info']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe)): ?>
<?php $attributes = $__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe; ?>
<?php unset($__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe)): ?>
<?php $component = $__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe; ?>
<?php unset($__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe); ?>
<?php endif; ?><?php if (isset($component)) { $__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.metric-card','data' => ['title' => 'Logbook disetujui','value' => $program->logbooks->where('status','approved')->count(),'icon' => 'notebook','tone' => 'success']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.metric-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Logbook disetujui','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($program->logbooks->where('status','approved')->count()),'icon' => 'notebook','tone' => 'success']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe)): ?>
<?php $attributes = $__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe; ?>
<?php unset($__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe)): ?>
<?php $component = $__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe; ?>
<?php unset($__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe); ?>
<?php endif; ?><?php if (isset($component)) { $__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.metric-card','data' => ['title' => 'Progres terbaru','value' => ($metrics['latest_reported_progress'] ?? '—').'%' ,'icon' => 'activity','tone' => 'accent']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.metric-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Progres terbaru','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(($metrics['latest_reported_progress'] ?? '—').'%' ),'icon' => 'activity','tone' => 'accent']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe)): ?>
<?php $attributes = $__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe; ?>
<?php unset($__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe)): ?>
<?php $component = $__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe; ?>
<?php unset($__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe); ?>
<?php endif; ?></section>
<div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_380px]"><main class="space-y-6"><?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><h2 class="text-lg font-bold text-text-primary">Bukti pelaksanaan</h2><div class="mt-4 divide-y divide-border"><?php $__empty_1 = true; $__currentLoopData = $program->activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><article class="flex flex-wrap items-center justify-between gap-2 py-3"><div><p class="font-semibold text-text-primary"><?php echo e($activity->title); ?></p><p class="text-xs text-text-secondary"><?php echo e($activity->category?->name); ?></p></div><?php if (isset($component)) { $__componentOriginal846b770b82a03494b6267b1ec4369c07 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal846b770b82a03494b6267b1ec4369c07 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.activity.status-badge','data' => ['status' => $activity->execution_status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('activity.status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activity->execution_status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal846b770b82a03494b6267b1ec4369c07)): ?>
<?php $attributes = $__attributesOriginal846b770b82a03494b6267b1ec4369c07; ?>
<?php unset($__attributesOriginal846b770b82a03494b6267b1ec4369c07); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal846b770b82a03494b6267b1ec4369c07)): ?>
<?php $component = $__componentOriginal846b770b82a03494b6267b1ec4369c07; ?>
<?php unset($__componentOriginal846b770b82a03494b6267b1ec4369c07); ?>
<?php endif; ?></article><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p class="text-sm text-text-secondary">Tidak ada Activity terhubung.</p><?php endif; ?></div> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><h2 class="text-lg font-bold text-text-primary">Ringkasan E-LPJ</h2><?php if($financial): ?><dl class="mt-4 space-y-3 text-sm"><div class="flex justify-between gap-4"><dt>Anggaran</dt><dd class="rupiah font-bold"><?php echo e($money->rupiah($financial['proposal_budget'])); ?></dd></div><div class="flex justify-between gap-4"><dt>Realisasi</dt><dd class="rupiah font-bold"><?php echo e($money->rupiah($financial['realization_total'])); ?></dd></div><div class="flex justify-between gap-4"><dt>Selisih</dt><dd class="rupiah font-bold"><?php echo e($money->rupiah($financial['difference'])); ?></dd></div></dl><?php else: ?><p class="mt-3 text-sm text-text-secondary">Belum ada E-LPJ.</p><?php endif; ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($component)) { $__componentOriginal52d89e0fa152624786a863357dd18ad1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal52d89e0fa152624786a863357dd18ad1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.review-history','data' => ['title' => 'Riwayat evaluasi']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.review-history'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Riwayat evaluasi']); ?><?php $__empty_1 = true; $__currentLoopData = $program->evaluations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evaluation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><article class="relative pb-5 last:pb-0"><span class="absolute -left-[27px] top-1 size-3 rounded-full bg-primary ring-4 ring-white"></span><div class="flex flex-wrap justify-between gap-2"><?php if (isset($component)) { $__componentOriginala6bce03fe02cebbc32a28be3596d5a2b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala6bce03fe02cebbc32a28be3596d5a2b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.program.evaluation-decision-badge','data' => ['decision' => $evaluation->decision]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('program.evaluation-decision-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['decision' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($evaluation->decision)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala6bce03fe02cebbc32a28be3596d5a2b)): ?>
<?php $attributes = $__attributesOriginala6bce03fe02cebbc32a28be3596d5a2b; ?>
<?php unset($__attributesOriginala6bce03fe02cebbc32a28be3596d5a2b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala6bce03fe02cebbc32a28be3596d5a2b)): ?>
<?php $component = $__componentOriginala6bce03fe02cebbc32a28be3596d5a2b; ?>
<?php unset($__componentOriginala6bce03fe02cebbc32a28be3596d5a2b); ?>
<?php endif; ?><span class="text-xs text-text-muted"><?php echo e($evaluation->evaluated_at->translatedFormat('d M Y H:i')); ?> · <?php echo e($evaluation->verifier->name); ?></span></div><p class="mt-2 text-sm text-text-secondary"><?php echo e($evaluation->evaluation_notes ?: 'Tidak ada catatan tambahan.'); ?></p></article><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p class="text-sm text-text-secondary">Belum ada evaluasi.</p><?php endif; ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal52d89e0fa152624786a863357dd18ad1)): ?>
<?php $attributes = $__attributesOriginal52d89e0fa152624786a863357dd18ad1; ?>
<?php unset($__attributesOriginal52d89e0fa152624786a863357dd18ad1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal52d89e0fa152624786a863357dd18ad1)): ?>
<?php $component = $__componentOriginal52d89e0fa152624786a863357dd18ad1; ?>
<?php unset($__componentOriginal52d89e0fa152624786a863357dd18ad1); ?>
<?php endif; ?></main>
<aside class="h-fit xl:sticky xl:top-24"><?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create',[App\Models\ProgramEvaluation::class,$program])): ?><section class="sipora-card"><h2 class="text-xl font-bold text-text-primary">Keputusan final</h2><p class="mt-1 text-sm text-text-secondary">Keputusan menggunakan seluruh bukti Program yang tersedia.</p><form class="mt-5 space-y-4" method="POST" action="<?php echo e(route('verifier.program-evaluations.store',$program)); ?>"><?php echo csrf_field(); ?><input type="hidden" name="expected_latest_evaluation_id" value="<?php echo e($program->latestEvaluation?->uuid()); ?>"><label><span class="label">Keputusan</span><select class="field" name="decision"><option value="approved">Setujui dan selesaikan Program</option><option value="revision">Minta tindak lanjut</option><option value="rejected">Tolak evaluasi akhir</option></select></label><label><span class="label">Catatan evaluasi</span><textarea class="field min-h-32" name="evaluation_notes" placeholder="Wajib untuk tindak lanjut atau penolakan"><?php echo e(old('evaluation_notes')); ?></textarea></label><?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('evaluation_notes')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('evaluation_notes'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?><?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('evaluation')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('evaluation'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?><button class="btn-primary">Finalkan evaluasi</button></form></section><?php else: ?><?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><h2 class="text-lg font-bold">Evaluasi tersimpan</h2><p class="mt-2 text-sm text-text-secondary">Riwayat keputusan tersedia pada panel utama.</p> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?><?php endif; ?></aside></div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.verifier', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/verifier/program-evaluations/show.blade.php ENDPATH**/ ?>