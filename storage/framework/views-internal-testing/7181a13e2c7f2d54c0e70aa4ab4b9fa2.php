
<?php $__env->startSection('title', 'Jelajahi Community · SIPORA'); ?>
<?php $__env->startSection('meta_description', 'Jelajahi Community aktif dan terverifikasi di SIPORA Kabupaten Pemalang.'); ?>
<?php $__env->startSection('canonical', route('communities.index')); ?>
<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginal780d11c2fd95c33fc53cd02cfb936d73 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal780d11c2fd95c33fc53cd02cfb936d73 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.page-intro','data' => ['eyebrow' => 'Tumbuh bersama','title' => 'Temukan Community yang tepat','description' => 'Jelajahi Community aktif dan terverifikasi di seluruh Kabupaten Pemalang.','icon' => 'building','variant' => 'community']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.page-intro'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Tumbuh bersama','title' => 'Temukan Community yang tepat','description' => 'Jelajahi Community aktif dan terverifikasi di seluruh Kabupaten Pemalang.','icon' => 'building','variant' => 'community']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.filter-panel','data' => ['action' => route('communities.index'),'reset' => route('communities.index'),'label' => 'Filter Community']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.filter-panel'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('communities.index')),'reset' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('communities.index')),'label' => 'Filter Community']); ?><div class="grid gap-4 md:grid-cols-3"><label><span class="label">Cari Community</span><input class="field" type="search" name="q" value="<?php echo e($filters['q']); ?>" maxlength="120" placeholder="Nama, kategori, deskripsi, atau lokasi"></label><label><span class="label">Kategori</span><select class="field" name="category"><option value="">Semua kategori</option><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($category->slug); ?>" <?php if($filters['category']===$category->slug): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label><label><span class="label">Kecamatan</span><select class="field" name="location"><option value="">Semua lokasi</option><?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($location->code); ?>" <?php if($filters['location']===$location->code): echo 'selected'; endif; ?>><?php echo e($location->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label></div> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9bcf51b467deef585b101ab8171ad7ba)): ?>
<?php $attributes = $__attributesOriginal9bcf51b467deef585b101ab8171ad7ba; ?>
<?php unset($__attributesOriginal9bcf51b467deef585b101ab8171ad7ba); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9bcf51b467deef585b101ab8171ad7ba)): ?>
<?php $component = $__componentOriginal9bcf51b467deef585b101ab8171ad7ba; ?>
<?php unset($__componentOriginal9bcf51b467deef585b101ab8171ad7ba); ?>
<?php endif; ?><div class="mt-9 flex flex-wrap items-end justify-between gap-3"><div><p class="text-xs font-bold uppercase tracking-wider text-accent-600">Community publik</p><h2 class="mt-1 text-2xl font-bold text-text-primary"><?php echo e($communities->total()); ?> hasil ditemukan</h2></div><a class="public-link" href="<?php echo e(route('activities.index')); ?>">Jelajahi Activity <?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'chevron-right','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-right','class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></a></div><div class="mt-6 public-card-grid"><?php $__empty_1 = true; $__currentLoopData = $communities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $community): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if (isset($component)) { $__componentOriginale1f00f9f06c1955a4a1e19b72a6c138b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale1f00f9f06c1955a4a1e19b72a6c138b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.discovery.community-card','data' => ['community' => $community]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('discovery.community-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['community' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($community)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale1f00f9f06c1955a4a1e19b72a6c138b)): ?>
<?php $attributes = $__attributesOriginale1f00f9f06c1955a4a1e19b72a6c138b; ?>
<?php unset($__attributesOriginale1f00f9f06c1955a4a1e19b72a6c138b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale1f00f9f06c1955a4a1e19b72a6c138b)): ?>
<?php $component = $__componentOriginale1f00f9f06c1955a4a1e19b72a6c138b; ?>
<?php unset($__componentOriginale1f00f9f06c1955a4a1e19b72a6c138b); ?>
<?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if (isset($component)) { $__componentOriginal3607a477fdef7402bc742abad5df9c51 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3607a477fdef7402bc742abad5df9c51 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.empty-state','data' => ['class' => 'col-span-full','title' => 'Tidak ada Community yang cocok','description' => 'Coba kata pencarian, kategori, atau lokasi lain.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'col-span-full','title' => 'Tidak ada Community yang cocok','description' => 'Coba kata pencarian, kategori, atau lokasi lain.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $attributes = $__attributesOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__attributesOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $component = $__componentOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__componentOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?><?php endif; ?></div><?php if($communities->hasPages()): ?><div class="mt-10"><?php echo e($communities->links()); ?></div><?php endif; ?></div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/public/communities/index.blade.php ENDPATH**/ ?>