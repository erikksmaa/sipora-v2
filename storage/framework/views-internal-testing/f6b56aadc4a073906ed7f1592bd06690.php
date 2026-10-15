
<?php $__env->startSection('title', 'Program Pemuda · SIPORA'); ?>
<?php $__env->startSection('meta_description', 'Jelajahi Program pemuda yang sedang berjalan atau telah selesai melalui ekosistem SIPORA Kabupaten Pemalang.'); ?>
<?php $__env->startSection('canonical', route('programs.index')); ?>
<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginal780d11c2fd95c33fc53cd02cfb936d73 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal780d11c2fd95c33fc53cd02cfb936d73 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.page-intro','data' => ['eyebrow' => 'Program publik','title' => 'Program untuk pengembangan pemuda','description' => 'Program yang sedang berjalan atau telah selesai, memiliki Proposal disetujui, dan diselenggarakan Community aktif.','icon' => 'notebook','variant' => 'program']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.page-intro'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Program publik','title' => 'Program untuk pengembangan pemuda','description' => 'Program yang sedang berjalan atau telah selesai, memiliki Proposal disetujui, dan diselenggarakan Community aktif.','icon' => 'notebook','variant' => 'program']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal780d11c2fd95c33fc53cd02cfb936d73)): ?>
<?php $attributes = $__attributesOriginal780d11c2fd95c33fc53cd02cfb936d73; ?>
<?php unset($__attributesOriginal780d11c2fd95c33fc53cd02cfb936d73); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal780d11c2fd95c33fc53cd02cfb936d73)): ?>
<?php $component = $__componentOriginal780d11c2fd95c33fc53cd02cfb936d73; ?>
<?php unset($__componentOriginal780d11c2fd95c33fc53cd02cfb936d73); ?>
<?php endif; ?>
<div class="bg-surface-soft px-5 py-10"><div class="mx-auto max-w-7xl"><?php if (isset($component)) { $__componentOriginal9bcf51b467deef585b101ab8171ad7ba = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9bcf51b467deef585b101ab8171ad7ba = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.filter-panel','data' => ['action' => route('programs.index'),'reset' => route('programs.index'),'label' => 'Filter Program']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.filter-panel'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('programs.index')),'reset' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('programs.index')),'label' => 'Filter Program']); ?><div class="grid gap-4 md:grid-cols-3"><label><span class="label">Cari Program</span><input class="field" type="search" name="q" value="<?php echo e($filters['q']); ?>" maxlength="120" placeholder="Judul, Community, kategori, atau tujuan"></label><label><span class="label">Kategori</span><select class="field" name="category"><option value="">Semua kategori</option><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($category->slug); ?>" <?php if($filters['category']===$category->slug): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label><label><span class="label">Status</span><select class="field" name="status"><option value="">Berjalan dan selesai</option><option value="running" <?php if($filters['status']==='running'): echo 'selected'; endif; ?>>Berjalan</option><option value="completed" <?php if($filters['status']==='completed'): echo 'selected'; endif; ?>>Selesai</option></select></label></div> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9bcf51b467deef585b101ab8171ad7ba)): ?>
<?php $attributes = $__attributesOriginal9bcf51b467deef585b101ab8171ad7ba; ?>
<?php unset($__attributesOriginal9bcf51b467deef585b101ab8171ad7ba); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9bcf51b467deef585b101ab8171ad7ba)): ?>
<?php $component = $__componentOriginal9bcf51b467deef585b101ab8171ad7ba; ?>
<?php unset($__componentOriginal9bcf51b467deef585b101ab8171ad7ba); ?>
<?php endif; ?><div class="mt-9"><p class="text-xs font-bold uppercase tracking-wider text-accent-600">Program terverifikasi</p><h2 class="mt-1 text-2xl font-bold text-text-primary"><?php echo e($programs->total()); ?> hasil ditemukan</h2></div><div class="mt-6 public-card-grid"><?php $__empty_1 = true; $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if (isset($component)) { $__componentOriginale07892df06fff4909c13fe4117821ea6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale07892df06fff4909c13fe4117821ea6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.discovery.program-card','data' => ['program' => $program]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('discovery.program-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['program' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($program)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale07892df06fff4909c13fe4117821ea6)): ?>
<?php $attributes = $__attributesOriginale07892df06fff4909c13fe4117821ea6; ?>
<?php unset($__attributesOriginale07892df06fff4909c13fe4117821ea6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale07892df06fff4909c13fe4117821ea6)): ?>
<?php $component = $__componentOriginale07892df06fff4909c13fe4117821ea6; ?>
<?php unset($__componentOriginale07892df06fff4909c13fe4117821ea6); ?>
<?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if (isset($component)) { $__componentOriginal3607a477fdef7402bc742abad5df9c51 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3607a477fdef7402bc742abad5df9c51 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.empty-state','data' => ['class' => 'col-span-full','title' => 'Belum ada Program yang cocok','description' => 'Ubah kata pencarian atau filter untuk melihat Program lain.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'col-span-full','title' => 'Belum ada Program yang cocok','description' => 'Ubah kata pencarian atau filter untuk melihat Program lain.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $attributes = $__attributesOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__attributesOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $component = $__componentOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__componentOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?><?php endif; ?></div><?php if($programs->hasPages()): ?><div class="mt-10"><?php echo e($programs->links()); ?></div><?php endif; ?></div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/public/programs/index.blade.php ENDPATH**/ ?>