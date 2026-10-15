
<?php $__env->startSection('title', 'Forum Community · SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<section class="public-page-intro"><div class="mx-auto max-w-7xl px-5"><p class="text-xs font-extrabold uppercase tracking-[.2em] text-orange-300">Ruang diskusi pemuda</p><h1 class="mt-3 font-heading text-3xl font-bold sm:text-4xl">Forum Community</h1><p class="mt-3 max-w-2xl text-sm leading-7 text-white/85">Tanyakan hal yang ingin kamu pelajari, bagikan pengalaman, dan bantu sesama pemuda Pemalang.</p><div class="mt-6"><?php if(auth()->guard()->check()): ?> <?php if(auth()->user()->hasRole('youth')): ?><a class="btn-primary" href="<?php echo e(route('forum.create')); ?>">Mulai diskusi</a><?php endif; ?> <?php else: ?> <a class="btn-primary" href="<?php echo e(route('login')); ?>">Masuk untuk berdiskusi</a> <?php endif; ?></div></div></section>
<div class="mx-auto grid max-w-7xl gap-7 px-5 py-10 lg:grid-cols-[250px_minmax(0,1fr)]"><aside x-data="{ categoriesOpen: false }" class="sipora-card h-fit p-5"><h2 class="hidden font-heading text-lg font-bold text-text-primary lg:block">Kategori</h2><button class="btn-outline w-full justify-between lg:hidden" type="button" @click="categoriesOpen = !categoriesOpen" :aria-expanded="categoriesOpen" aria-controls="forum-categories">Kategori: <?php echo e($categories->firstWhere('slug', $category)?->name ?? 'Semua topik'); ?> <?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'chevron-down','class' => 'size-4 shrink-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-down','class' => 'size-4 shrink-0']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></button><nav id="forum-categories" class="mt-4 grid gap-1" :class="categoriesOpen ? 'grid' : 'hidden lg:grid'" aria-label="Kategori Forum"><a class="rounded-lg px-3 py-2 text-sm font-semibold hover:bg-surface-soft <?php echo e(!$category ? 'bg-surface-soft text-primary' : 'text-text-secondary'); ?>" href="<?php echo e(route('forum.index', ['sort' => $sort])); ?>">Semua topik</a><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><a class="rounded-lg px-3 py-2 text-sm font-semibold hover:bg-surface-soft <?php echo e($category === $item->slug ? 'bg-surface-soft text-primary' : 'text-text-secondary'); ?>" href="<?php echo e(route('forum.index', ['category' => $item->slug, 'sort' => $sort])); ?>"><?php echo e($item->name); ?></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></nav></aside>
<div class="min-w-0"><div class="mb-4 flex flex-wrap items-center justify-between gap-3"><h2 class="font-heading text-2xl font-bold text-text-primary">Diskusi terbaru</h2><div class="flex gap-2"><a class="btn-outline !min-h-9 !px-3 text-sm" href="<?php echo e(route('forum.index', array_filter(['category' => $category, 'sort' => 'latest']))); ?>" <?php if($sort === 'latest'): ?> aria-current="page" <?php endif; ?>>Terbaru</a><a class="btn-outline !min-h-9 !px-3 text-sm" href="<?php echo e(route('forum.index', array_filter(['category' => $category, 'sort' => 'discussed']))); ?>" <?php if($sort === 'discussed'): ?> aria-current="page" <?php endif; ?>>Paling dibahas</a></div></div><div class="space-y-3"><?php $__empty_1 = true; $__currentLoopData = $threads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thread): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><article class="card-interactive sipora-card p-5"><div class="flex flex-wrap items-center gap-2 text-xs font-bold text-text-muted"><?php if (isset($component)) { $__componentOriginale7f12d76f1f9891524de96ca143dbf2b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale7f12d76f1f9891524de96ca143dbf2b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forum.category-badge','data' => ['category' => $thread->category]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forum.category-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['category' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($thread->category)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale7f12d76f1f9891524de96ca143dbf2b)): ?>
<?php $attributes = $__attributesOriginale7f12d76f1f9891524de96ca143dbf2b; ?>
<?php unset($__attributesOriginale7f12d76f1f9891524de96ca143dbf2b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale7f12d76f1f9891524de96ca143dbf2b)): ?>
<?php $component = $__componentOriginale7f12d76f1f9891524de96ca143dbf2b; ?>
<?php unset($__componentOriginale7f12d76f1f9891524de96ca143dbf2b); ?>
<?php endif; ?><span aria-hidden="true">·</span><?php if (isset($component)) { $__componentOriginal525f984c6bf98c494ca20fdba7c09630 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal525f984c6bf98c494ca20fdba7c09630 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.author-identity','data' => ['author' => $thread->author]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.author-identity'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['author' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($thread->author)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal525f984c6bf98c494ca20fdba7c09630)): ?>
<?php $attributes = $__attributesOriginal525f984c6bf98c494ca20fdba7c09630; ?>
<?php unset($__attributesOriginal525f984c6bf98c494ca20fdba7c09630); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal525f984c6bf98c494ca20fdba7c09630)): ?>
<?php $component = $__componentOriginal525f984c6bf98c494ca20fdba7c09630; ?>
<?php unset($__componentOriginal525f984c6bf98c494ca20fdba7c09630); ?>
<?php endif; ?><span aria-hidden="true">·</span><time datetime="<?php echo e($thread->created_at->toIso8601String()); ?>"><?php echo e($thread->created_at->diffForHumans()); ?></time><?php if($thread->status === 'locked'): ?><?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['tone' => 'warning']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => 'warning']); ?>Terkunci <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?><?php endif; ?></div><h3 class="mt-2 text-lg font-extrabold text-text-primary"><a class="card-link hover:text-interactive" href="<?php echo e(route('forum.show', $thread)); ?>"><?php echo e($thread->title); ?></a></h3><p class="mt-2 line-clamp-2 text-sm leading-6 text-text-secondary"><?php echo e($thread->body); ?></p><p class="mt-3 text-xs font-semibold text-text-muted"><?php echo e($thread->replies_count); ?> balasan</p></article><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><div class="sipora-card p-8"><?php if (isset($component)) { $__componentOriginal3607a477fdef7402bc742abad5df9c51 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3607a477fdef7402bc742abad5df9c51 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.empty-state','data' => ['icon' => 'users','title' => 'Belum ada diskusi','description' => 'Mulai topik pertama pada kategori ini.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'users','title' => 'Belum ada diskusi','description' => 'Mulai topik pertama pada kategori ini.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $attributes = $__attributesOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__attributesOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $component = $__componentOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__componentOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?></div><?php endif; ?></div><div class="mt-6"><?php echo e($threads->links()); ?></div></div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/forum/index.blade.php ENDPATH**/ ?>