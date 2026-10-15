<?php $__env->startSection('title', $entry->activity->title.' · Activity Passport'); ?>
<?php $__env->startSection('content'); ?>
<div class="mx-auto max-w-4xl space-y-6"><a class="text-sm font-bold text-[#243378]" href="<?php echo e(route('youth.passport.index')); ?>">← Activity Passport</a><section class="overflow-hidden rounded-3xl bg-[#243378] p-6 text-white shadow-lg sm:p-8"><div class="flex flex-wrap items-start justify-between gap-4"><div><p class="text-sm font-bold uppercase tracking-wider text-orange-300">Pengalaman SIPORA terverifikasi</p><h1 class="mt-2 text-3xl font-black"><?php echo e($entry->activity->title); ?></h1><p class="mt-2 text-indigo-100"><?php echo e($entry->activity->organization->name); ?></p></div><?php if (isset($component)) { $__componentOriginal4cc5583ac6f3cb736f99770e4859859b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4cc5583ac6f3cb736f99770e4859859b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.activity.completion-badge','data' => ['status' => $entry->completion_status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('activity.completion-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($entry->completion_status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4cc5583ac6f3cb736f99770e4859859b)): ?>
<?php $attributes = $__attributesOriginal4cc5583ac6f3cb736f99770e4859859b; ?>
<?php unset($__attributesOriginal4cc5583ac6f3cb736f99770e4859859b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4cc5583ac6f3cb736f99770e4859859b)): ?>
<?php $component = $__componentOriginal4cc5583ac6f3cb736f99770e4859859b; ?>
<?php unset($__componentOriginal4cc5583ac6f3cb736f99770e4859859b); ?>
<?php endif; ?></div></section>
<?php if (isset($component)) { $__componentOriginalc2a653d0f44ebc115de2e0e2b2511155 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2a653d0f44ebc115de2e0e2b2511155 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.media.activity','data' => ['activity' => $entry->activity]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('media.activity'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['activity' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($entry->activity)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2a653d0f44ebc115de2e0e2b2511155)): ?>
<?php $attributes = $__attributesOriginalc2a653d0f44ebc115de2e0e2b2511155; ?>
<?php unset($__attributesOriginalc2a653d0f44ebc115de2e0e2b2511155); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2a653d0f44ebc115de2e0e2b2511155)): ?>
<?php $component = $__componentOriginalc2a653d0f44ebc115de2e0e2b2511155; ?>
<?php unset($__componentOriginalc2a653d0f44ebc115de2e0e2b2511155); ?>
<?php endif; ?>
<?php ($presentCount = $entry->activity->sessions->filter(fn ($session) => $session->attendances->first()?->attendance_status === 'present')->count()); ?>
<div class="sipora-card flex flex-wrap items-center justify-between gap-3"><div><p class="text-xs font-bold uppercase tracking-wider text-interactive">Ringkasan kehadiran</p><strong class="text-xl text-text-primary"><?php echo e($presentCount); ?> dari <?php echo e($entry->activity->sessions->count()); ?> sesi hadir</strong></div><?php if (isset($component)) { $__componentOriginal77427818d7088fcafb4732ee4ea7535a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal77427818d7088fcafb4732ee4ea7535a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portfolio.provenance-badge','data' => ['verified' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portfolio.provenance-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['verified' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal77427818d7088fcafb4732ee4ea7535a)): ?>
<?php $attributes = $__attributesOriginal77427818d7088fcafb4732ee4ea7535a; ?>
<?php unset($__attributesOriginal77427818d7088fcafb4732ee4ea7535a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal77427818d7088fcafb4732ee4ea7535a)): ?>
<?php $component = $__componentOriginal77427818d7088fcafb4732ee4ea7535a; ?>
<?php unset($__componentOriginal77427818d7088fcafb4732ee4ea7535a); ?>
<?php endif; ?></div>
<div class="grid gap-6 lg:grid-cols-[1fr_300px]"><section class="sipora-card"><h2 class="text-xl font-bold text-[#14205c]">Rangkaian sesi dan kehadiran</h2><div class="mt-5 space-y-3"><?php $__empty_1 = true; $__currentLoopData = $entry->activity->sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php ($attendance=$session->attendances->first()); ?><div class="flex flex-wrap items-center justify-between gap-3 rounded-xl bg-slate-50 p-4"><div><p class="font-bold"><?php echo e($session->title); ?></p><p class="mt-1 text-xs text-slate-500"><?php echo e($session->start_at->translatedFormat('d M Y, H:i')); ?> · <?php echo e($session->venue_name ?: $entry->activity->location_type); ?></p></div><span class="rounded-full px-3 py-1 text-xs font-bold <?php echo e($attendance?->attendance_status==='present' ? 'bg-emerald-100 text-emerald-700' : ($attendance?->attendance_status==='excused' ? 'bg-amber-100 text-amber-700' : 'bg-slate-200 text-slate-600')); ?>"><?php echo e(match($attendance?->attendance_status){'present'=>'Hadir','excused'=>'Izin','absent'=>'Absen',default=>'Belum tercatat'}); ?></span></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p class="text-sm text-slate-500">Tidak ada rincian sesi.</p><?php endif; ?></div></section>
<aside class="sipora-card"><h2 class="font-bold text-[#14205c]">Detail Passport</h2><dl class="mt-4 space-y-4 text-sm"><div><dt class="text-slate-500">Kategori</dt><dd class="font-bold"><?php echo e($entry->activity->category?->name ?? '—'); ?></dd></div><div><dt class="text-slate-500">Peran</dt><dd class="font-bold capitalize"><?php echo e($entry->activity_role); ?></dd></div><div><dt class="text-slate-500">Periode</dt><dd class="font-bold"><?php echo e($entry->activity->start_at->translatedFormat('d M Y')); ?> – <?php echo e($entry->activity->end_at->translatedFormat('d M Y')); ?></dd></div><div><dt class="text-slate-500">Diselesaikan</dt><dd class="font-bold"><?php echo e($entry->completed_at?->translatedFormat('d M Y, H:i')); ?></dd></div><div><dt class="text-slate-500">ID Activity</dt><dd class="break-all font-mono text-xs"><?php echo e($entry->activity->uuid()); ?></dd></div></dl><?php if($entry->certificate): ?><div class="mt-6 rounded-xl bg-indigo-50 p-4"><p class="text-xs font-bold uppercase text-[#243378]">Sertifikat tersedia</p><p class="mt-1 break-all text-xs text-slate-600"><?php echo e($entry->certificate->certificate_number); ?></p><a class="btn-primary mt-3" href="<?php echo e(route('youth.certificates.show',$entry->certificate)); ?>">Lihat sertifikat</a></div><?php else: ?><p class="mt-6 rounded-xl bg-slate-50 p-3 text-xs leading-5 text-slate-600">Belum ada sertifikat yang diterbitkan untuk pengalaman ini.</p><?php endif; ?></aside></div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.youth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/youth/passport/show.blade.php ENDPATH**/ ?>