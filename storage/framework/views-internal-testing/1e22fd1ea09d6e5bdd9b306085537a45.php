
<?php $__env->startSection('title', 'Opportunity Hub · SIPORA'); ?>
<?php $__env->startSection('meta_description', 'Temukan informasi Opportunity eksternal yang dikurasi Admin SIPORA Kabupaten Pemalang.'); ?>
<?php $__env->startSection('canonical', route('opportunities.index')); ?>
<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginal780d11c2fd95c33fc53cd02cfb936d73 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal780d11c2fd95c33fc53cd02cfb936d73 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.page-intro','data' => ['eyebrow' => 'Opportunity Hub','title' => 'Temukan peluang pengembangan diri','description' => 'Informasi peluang eksternal yang dikurasi Admin SIPORA. Pendaftaran dilakukan pada situs penyedia.','icon' => 'briefcase','variant' => 'opportunity']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.page-intro'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Opportunity Hub','title' => 'Temukan peluang pengembangan diri','description' => 'Informasi peluang eksternal yang dikurasi Admin SIPORA. Pendaftaran dilakukan pada situs penyedia.','icon' => 'briefcase','variant' => 'opportunity']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.filter-panel','data' => ['action' => route('opportunities.index'),'reset' => route('opportunities.index'),'label' => 'Filter Opportunity']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.filter-panel'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('opportunities.index')),'reset' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('opportunities.index')),'label' => 'Filter Opportunity']); ?><div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4"><label class="xl:col-span-2"><span class="label">Cari Opportunity</span><input class="field" type="search" name="q" value="<?php echo e($filters['q']); ?>" maxlength="120" placeholder="Judul, penyedia, kategori, atau lokasi"></label><label><span class="label">Kategori</span><select class="field" name="category"><option value="">Semua kategori</option><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($category->slug); ?>" <?php if($filters['category']===$category->slug): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label><label><span class="label">Kecamatan</span><select class="field" name="location"><option value="">Semua lokasi</option><?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($location->code); ?>" <?php if($filters['location']===$location->code): echo 'selected'; endif; ?>><?php echo e($location->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label><label><span class="label">Status batas waktu</span><select class="field" name="deadline"><option value="open" <?php if($filters['deadline']==='open'): echo 'selected'; endif; ?>>Masih tersedia</option><option value="expired" <?php if($filters['deadline']==='expired'): echo 'selected'; endif; ?>>Sudah lewat</option><option value="all" <?php if($filters['deadline']==='all'): echo 'selected'; endif; ?>>Semua</option></select></label></div> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9bcf51b467deef585b101ab8171ad7ba)): ?>
<?php $attributes = $__attributesOriginal9bcf51b467deef585b101ab8171ad7ba; ?>
<?php unset($__attributesOriginal9bcf51b467deef585b101ab8171ad7ba); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9bcf51b467deef585b101ab8171ad7ba)): ?>
<?php $component = $__componentOriginal9bcf51b467deef585b101ab8171ad7ba; ?>
<?php unset($__componentOriginal9bcf51b467deef585b101ab8171ad7ba); ?>
<?php endif; ?><div class="mt-9 flex flex-wrap items-end justify-between gap-3"><div><p class="text-xs font-bold uppercase tracking-wider text-accent-600">Peluang terkurasi</p><h2 class="mt-1 text-2xl font-bold text-text-primary"><?php echo e($opportunities->total()); ?> hasil ditemukan</h2></div><?php if(auth()->guard()->check()): ?> <?php if (\Illuminate\Support\Facades\Blade::check('role', 'youth')): ?><a class="public-link" href="<?php echo e(route('youth.opportunities.bookmarks')); ?>"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'bookmark','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'bookmark','class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>Opportunity tersimpan</a><?php endif; ?> <?php endif; ?></div><div class="mt-6 public-card-grid"><?php $__empty_1 = true; $__currentLoopData = $opportunities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opportunity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if (isset($component)) { $__componentOriginal31ba4aa0af51a52f1a7404d47e1cf707 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal31ba4aa0af51a52f1a7404d47e1cf707 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.discovery.opportunity-card','data' => ['opportunity' => $opportunity]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('discovery.opportunity-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['opportunity' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($opportunity)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal31ba4aa0af51a52f1a7404d47e1cf707)): ?>
<?php $attributes = $__attributesOriginal31ba4aa0af51a52f1a7404d47e1cf707; ?>
<?php unset($__attributesOriginal31ba4aa0af51a52f1a7404d47e1cf707); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal31ba4aa0af51a52f1a7404d47e1cf707)): ?>
<?php $component = $__componentOriginal31ba4aa0af51a52f1a7404d47e1cf707; ?>
<?php unset($__componentOriginal31ba4aa0af51a52f1a7404d47e1cf707); ?>
<?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if (isset($component)) { $__componentOriginal3607a477fdef7402bc742abad5df9c51 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3607a477fdef7402bc742abad5df9c51 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.empty-state','data' => ['class' => 'col-span-full','title' => 'Tidak ada Opportunity yang cocok','description' => 'Ubah pencarian atau filter untuk melihat pilihan lain.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'col-span-full','title' => 'Tidak ada Opportunity yang cocok','description' => 'Ubah pencarian atau filter untuk melihat pilihan lain.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $attributes = $__attributesOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__attributesOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $component = $__componentOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__componentOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?><?php endif; ?></div><?php if($opportunities->hasPages()): ?><div class="mt-10"><?php echo e($opportunities->links()); ?></div><?php endif; ?></div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/public/opportunities/index.blade.php ENDPATH**/ ?>