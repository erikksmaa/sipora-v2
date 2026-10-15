<?php $__env->startSection('title', 'Moderasi Feed · SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-6"><?php if (isset($component)) { $__componentOriginal91a231a9270579fa1ae9246bd51fb785 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91a231a9270579fa1ae9246bd51fb785 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.page-header','data' => ['eyebrow' => 'Konten','title' => 'Moderasi Feed','description' => 'Tinjau laporan post dan komentar. Riwayat tetap disimpan.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Konten','title' => 'Moderasi Feed','description' => 'Tinjau laporan post dan komentar. Riwayat tetap disimpan.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $attributes = $__attributesOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $component = $__componentOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__componentOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?><nav class="flex flex-wrap gap-2" aria-label="Status laporan"><?php $__currentLoopData = ['pending'=>'Menunggu','resolved'=>'Selesai','all'=>'Semua']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><a class="btn-outline" href="<?php echo e(route('admin.social.index', ['status'=>$key])); ?>" <?php if($status===$key): ?> aria-current="page" <?php endif; ?>><?php echo e($label); ?></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></nav>
<?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><article class="sipora-card space-y-4 p-5"><p class="text-xs font-bold text-interactive"><?php echo e($report->reason); ?> · <?php echo e($report->status); ?> · <?php echo e($report->comment ? 'Komentar' : 'Post'); ?></p><p class="whitespace-pre-line break-words text-sm leading-7 text-text-secondary"><?php echo e($report->comment?->body ?? $report->post->body); ?></p><?php if($report->details): ?><p class="text-sm text-text-muted">Keterangan: <?php echo e($report->details); ?></p><?php endif; ?> <?php if($report->post->image_path): ?><img class="max-h-80 max-w-full object-contain" src="<?php echo e(route('admin.social.image', $report->post)); ?>" alt="Lampiran post yang dilaporkan" loading="lazy"><?php endif; ?>
<div class="flex flex-wrap gap-2"><form method="post" action="<?php echo e(route('admin.social.posts.update', $report->post)); ?>"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?><input type="hidden" name="is_hidden" value="<?php echo e($report->post->is_hidden ? 0 : 1); ?>"><button class="btn-outline" type="submit"><?php echo e($report->post->is_hidden ? 'Tampilkan post' : 'Sembunyikan post'); ?></button></form><?php if($report->comment): ?><form method="post" action="<?php echo e(route('admin.social.comments.update', $report->comment)); ?>"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?><input type="hidden" name="is_hidden" value="<?php echo e($report->comment->is_hidden ? 0 : 1); ?>"><button class="btn-outline" type="submit"><?php echo e($report->comment->is_hidden ? 'Tampilkan komentar' : 'Sembunyikan komentar'); ?></button></form><?php endif; ?> <?php if($report->status==='pending'): ?><form method="post" action="<?php echo e(route('admin.social.reports.resolve', $report)); ?>"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?><button class="btn-primary" type="submit">Tutup laporan</button></form><?php endif; ?></div></article><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><div class="sipora-card p-8"><?php if (isset($component)) { $__componentOriginal3607a477fdef7402bc742abad5df9c51 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3607a477fdef7402bc742abad5df9c51 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.empty-state','data' => ['icon' => 'shield-check','title' => 'Tidak ada laporan','description' => 'Laporan Social Feed akan tampil di sini.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'shield-check','title' => 'Tidak ada laporan','description' => 'Laporan Social Feed akan tampil di sini.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $attributes = $__attributesOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__attributesOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $component = $__componentOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__componentOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?></div><?php endif; ?><?php echo e($reports->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/admin/social/index.blade.php ENDPATH**/ ?>