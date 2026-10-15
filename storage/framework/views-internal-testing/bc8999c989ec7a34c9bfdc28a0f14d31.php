<?php $__env->startSection('title', 'Activity Passport · SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-6"><?php if (isset($component)) { $__componentOriginal8e0a96c106aaf9b655be7405167c4255 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8e0a96c106aaf9b655be7405167c4255 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.page-header','data' => ['eyebrow' => 'Pengalaman terverifikasi','title' => 'Activity Passport','description' => 'Riwayat partisipasi yang telah diselesaikan dan disahkan oleh pengelola Activity SIPORA.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Pengalaman terverifikasi','title' => 'Activity Passport','description' => 'Riwayat partisipasi yang telah diselesaikan dan disahkan oleh pengelola Activity SIPORA.']); ?><div class="rounded-xl bg-indigo-50 px-5 py-3 text-center"><p class="text-2xl font-black text-primary"><?php echo e($completedCount); ?></p><p class="text-xs font-semibold text-slate-600">Activity selesai</p></div> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8e0a96c106aaf9b655be7405167c4255)): ?>
<?php $attributes = $__attributesOriginal8e0a96c106aaf9b655be7405167c4255; ?>
<?php unset($__attributesOriginal8e0a96c106aaf9b655be7405167c4255); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e0a96c106aaf9b655be7405167c4255)): ?>
<?php $component = $__componentOriginal8e0a96c106aaf9b655be7405167c4255; ?>
<?php unset($__componentOriginal8e0a96c106aaf9b655be7405167c4255); ?>
<?php endif; ?>
<section class="rounded-2xl border border-indigo-100 bg-indigo-50 p-4 text-sm text-[#243378]"><strong>Data tepercaya SIPORA.</strong> Passport dibentuk otomatis dari partisipasi yang diterima dan diselesaikan oleh pengelola. Catatan ini masih privat dan belum menjadi Portfolio publik.</section>
<div class="grid gap-4 lg:grid-cols-2"><?php $__empty_1 = true; $__currentLoopData = $entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><article class="sipora-card flex flex-col"><div class="flex items-start justify-between gap-3"><div><p class="text-xs font-extrabold uppercase tracking-wider text-orange-600"><?php echo e($entry->activity->category?->name ?? 'Activity'); ?></p><h2 class="mt-1 text-xl font-black text-text-primary"><?php echo e($entry->activity->title); ?></h2></div><div class="flex flex-col items-end gap-2"><?php if (isset($component)) { $__componentOriginal4cc5583ac6f3cb736f99770e4859859b = $component; } ?>
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
<?php endif; ?><?php if($entry->certificate): ?><span class="rounded-full bg-indigo-100 px-2.5 py-1 text-xs font-bold text-primary">Sertifikat tersedia</span><?php endif; ?></div></div><dl class="mt-5 grid gap-3 text-sm sm:grid-cols-2"><div><dt class="text-slate-500">Penyelenggara</dt><dd class="font-bold"><?php echo e($entry->activity->organization->name); ?></dd></div><div><dt class="text-slate-500">Periode</dt><dd class="font-bold"><?php echo e($entry->activity->start_at->translatedFormat('d M Y')); ?> – <?php echo e($entry->activity->end_at->translatedFormat('d M Y')); ?></dd></div><div><dt class="text-slate-500">Peran</dt><dd class="font-bold capitalize"><?php echo e($entry->activity_role); ?></dd></div><div><dt class="text-slate-500">Lokasi</dt><dd class="font-bold capitalize"><?php echo e($entry->activity->location_type); ?><?php echo e($entry->activity->venue_name ? ' · '.$entry->activity->venue_name : ''); ?></dd></div></dl><div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4"><p class="text-xs text-slate-500"><?php echo e($entry->present_count); ?> dari <?php echo e($entry->activity->sessions_count); ?> sesi hadir · <?php echo e($entry->absent_count); ?> absen · <?php echo e($entry->excused_count); ?> izin</p><div class="flex gap-3"><?php if($entry->certificate): ?><a class="text-sm font-bold text-orange-600" href="<?php echo e(route('youth.certificates.show',$entry->certificate)); ?>">Sertifikat</a><?php endif; ?><a class="text-sm font-bold text-primary" href="<?php echo e(route('youth.passport.show',$entry)); ?>">Lihat detail →</a></div></div></article><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if (isset($component)) { $__componentOriginal3607a477fdef7402bc742abad5df9c51 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3607a477fdef7402bc742abad5df9c51 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.empty-state','data' => ['class' => 'col-span-full','title' => 'Passport-mu masih kosong','description' => 'Activity akan muncul setelah pendaftaran diterima dan pengelola menetapkan partisipasimu selesai.','action' => 'Jelajahi Activity','href' => route('activities.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'col-span-full','title' => 'Passport-mu masih kosong','description' => 'Activity akan muncul setelah pendaftaran diterima dan pengelola menetapkan partisipasimu selesai.','action' => 'Jelajahi Activity','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('activities.index'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $attributes = $__attributesOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__attributesOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $component = $__componentOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__componentOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?><?php endif; ?></div><?php echo e($entries->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.youth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/youth/passport/index.blade.php ENDPATH**/ ?>