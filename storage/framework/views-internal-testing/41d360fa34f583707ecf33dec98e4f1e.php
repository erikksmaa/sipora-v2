<?php $__env->startSection('title', 'Moderasi Forum · SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-6"><?php if (isset($component)) { $__componentOriginal91a231a9270579fa1ae9246bd51fb785 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91a231a9270579fa1ae9246bd51fb785 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.page-header','data' => ['eyebrow' => 'Konten','title' => 'Moderasi Forum','description' => 'Tinjau laporan, sembunyikan konten, atau kunci diskusi tanpa menghapus riwayat.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Konten','title' => 'Moderasi Forum','description' => 'Tinjau laporan, sembunyikan konten, atau kunci diskusi tanpa menghapus riwayat.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $attributes = $__attributesOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $component = $__componentOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__componentOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?><div class="flex flex-wrap gap-2"><?php $__currentLoopData = ['pending' => 'Menunggu', 'resolved' => 'Selesai', 'all' => 'Semua']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><a class="btn-outline !min-h-9 !px-3 text-sm" href="<?php echo e(route('admin.forum.index', ['status' => $value])); ?>" <?php if($status === $value): ?> aria-current="page" <?php endif; ?>><?php echo e($label); ?></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div><div class="space-y-4"><?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><article class="sipora-card p-5"><div class="flex flex-wrap items-start justify-between gap-3"><div><p class="text-xs font-bold uppercase tracking-wider text-interactive"><?php echo e($report->reason); ?> · <?php echo e($report->status); ?></p><h2 class="mt-1 font-heading text-lg font-bold text-text-primary"><?php echo e($report->thread->title); ?></h2><p class="mt-1 text-xs text-text-muted">Dilaporkan <?php echo e($report->created_at->diffForHumans()); ?> · <?php echo e($report->reply ? 'Balasan' : 'Diskusi'); ?></p></div><?php if($report->thread->status !== 'hidden'): ?><a class="btn-outline !min-h-9 !px-3 text-sm" href="<?php echo e(route('forum.show', $report->thread)); ?>">Buka diskusi</a><?php endif; ?></div><div class="mt-4 border border-border bg-surface-soft p-4 text-sm text-text-secondary"><p class="font-bold text-text-primary">Konten yang dilaporkan</p><p class="mt-2 whitespace-pre-line break-words"><?php echo e($report->reply?->body ?? $report->thread->body); ?></p><?php if($report->details): ?><p class="mt-3 text-xs">Keterangan: <?php echo e($report->details); ?></p><?php endif; ?></div><div class="mt-4 flex flex-wrap gap-2"><form method="post" action="<?php echo e(route('admin.forum.threads.update', $report->thread)); ?>"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?><select class="field" name="status" aria-label="Status diskusi" onchange="this.form.submit()"><?php $__currentLoopData = ['active'=>'Aktif','locked'=>'Terkunci','hidden'=>'Sembunyi']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($value); ?>" <?php if($report->thread->status === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></form><?php if($report->reply): ?><form method="post" action="<?php echo e(route('admin.forum.replies.update', $report->reply)); ?>"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?><input type="hidden" name="is_hidden" value="<?php echo e($report->reply->is_hidden ? 0 : 1); ?>"><button class="btn-outline !min-h-9 !px-3 text-sm" type="submit"><?php echo e($report->reply->is_hidden ? 'Tampilkan balasan' : 'Sembunyikan balasan'); ?></button></form><?php endif; ?> <?php if($report->status === 'pending'): ?><form method="post" action="<?php echo e(route('admin.forum.reports.resolve', $report)); ?>"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?><button class="btn-primary !min-h-9 !px-3 text-sm" type="submit">Tutup laporan</button></form><?php endif; ?></div></article><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><div class="sipora-card p-8"><?php if (isset($component)) { $__componentOriginal3607a477fdef7402bc742abad5df9c51 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3607a477fdef7402bc742abad5df9c51 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.empty-state','data' => ['icon' => 'shield-check','title' => 'Tidak ada laporan','description' => 'Laporan Forum akan tampil di sini.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'shield-check','title' => 'Tidak ada laporan','description' => 'Laporan Forum akan tampil di sini.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $attributes = $__attributesOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__attributesOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $component = $__componentOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__componentOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?></div><?php endif; ?></div><?php echo e($reports->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/admin/forum/index.blade.php ENDPATH**/ ?>