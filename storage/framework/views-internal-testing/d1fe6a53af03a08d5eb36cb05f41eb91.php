<section class="rounded-2xl bg-white p-6 shadow-sm">
 <div class="flex flex-wrap items-start justify-between gap-4"><div><p class="text-sm font-bold text-orange-600">MONITORING PROGRAM</p><h2 class="mt-1 text-xl font-black text-[#14205c]">Logbook pelaksanaan</h2></div><div class="flex gap-2"><?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('startExecution',$program)): ?><form method="POST" action="<?php echo e(route('manager.programs.start-execution',[$organization,$program])); ?>"><?php echo csrf_field(); ?><button class="btn-primary">Mulai eksekusi</button></form><?php endif; ?> <?php if($program->execution_status==='running'): ?><a class="btn-primary" href="<?php echo e(route('manager.program-logbooks.create',[$organization,$program])); ?>">+ Tambah Logbook</a><?php endif; ?></div></div>
 <dl class="mt-5 grid gap-3 sm:grid-cols-4"><div class="rounded-xl bg-[#eef0ff] p-4"><dt class="text-xs font-bold text-slate-500">ACTIVITY</dt><dd class="mt-1 text-xl font-black text-[#14205c]"><?php echo e($metrics['activity_completed']); ?>/<?php echo e($metrics['activity_total']); ?></dd></div><div class="rounded-xl bg-[#eef0ff] p-4"><dt class="text-xs font-bold text-slate-500">LOGBOOK</dt><dd class="mt-1 text-xl font-black text-[#14205c]"><?php echo e($metrics['logbook_total']); ?></dd></div><div class="rounded-xl bg-[#eef0ff] p-4"><dt class="text-xs font-bold text-slate-500">LAPORAN TERAKHIR</dt><dd class="mt-1 font-black text-[#14205c]"><?php echo e($metrics['latest_log_date']?->translatedFormat('d M Y') ?? '—'); ?></dd></div><div class="rounded-xl bg-[#eef0ff] p-4"><dt class="text-xs font-bold text-slate-500">PROGRES DILAPORKAN</dt><dd class="mt-1 text-xl font-black text-[#14205c]"><?php echo e($metrics['latest_reported_progress'] !== null ? $metrics['latest_reported_progress'].'%' : '—'); ?></dd></div></dl>
 <div class="mt-5 space-y-3"><?php $__empty_1 = true; $__currentLoopData = $program->logbooks->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $logbook): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><a class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 p-4 hover:border-indigo-300" href="<?php echo e(route('manager.program-logbooks.show',[$organization,$program,$logbook])); ?>"><div><p class="font-extrabold text-[#14205c]"><?php echo e($logbook->log_date->translatedFormat('d M Y')); ?> · <?php echo e(str($logbook->summary)->limit(80)); ?></p><p class="mt-1 text-sm text-slate-500"><?php echo e($logbook->creator->name); ?> · <?php echo e($logbook->media->count()); ?> media</p></div><?php if (isset($component)) { $__componentOriginal7e936d243853d9416a8639649931bc46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7e936d243853d9416a8639649931bc46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.program.logbook-status-badge','data' => ['status' => $logbook->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('program.logbook-status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($logbook->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7e936d243853d9416a8639649931bc46)): ?>
<?php $attributes = $__attributesOriginal7e936d243853d9416a8639649931bc46; ?>
<?php unset($__attributesOriginal7e936d243853d9416a8639649931bc46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7e936d243853d9416a8639649931bc46)): ?>
<?php $component = $__componentOriginal7e936d243853d9416a8639649931bc46; ?>
<?php unset($__componentOriginal7e936d243853d9416a8639649931bc46); ?>
<?php endif; ?></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p class="rounded-xl border border-dashed border-slate-300 p-6 text-center text-sm text-slate-500">Belum ada catatan pelaksanaan.</p><?php endif; ?></div>
 <?php if($metrics['logbook_total']): ?><a class="mt-4 inline-flex text-sm font-bold text-[#243378] hover:underline" href="<?php echo e(route('manager.program-logbooks.index',[$organization,$program])); ?>">Lihat semua Logbook →</a><?php endif; ?>
</section>
<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/manager/programs/_monitoring.blade.php ENDPATH**/ ?>