<?php $__env->startSection('title','Logbook '.$logbook->log_date->format('Y-m-d')); ?>
<?php $__env->startSection('manager-content'); ?>
<div class="space-y-6"><?php if (isset($component)) { $__componentOriginal8e0a96c106aaf9b655be7405167c4255 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8e0a96c106aaf9b655be7405167c4255 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.page-header','data' => ['eyebrow' => $logbook->log_date->translatedFormat('d F Y'),'title' => 'Catatan pelaksanaan','description' => $logbook->creator->name.($logbook->activity?' · '.$logbook->activity->title:'')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($logbook->log_date->translatedFormat('d F Y')),'title' => 'Catatan pelaksanaan','description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($logbook->creator->name.($logbook->activity?' · '.$logbook->activity->title:''))]); ?><a class="btn-secondary" href="<?php echo e(route('manager.program-logbooks.index',[$organization,$program])); ?>">Semua Logbook</a><?php if (isset($component)) { $__componentOriginal7e936d243853d9416a8639649931bc46 = $component; } ?>
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
<?php endif; ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8e0a96c106aaf9b655be7405167c4255)): ?>
<?php $attributes = $__attributesOriginal8e0a96c106aaf9b655be7405167c4255; ?>
<?php unset($__attributesOriginal8e0a96c106aaf9b655be7405167c4255); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e0a96c106aaf9b655be7405167c4255)): ?>
<?php $component = $__componentOriginal8e0a96c106aaf9b655be7405167c4255; ?>
<?php unset($__componentOriginal8e0a96c106aaf9b655be7405167c4255); ?>
<?php endif; ?><section class="rounded-2xl bg-white p-6 shadow-sm"><div class="grid gap-5 lg:grid-cols-3"><div class="lg:col-span-2"><h2 class="font-extrabold text-[#14205c]">Ringkasan</h2><p class="mt-2 whitespace-pre-line text-slate-600"><?php echo e($logbook->summary); ?></p></div><div class="rounded-xl bg-[#eef0ff] p-5"><p class="text-xs font-bold text-slate-500">PROGRES DILAPORKAN</p><p class="mt-1 text-3xl font-black text-[#14205c]"><?php echo e($logbook->progress_percent); ?>%</p></div><div><h2 class="font-extrabold text-[#14205c]">Hambatan</h2><p class="mt-2 whitespace-pre-line text-slate-600"><?php echo e($logbook->obstacles?:'Tidak ada.'); ?></p></div><div><h2 class="font-extrabold text-[#14205c]">Solusi</h2><p class="mt-2 whitespace-pre-line text-slate-600"><?php echo e($logbook->solutions?:'Belum dicatat.'); ?></p></div></div>
<?php if($logbook->review_notes): ?><div class="mt-5 rounded-xl border border-orange-200 bg-orange-50 p-4"><strong class="text-orange-700">Catatan Verifier</strong><p class="mt-1 text-sm"><?php echo e($logbook->review_notes); ?></p></div><?php endif; ?>
<div class="mt-5 flex flex-wrap gap-3"><?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update',$logbook)): ?><a class="btn-secondary" href="<?php echo e(route('manager.program-logbooks.edit',[$organization,$program,$logbook])); ?>">Edit</a><form method="POST" action="<?php echo e(route('manager.program-logbooks.submit',[$organization,$program,$logbook])); ?>"><?php echo csrf_field(); ?><button class="btn-primary">Ajukan review</button></form><?php endif; ?></div></section>
<section class="rounded-2xl bg-white p-6 shadow-sm"><h2 class="text-xl font-black text-[#14205c]">Media pendukung</h2><div class="mt-4 grid gap-3 sm:grid-cols-2"><?php $__empty_1 = true; $__currentLoopData = $logbook->media; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><div class="rounded-xl border border-slate-200 p-4"><a class="font-bold text-[#243378] hover:underline" target="_blank" href="<?php echo e(route('manager.program-logbooks.media.show',[$organization,$program,$logbook,$media])); ?>">Buka media</a><p class="mt-1 text-sm text-slate-500"><?php echo e($media->caption?:'Tanpa keterangan'); ?></p><?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update',$logbook)): ?><form class="mt-3" method="POST" action="<?php echo e(route('manager.program-logbooks.media.destroy',[$organization,$program,$logbook,$media])); ?>"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="text-sm font-bold text-rose-600">Hapus</button></form><?php endif; ?></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p class="text-sm text-slate-500">Belum ada media.</p><?php endif; ?></div><?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update',$logbook)): ?><form class="mt-5 grid gap-3 sm:grid-cols-[1fr_1fr_auto]" enctype="multipart/form-data" method="POST" action="<?php echo e(route('manager.program-logbooks.media.store',[$organization,$program,$logbook])); ?>"><?php echo csrf_field(); ?><input class="field" type="file" name="file" accept=".jpg,.jpeg,.png,.webp,.pdf"><input class="field" name="caption" placeholder="Keterangan media"><button class="btn-primary">Unggah</button></form><?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('file')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('file'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?><?php endif; ?></section></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.manager', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/manager/program-logbooks/show.blade.php ENDPATH**/ ?>