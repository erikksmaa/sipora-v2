<?php $__env->startSection('title', 'E-LPJ '.$program->title); ?>

<?php $__env->startSection('manager-content'); ?>
    <div class="space-y-6">
        <?php if (isset($component)) { $__componentOriginal8e0a96c106aaf9b655be7405167c4255 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8e0a96c106aaf9b655be7405167c4255 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.page-header','data' => ['eyebrow' => 'E-LPJ versi '.e($report->version).'','title' => 'Laporan Keuangan','description' => $program->title]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'E-LPJ versi '.e($report->version).'','title' => 'Laporan Keuangan','description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($program->title)]); ?>
             <?php $__env->slot('actions', null, []); ?> 
                <a class="btn-secondary" href="<?php echo e(route('manager.programs.show', [$organization, $program])); ?>">Detail Program</a>
                <?php if (isset($component)) { $__componentOriginala4d8a01ad76ad1a0de2522bb3954ab8d = $component; } ?>
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
<?php endif; ?>
             <?php $__env->endSlot(); ?>
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

        <section class="rounded-2xl bg-white p-5 shadow-sm sm:p-6">
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <?php $__currentLoopData = [
                    ['label' => 'ANGGARAN PROPOSAL', 'value' => $summary['proposal_budget'], 'box' => 'bg-[#eef0ff]', 'text' => 'text-[#14205c]'],
                    ['label' => 'REALISASI BELANJA', 'value' => $summary['realization_total'], 'box' => 'bg-orange-50', 'text' => 'text-orange-700'],
                    ['label' => 'PENERIMAAN', 'value' => $summary['income_total'], 'box' => 'bg-emerald-50', 'text' => 'text-emerald-700'],
                    ['label' => 'SELISIH ANGGARAN', 'value' => $summary['difference'], 'box' => $summary['over_budget'] ? 'bg-rose-50' : 'bg-slate-50', 'text' => $summary['over_budget'] ? 'text-rose-700' : 'text-[#14205c]'],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metric): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="rounded-xl p-4 <?php echo e($metric['box']); ?>">
                        <p class="text-xs font-bold text-slate-500"><?php echo e($metric['label']); ?></p>
                        <p class="rupiah mt-1 text-xl font-black <?php echo e($metric['text']); ?>"><?php echo e($money->rupiah($metric['value'])); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <?php if($summary['over_budget']): ?>
                <p class="mt-3 rounded-xl border border-rose-200 bg-rose-50 p-3 text-sm font-semibold text-rose-700">Realisasi belanja melebihi anggaran proposal. Periksa kembali rincian sebelum mengajukan.</p>
            <?php endif; ?>

            <?php if($report->notes): ?>
                <div class="mt-5">
                    <h2 class="font-extrabold text-[#14205c]">Catatan umum</h2>
                    <p class="mt-2 whitespace-pre-line text-slate-600"><?php echo e($report->notes); ?></p>
                </div>
            <?php endif; ?>

            <?php if($report->review_notes): ?>
                <div class="mt-5 rounded-xl border border-orange-200 bg-orange-50 p-4">
                    <strong class="text-orange-700">Catatan Verifier</strong>
                    <p class="mt-1 whitespace-pre-line text-sm"><?php echo e($report->review_notes); ?></p>
                </div>
            <?php endif; ?>

            <div class="mt-5 flex flex-wrap gap-3">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $report)): ?>
                    <a class="btn-secondary" href="<?php echo e(route('manager.financial-reports.edit', [$organization, $program, $report])); ?>">Edit catatan</a>
                    <form method="POST" action="<?php echo e(route('manager.financial-reports.submit', [$organization, $program, $report])); ?>">
                        <?php echo csrf_field(); ?>
                        <button class="btn-primary">Ajukan E-LPJ</button>
                    </form>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('revise', $report)): ?>
                    <form method="POST" action="<?php echo e(route('manager.financial-reports.revise', [$organization, $program, $report])); ?>">
                        <?php echo csrf_field(); ?>
                        <button class="btn-primary">Buat versi revisi</button>
                    </form>
                <?php endif; ?>
            </div>
            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['class' => 'mt-3','messages' => $errors->get('report')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-3','messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('report'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
        </section>

        <section class="rounded-2xl bg-white p-5 shadow-sm sm:p-6">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <p class="text-sm font-bold text-orange-600">RINCIAN TRANSAKSI</p>
                    <h2 class="mt-1 text-xl font-black text-[#14205c]"><?php echo e($summary['item_count']); ?> item keuangan</h2>
                </div>
                <p class="text-sm text-slate-500"><?php echo e($summary['evidence_count']); ?> bukti terlampir</p>
            </div>

            <div class="mt-5 space-y-4">
                <?php $__empty_1 = true; $__currentLoopData = $report->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <article class="rounded-xl border border-slate-200 p-4">
                        <div class="flex flex-wrap justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-xs font-bold uppercase <?php echo e($item->transaction_type === 'expense' ? 'text-orange-600' : 'text-emerald-700'); ?>">
                                    <?php echo e($item->transaction_type === 'expense' ? 'Belanja' : 'Penerimaan'); ?> · <?php echo e($item->transaction_date->translatedFormat('d M Y')); ?>

                                </p>
                                <p class="mt-1 break-words font-bold text-[#14205c]"><?php echo e($item->description); ?></p>
                            </div>
                            <p class="rupiah font-black text-[#14205c]"><?php echo e($money->rupiah($item->amount)); ?></p>
                        </div>
                        <?php if($item->receipt_path): ?>
                            <?php if (isset($component)) { $__componentOriginalbc28015eac9404a329549563eb6c78a7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbc28015eac9404a329549563eb6c78a7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.media.receipt','data' => ['url' => route('manager.financial-reports.items.receipt', [$organization, $program, $report, $item]),'image' => in_array(strtolower(pathinfo($item->receipt_path, PATHINFO_EXTENSION)), ['jpg','jpeg','png','webp'])]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('media.receipt'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('manager.financial-reports.items.receipt', [$organization, $program, $report, $item])),'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(in_array(strtolower(pathinfo($item->receipt_path, PATHINFO_EXTENSION)), ['jpg','jpeg','png','webp']))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbc28015eac9404a329549563eb6c78a7)): ?>
<?php $attributes = $__attributesOriginalbc28015eac9404a329549563eb6c78a7; ?>
<?php unset($__attributesOriginalbc28015eac9404a329549563eb6c78a7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbc28015eac9404a329549563eb6c78a7)): ?>
<?php $component = $__componentOriginalbc28015eac9404a329549563eb6c78a7; ?>
<?php unset($__componentOriginalbc28015eac9404a329549563eb6c78a7); ?>
<?php endif; ?>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $report)): ?>
                            <details class="mt-4">
                                <summary class="cursor-pointer text-sm font-bold text-[#243378]">Edit item</summary>
                                <form class="mt-4 grid gap-3 md:grid-cols-2" enctype="multipart/form-data" method="POST" action="<?php echo e(route('manager.financial-reports.items.update', [$organization, $program, $report, $item])); ?>">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <select class="field" name="transaction_type">
                                        <option value="expense" <?php if($item->transaction_type === 'expense'): echo 'selected'; endif; ?>>Belanja</option>
                                        <option value="income" <?php if($item->transaction_type === 'income'): echo 'selected'; endif; ?>>Penerimaan</option>
                                    </select>
                                    <input class="field" type="date" name="transaction_date" value="<?php echo e($item->transaction_date->format('Y-m-d')); ?>">
                                    <input class="field md:col-span-2" name="description" value="<?php echo e($item->description); ?>">
                                    <input class="field" name="amount" inputmode="decimal" value="<?php echo e($item->amount); ?>">
                                    <input class="field" type="file" name="receipt" accept=".jpg,.jpeg,.png,.webp,.pdf">
                                    <button class="btn-secondary">Simpan item</button>
                                </form>
                                <form class="mt-2" method="POST" action="<?php echo e(route('manager.financial-reports.items.destroy', [$organization, $program, $report, $item])); ?>">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button class="text-sm font-bold text-rose-600">Hapus item</button>
                                </form>
                            </details>
                        <?php endif; ?>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="rounded-xl border border-dashed border-slate-300 p-8 text-center text-sm text-slate-500">Belum ada transaksi.</p>
                <?php endif; ?>
            </div>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $report)): ?>
                <form class="mt-6 grid gap-3 md:grid-cols-2" enctype="multipart/form-data" method="POST" action="<?php echo e(route('manager.financial-reports.items.store', [$organization, $program, $report])); ?>">
                    <?php echo csrf_field(); ?>
                    <select class="field" name="transaction_type"><option value="expense">Belanja</option><option value="income">Penerimaan</option></select>
                    <input class="field" type="date" name="transaction_date" value="<?php echo e(old('transaction_date', now()->toDateString())); ?>">
                    <input class="field md:col-span-2" name="description" placeholder="Uraian transaksi" value="<?php echo e(old('description')); ?>">
                    <input class="field" name="amount" inputmode="decimal" placeholder="Nominal, contoh 150000.00" value="<?php echo e(old('amount')); ?>">
                    <input class="field" type="file" name="receipt" accept=".jpg,.jpeg,.png,.webp,.pdf">
                    <div class="md:col-span-2"><button class="btn-primary">Tambah item</button></div>
                </form>
                <?php $__currentLoopData = ['transaction_type', 'transaction_date', 'description', 'amount', 'receipt']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get($field)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get($field))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </section>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.manager', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/manager/financial-reports/show.blade.php ENDPATH**/ ?>