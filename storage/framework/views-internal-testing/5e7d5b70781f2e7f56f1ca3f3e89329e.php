<?php $__env->startSection('title', 'Social Feed · SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<section class="public-page-intro"><div class="mx-auto max-w-4xl px-5"><p class="text-xs font-bold uppercase tracking-widest text-orange-300">Kabar ekosistem pemuda</p><h1 class="mt-3 font-heading text-3xl font-bold">Social Feed</h1><p class="mt-3 text-sm leading-7 text-white/85">Update singkat, kegiatan, dan kesempatan terbaru. Diurutkan dari yang paling baru, tanpa peringkat popularitas.</p></div></section>
<div class="mx-auto max-w-4xl space-y-5 px-5 py-8">
<div class="sipora-card flex flex-wrap items-center justify-between gap-3 p-4"><p class="text-sm text-text-secondary">Bagikan kabar dan temukan ruang untuk bertumbuh.</p><?php if(auth()->guard()->check()): ?> <?php if(auth()->user()->hasRole('youth')): ?><a class="btn-primary" href="<?php echo e(route('social.create')); ?>">Buat post</a><?php endif; ?> <?php else: ?><a class="btn-outline" href="<?php echo e(route('login')); ?>">Masuk untuk berinteraksi</a><?php endif; ?></div>
<nav class="flex flex-wrap gap-2" aria-label="Filter Social Feed"><?php $__currentLoopData = ['all'=>'Semua','following'=>'Mengikuti','activity'=>'Activity','community'=>'Community','opportunity'=>'Opportunity']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><a class="btn-outline !min-h-10 !px-3 text-sm <?php echo e($tab === $key ? '!bg-surface-soft' : ''); ?>" href="<?php echo e(route('social.index', ['tab'=>$key])); ?>" <?php if($tab===$key): ?> aria-current="page" <?php endif; ?>><?php echo e($label); ?></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php if(auth()->guard()->check()): ?> <?php if(auth()->user()->hasRole('youth')): ?><a class="btn-outline !min-h-10 !px-3 text-sm" href="<?php echo e(route('social.index', ['tab'=>'mine'])); ?>" <?php if($tab==='mine'): ?> aria-current="page" <?php endif; ?>>Post saya</a><?php endif; ?> <?php endif; ?></nav>
<?php if($tab === 'following'): ?><p class="text-sm text-text-muted">Hanya post publik dari Youth dan Community yang kamu ikuti serta update ekosistem dari Community tersebut, tetap terbaru lebih dulu.</p><?php endif; ?>
<?php if(!$entries): ?><div class="sipora-card p-8"><p class="text-text-secondary">Masuk untuk melihat sumber yang kamu ikuti.</p><a class="btn-outline mt-4" href="<?php echo e(route('login')); ?>">Masuk</a></div><?php else: ?>
<?php $__empty_1 = true; $__currentLoopData = $entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<?php if($entry['kind'] === 'post'): ?><?php echo $__env->make('social.post-card', ['post'=>$entry['model'], 'liked'=>$entry['model']->liked, 'followed'=>$entry['followed'], 'detail'=>false], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php else: ?> <?php ($model = $entry['model']); ?> <?php ($kind = $entry['kind']); ?>
<article class="sipora-card space-y-3 p-5 sm:p-6"><p class="text-xs font-bold uppercase tracking-widest text-interactive"><?php echo e(['activity'=>'Activity dipublikasikan','community'=>'Community terverifikasi','opportunity'=>'Opportunity dipublikasikan'][$kind]); ?> · Update ekosistem</p><h2 class="text-xl font-bold text-text-primary"><?php echo e($kind === 'community' ? $model->name : $model->title); ?></h2><p class="text-sm text-text-secondary"><?php echo e(\Illuminate\Support\Str::limit(strip_tags($model->description ?? ''), 180)); ?></p><div class="flex flex-wrap items-center gap-3"><a class="btn-outline !min-h-10 !px-3 text-sm" href="<?php echo e(route(['activity'=>'activities.show','community'=>'communities.show','opportunity'=>'opportunities.show'][$kind], $model)); ?>">Lihat <?php echo e(ucfirst($kind)); ?></a><?php ($source = $kind === 'community' ? $model : $model->organization); ?><?php if($source && $source->review_status === 'approved' && $source->operational_status === 'active'): ?><?php echo $__env->make('social.follow', ['targetType'=>'community', 'targetId'=>$source->slug, 'followed'=>$entry['followed']], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php endif; ?><time class="text-xs text-text-muted" datetime="<?php echo e(($kind==='community' ? $model->approved_at : $model->published_at)->toIso8601String()); ?>"><?php echo e(($kind==='community' ? $model->approved_at : $model->published_at)->diffForHumans()); ?></time></div></article>
<?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><div class="sipora-card p-8"><?php if (isset($component)) { $__componentOriginal3607a477fdef7402bc742abad5df9c51 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3607a477fdef7402bc742abad5df9c51 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.empty-state','data' => ['icon' => 'users','title' => 'Belum ada update','description' => 'Ikuti sumber dari post, profil publik Youth, atau Community untuk mengisi feed Mengikuti.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'users','title' => 'Belum ada update','description' => 'Ikuti sumber dari post, profil publik Youth, atau Community untuk mengisi feed Mengikuti.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $attributes = $__attributesOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__attributesOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $component = $__componentOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__componentOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?><div class="mt-4 flex flex-wrap gap-2"><a class="btn-outline" href="<?php echo e(route('youth-directory.index')); ?>">Temukan Youth</a><a class="btn-outline" href="<?php echo e(route('communities.index')); ?>">Temukan Community</a></div></div><?php endif; ?>
<?php echo e($entries->links()); ?><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/social/index.blade.php ENDPATH**/ ?>