<section class="rounded-2xl bg-white p-6 shadow-sm"><div class="flex flex-wrap items-end justify-between gap-3"><div><p class="text-sm font-bold text-orange-600">E-LPJ</p><h2 class="mt-1 text-xl font-black text-[#14205c]">Laporan keuangan Program</h2></div><?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create',[App\Models\FinancialReport::class,$program])): ?><a class="btn-primary" href="<?php echo e(route('manager.financial-reports.create',[$organization,$program])); ?>">Mulai E-LPJ</a><?php endif; ?></div><div class="mt-5 space-y-3"><?php $__empty_1 = true; $__currentLoopData = $program->financialReports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $financialReport): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><a class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-slate-200 p-4 hover:border-indigo-300" href="<?php echo e(route('manager.financial-reports.show',[$organization,$program,$financialReport])); ?>"><div><p class="font-extrabold text-[#14205c]">E-LPJ versi <?php echo e($financialReport->version); ?></p><p class="mt-1 text-sm text-slate-500"><?php echo e($financialReport->items->count()); ?> transaksi · <?php echo e($financialReport->submitted_at?->translatedFormat('d M Y H:i') ?? 'Belum diajukan'); ?></p></div><?php if (isset($component)) { $__componentOriginala4d8a01ad76ad1a0de2522bb3954ab8d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala4d8a01ad76ad1a0de2522bb3954ab8d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.program.financial-status-badge','data' => ['status' => $financialReport->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('program.financial-status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($financialReport->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala4d8a01ad76ad1a0de2522bb3954ab8d)): ?>
<?php $attributes = $__attributesOriginala4d8a01ad76ad1a0de2522bb3954ab8d; ?>
<?php unset($__attributesOriginala4d8a01ad76ad1a0de2522bb3954ab8d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala4d8a01ad76ad1a0de2522bb3954ab8d)): ?>
<?php $component = $__componentOriginala4d8a01ad76ad1a0de2522bb3954ab8d; ?>
<?php unset($__componentOriginala4d8a01ad76ad1a0de2522bb3954ab8d); ?>
<?php endif; ?></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p class="rounded-xl border border-dashed border-slate-300 p-6 text-center text-sm text-slate-500">Belum ada E-LPJ. E-LPJ dapat dibuat saat Program berjalan, proposal disetujui, dan minimal satu Logbook disetujui.</p><?php endif; ?></div></section>
<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/manager/programs/_financial.blade.php ENDPATH**/ ?>