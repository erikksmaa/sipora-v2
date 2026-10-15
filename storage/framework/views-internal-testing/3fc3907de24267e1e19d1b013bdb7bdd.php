<?php $__env->startSection('title','Review E-LPJ'); ?>
<?php $__env->startSection('verifier-content'); ?>
<div class="space-y-6"><?php if (isset($component)) { $__componentOriginal91a231a9270579fa1ae9246bd51fb785 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91a231a9270579fa1ae9246bd51fb785 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.page-header','data' => ['eyebrow' => 'E-LPJ versi '.e($report->version).'','title' => $report->program->title,'description' => $report->program->organization->name.' · '.$report->program->creator->name]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'E-LPJ versi '.e($report->version).'','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($report->program->title),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($report->program->organization->name.' · '.$report->program->creator->name)]); ?> <?php $__env->slot('actions', null, []); ?> <a class="btn-secondary" href="<?php echo e(route('verifier.financial-reports.index')); ?>">Kembali</a><?php if (isset($component)) { $__componentOriginala4d8a01ad76ad1a0de2522bb3954ab8d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala4d8a01ad76ad1a0de2522bb3954ab8d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.program.financial-status-badge','data' => ['status' => $report->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('program.financial-status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($report->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala4d8a01ad76ad1a0de2522bb3954ab8d)): ?>
<?php $attributes = $__attributesOriginala4d8a01ad76ad1a0de2522bb3954ab8d; ?>
<?php unset($__attributesOriginala4d8a01ad76ad1a0de2522bb3954ab8d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala4d8a01ad76ad1a0de2522bb3954ab8d)): ?>
<?php $component = $__componentOriginala4d8a01ad76ad1a0de2522bb3954ab8d; ?>
<?php unset($__componentOriginala4d8a01ad76ad1a0de2522bb3954ab8d); ?>
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
<?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><h2 class="text-lg font-bold text-text-primary">Program & Proposal</h2><dl class="mt-4 grid gap-4 rounded-xl bg-surface-soft p-4 text-sm sm:grid-cols-2 lg:grid-cols-4"><div><dt class="text-text-muted">Kategori</dt><dd class="font-semibold"><?php echo e($report->program->category?->name ?? '—'); ?></dd></div><div><dt class="text-text-muted">Periode</dt><dd class="font-semibold"><?php echo e($report->program->start_date?->translatedFormat('d M Y') ?? '—'); ?> – <?php echo e($report->program->end_date?->translatedFormat('d M Y') ?? '—'); ?></dd></div><div><dt class="text-text-muted">Status Program</dt><dd class="font-semibold"><?php echo e(str($report->program->execution_status)->title()); ?></dd></div><div><dt class="text-text-muted">Proposal</dt><dd class="font-semibold">Versi <?php echo e($report->program->latestProposal?->version ?? '—'); ?> · <?php echo e($report->program->latestProposal?->status ?? '—'); ?></dd></div></dl><?php if($report->notes): ?><p class="mt-4 whitespace-pre-line text-sm text-text-secondary"><?php echo e($report->notes); ?></p><?php endif; ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<section class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4" aria-label="Ringkasan keuangan"><?php $__currentLoopData = [['Anggaran Proposal',$summary['proposal_budget'],'primary'],['Realisasi Pengeluaran',$summary['realization_total'],'accent'],['Total Pemasukan',$summary['income_total'],'success'],['Selisih',$summary['difference'],$summary['over_budget']?'accent':'info']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label,$value,$tone]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if (isset($component)) { $__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.metric-card','data' => ['title' => $label,'value' => $money->rupiah($value),'icon' => 'wallet','tone' => $tone,'class' => 'rupiah']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.metric-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($label),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($money->rupiah($value)),'icon' => 'wallet','tone' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tone),'class' => 'rupiah']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe)): ?>
<?php $attributes = $__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe; ?>
<?php unset($__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe)): ?>
<?php $component = $__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe; ?>
<?php unset($__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe); ?>
<?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></section>
<?php if($summary['over_budget']): ?><aside class="rounded-2xl border border-amber-200 bg-warning-soft p-4 text-sm text-text-secondary"><strong class="text-[#8A5A0A]">Perlu perhatian:</strong> realisasi pengeluaran melebihi anggaran Proposal.</aside><?php endif; ?>
<div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_380px]"><main class="space-y-6"><?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><h2 class="text-lg font-bold text-text-primary">Ringkasan pelaksanaan</h2><p class="mt-2 text-sm text-text-secondary"><?php echo e($report->program->activities->count()); ?> Activity terhubung · <?php echo e($report->program->logbooks->where('status','approved')->count()); ?> Logbook disetujui</p><div class="mt-4 divide-y divide-border"><?php $__empty_1 = true; $__currentLoopData = $report->program->logbooks->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $logbook): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><article class="py-3"><p class="text-xs font-bold text-text-muted"><?php echo e($logbook->log_date->translatedFormat('d M Y')); ?> · progres <?php echo e($logbook->progress_percent); ?>%</p><p class="mt-1 text-sm text-text-secondary"><?php echo e($logbook->summary); ?></p></article><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p class="text-sm text-text-secondary">Belum ada Logbook.</p><?php endif; ?></div> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<section class="table-shell"><div class="border-b border-border p-5"><h2 class="text-lg font-bold text-text-primary">Rincian transaksi</h2><p class="mt-1 text-sm text-text-secondary"><?php echo e($summary['item_count']); ?> transaksi · <?php echo e($summary['evidence_count']); ?> bukti</p></div><div class="overflow-x-auto"><table class="w-full min-w-[720px]"><thead><tr><th>Tanggal</th><th>Jenis</th><th>Deskripsi</th><th>Nominal</th><th>Bukti</th></tr></thead><tbody><?php $__empty_1 = true; $__currentLoopData = $report->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr class="border-t border-border"><td><?php echo e($item->transaction_date->translatedFormat('d M Y')); ?></td><td><?php echo e($item->transaction_type==='expense'?'Belanja':'Pemasukan'); ?></td><td class="font-semibold text-text-primary"><?php echo e($item->description); ?></td><td class="rupiah"><?php echo e($money->rupiah($item->amount)); ?></td><td><?php if($item->receipt_path): ?><?php if (isset($component)) { $__componentOriginalbc28015eac9404a329549563eb6c78a7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbc28015eac9404a329549563eb6c78a7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.media.receipt','data' => ['url' => route('verifier.financial-reports.items.receipt', [$report,$item]),'image' => in_array(strtolower(pathinfo($item->receipt_path, PATHINFO_EXTENSION)), ['jpg','jpeg','png','webp'])]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('media.receipt'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('verifier.financial-reports.items.receipt', [$report,$item])),'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(in_array(strtolower(pathinfo($item->receipt_path, PATHINFO_EXTENSION)), ['jpg','jpeg','png','webp']))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbc28015eac9404a329549563eb6c78a7)): ?>
<?php $attributes = $__attributesOriginalbc28015eac9404a329549563eb6c78a7; ?>
<?php unset($__attributesOriginalbc28015eac9404a329549563eb6c78a7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbc28015eac9404a329549563eb6c78a7)): ?>
<?php $component = $__componentOriginalbc28015eac9404a329549563eb6c78a7; ?>
<?php unset($__componentOriginalbc28015eac9404a329549563eb6c78a7); ?>
<?php endif; ?><?php else: ?><span class="text-text-muted">—</span><?php endif; ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="5"><?php if (isset($component)) { $__componentOriginal3607a477fdef7402bc742abad5df9c51 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3607a477fdef7402bc742abad5df9c51 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.empty-state','data' => ['icon' => 'wallet','title' => 'Tidak ada transaksi']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'wallet','title' => 'Tidak ada transaksi']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $attributes = $__attributesOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__attributesOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $component = $__componentOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__componentOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?></td></tr><?php endif; ?></tbody></table></div></section>
<?php if($report->review_notes): ?><?php if (isset($component)) { $__componentOriginal52d89e0fa152624786a863357dd18ad1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal52d89e0fa152624786a863357dd18ad1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.review-history','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.review-history'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><article><?php if (isset($component)) { $__componentOriginala4d8a01ad76ad1a0de2522bb3954ab8d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala4d8a01ad76ad1a0de2522bb3954ab8d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.program.financial-status-badge','data' => ['status' => $report->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('program.financial-status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($report->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala4d8a01ad76ad1a0de2522bb3954ab8d)): ?>
<?php $attributes = $__attributesOriginala4d8a01ad76ad1a0de2522bb3954ab8d; ?>
<?php unset($__attributesOriginala4d8a01ad76ad1a0de2522bb3954ab8d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala4d8a01ad76ad1a0de2522bb3954ab8d)): ?>
<?php $component = $__componentOriginala4d8a01ad76ad1a0de2522bb3954ab8d; ?>
<?php unset($__componentOriginala4d8a01ad76ad1a0de2522bb3954ab8d); ?>
<?php endif; ?><p class="mt-2 whitespace-pre-line text-sm text-text-secondary"><?php echo e($report->review_notes); ?></p></article> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal52d89e0fa152624786a863357dd18ad1)): ?>
<?php $attributes = $__attributesOriginal52d89e0fa152624786a863357dd18ad1; ?>
<?php unset($__attributesOriginal52d89e0fa152624786a863357dd18ad1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal52d89e0fa152624786a863357dd18ad1)): ?>
<?php $component = $__componentOriginal52d89e0fa152624786a863357dd18ad1; ?>
<?php unset($__componentOriginal52d89e0fa152624786a863357dd18ad1); ?>
<?php endif; ?><?php endif; ?></main>
<aside class="h-fit xl:sticky xl:top-24"><?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('review',$report)): ?><?php if (isset($component)) { $__componentOriginal797da58f16556f641c23b0e72d0d5590 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal797da58f16556f641c23b0e72d0d5590 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.decision-panel','data' => ['title' => 'Keputusan E-LPJ','action' => route('verifier.financial-reports.review',$report)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.decision-panel'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Keputusan E-LPJ','action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('verifier.financial-reports.review',$report))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal797da58f16556f641c23b0e72d0d5590)): ?>
<?php $attributes = $__attributesOriginal797da58f16556f641c23b0e72d0d5590; ?>
<?php unset($__attributesOriginal797da58f16556f641c23b0e72d0d5590); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal797da58f16556f641c23b0e72d0d5590)): ?>
<?php $component = $__componentOriginal797da58f16556f641c23b0e72d0d5590; ?>
<?php unset($__componentOriginal797da58f16556f641c23b0e72d0d5590); ?>
<?php endif; ?><?php else: ?><?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><h2 class="text-lg font-bold">Review selesai</h2><p class="mt-2 text-sm text-text-secondary">Status E-LPJ ditampilkan pada header.</p> <?php echo $__env->renderComponent(); ?>
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

<?php echo $__env->make('layouts.verifier', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/verifier/financial-reports/show.blade.php ENDPATH**/ ?>