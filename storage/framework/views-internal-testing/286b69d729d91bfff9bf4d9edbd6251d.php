
<?php $__env->startSection('title', 'Pencarian · SIPORA'); ?>
<?php $__env->startSection('meta_description', 'Cari Activity, Community, Opportunity, Program, Forum, dan Portfolio Pemuda publik dalam ekosistem SIPORA Kabupaten Pemalang.'); ?>
<?php $__env->startSection('canonical', route('search.index')); ?>
<?php $__env->startSection('content'); ?>
<section class="public-page-intro public-page-intro--search"><div class="relative mx-auto max-w-5xl text-center"><p class="text-xs font-bold uppercase tracking-[.18em] text-accent-300">Pencarian SIPORA</p><h1 class="font-heading mt-2 text-4xl font-bold tracking-tight sm:text-5xl">Temukan ruang tumbuhmu</h1><p class="mx-auto mt-3 max-w-2xl text-white/85">Cari lintas Activity, Community, Opportunity, Program, dan Portfolio Pemuda publik.</p><form class="mx-auto mt-7 flex max-w-3xl flex-col gap-3 rounded-2xl border border-border bg-white p-3 shadow-md sm:flex-row" method="GET" action="<?php echo e(route('search.index')); ?>"><label class="relative flex-1"><span class="sr-only">Kata pencarian</span><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'search','class' => 'pointer-events-none absolute left-4 top-1/2 size-5 -translate-y-1/2 text-text-muted']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'search','class' => 'pointer-events-none absolute left-4 top-1/2 size-5 -translate-y-1/2 text-text-muted']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?><input class="landing-search h-12 pl-11" type="search" name="q" value="<?php echo e($query); ?>" maxlength="120" placeholder="Teknologi, olahraga, beasiswa, pemuda..."></label><button class="landing-btn-primary" type="submit">Cari sekarang</button></form></div></section>
<div class="bg-surface-soft px-5 py-12"><div class="mx-auto max-w-6xl">
<?php if($query === ''): ?>
    <?php if (isset($component)) { $__componentOriginal3607a477fdef7402bc742abad5df9c51 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3607a477fdef7402bc742abad5df9c51 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.empty-state','data' => ['icon' => 'search','title' => 'Mulai pencarianmu','description' => 'Masukkan kata kunci untuk menemukan berbagai ruang pengembangan pemuda di SIPORA.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'search','title' => 'Mulai pencarianmu','description' => 'Masukkan kata kunci untuk menemukan berbagai ruang pengembangan pemuda di SIPORA.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $attributes = $__attributesOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__attributesOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $component = $__componentOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__componentOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?>
<?php else: ?>
    <div class="mb-9"><p class="text-xs font-bold uppercase tracking-wider text-accent-600">Hasil pencarian</p><h2 class="public-section-title mt-1">Hasil untuk “<?php echo e($query); ?>”</h2></div>
    <div class="space-y-12">
    <?php $__currentLoopData = [['Activity',$activities,'activities.index','activity'],['Community',$communities,'communities.index','building'],['Opportunity',$opportunities,'opportunities.index','briefcase'],['Program',$programs,'programs.index','notebook'],['Pemuda',$youth,'youth-directory.index','users']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label,$items,$routeName,$icon]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <section><div class="flex items-center justify-between gap-3 border-b border-border pb-4"><div class="flex items-center gap-3"><span class="grid size-10 place-items-center rounded-xl bg-primary-50 text-primary shadow-xs"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => $icon,'class' => 'size-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($icon),'class' => 'size-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></span><h2 class="text-xl font-bold text-text-primary"><?php echo e($label); ?></h2></div><a class="public-link" href="<?php echo e(route($routeName,['q'=>$query])); ?>">Lihat semua <?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
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
<?php endif; ?></a></div>
        <div class="mt-5 grid gap-5 md:grid-cols-2"><?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?> <?php if($label==='Activity'): ?><?php if (isset($component)) { $__componentOriginal446408be3aed06541d4520d25c2b2ddb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal446408be3aed06541d4520d25c2b2ddb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.discovery.activity-card','data' => ['activity' => $item,'compact' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('discovery.activity-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['activity' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item),'compact' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal446408be3aed06541d4520d25c2b2ddb)): ?>
<?php $attributes = $__attributesOriginal446408be3aed06541d4520d25c2b2ddb; ?>
<?php unset($__attributesOriginal446408be3aed06541d4520d25c2b2ddb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal446408be3aed06541d4520d25c2b2ddb)): ?>
<?php $component = $__componentOriginal446408be3aed06541d4520d25c2b2ddb; ?>
<?php unset($__componentOriginal446408be3aed06541d4520d25c2b2ddb); ?>
<?php endif; ?><?php elseif($label==='Community'): ?><?php if (isset($component)) { $__componentOriginale1f00f9f06c1955a4a1e19b72a6c138b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale1f00f9f06c1955a4a1e19b72a6c138b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.discovery.community-card','data' => ['community' => $item,'compact' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('discovery.community-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['community' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item),'compact' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale1f00f9f06c1955a4a1e19b72a6c138b)): ?>
<?php $attributes = $__attributesOriginale1f00f9f06c1955a4a1e19b72a6c138b; ?>
<?php unset($__attributesOriginale1f00f9f06c1955a4a1e19b72a6c138b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale1f00f9f06c1955a4a1e19b72a6c138b)): ?>
<?php $component = $__componentOriginale1f00f9f06c1955a4a1e19b72a6c138b; ?>
<?php unset($__componentOriginale1f00f9f06c1955a4a1e19b72a6c138b); ?>
<?php endif; ?><?php elseif($label==='Opportunity'): ?><?php if (isset($component)) { $__componentOriginal31ba4aa0af51a52f1a7404d47e1cf707 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal31ba4aa0af51a52f1a7404d47e1cf707 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.discovery.opportunity-card','data' => ['opportunity' => $item,'compact' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('discovery.opportunity-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['opportunity' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item),'compact' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal31ba4aa0af51a52f1a7404d47e1cf707)): ?>
<?php $attributes = $__attributesOriginal31ba4aa0af51a52f1a7404d47e1cf707; ?>
<?php unset($__attributesOriginal31ba4aa0af51a52f1a7404d47e1cf707); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal31ba4aa0af51a52f1a7404d47e1cf707)): ?>
<?php $component = $__componentOriginal31ba4aa0af51a52f1a7404d47e1cf707; ?>
<?php unset($__componentOriginal31ba4aa0af51a52f1a7404d47e1cf707); ?>
<?php endif; ?><?php elseif($label==='Program'): ?><?php if (isset($component)) { $__componentOriginale07892df06fff4909c13fe4117821ea6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale07892df06fff4909c13fe4117821ea6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.discovery.program-card','data' => ['program' => $item,'compact' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('discovery.program-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['program' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item),'compact' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale07892df06fff4909c13fe4117821ea6)): ?>
<?php $attributes = $__attributesOriginale07892df06fff4909c13fe4117821ea6; ?>
<?php unset($__attributesOriginale07892df06fff4909c13fe4117821ea6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale07892df06fff4909c13fe4117821ea6)): ?>
<?php $component = $__componentOriginale07892df06fff4909c13fe4117821ea6; ?>
<?php unset($__componentOriginale07892df06fff4909c13fe4117821ea6); ?>
<?php endif; ?><?php else: ?><?php if (isset($component)) { $__componentOriginal305b8019981f7a4dfb56f81b193d61d0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal305b8019981f7a4dfb56f81b193d61d0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.discovery.youth-card','data' => ['profile' => $item]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('discovery.youth-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['profile' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal305b8019981f7a4dfb56f81b193d61d0)): ?>
<?php $attributes = $__attributesOriginal305b8019981f7a4dfb56f81b193d61d0; ?>
<?php unset($__attributesOriginal305b8019981f7a4dfb56f81b193d61d0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal305b8019981f7a4dfb56f81b193d61d0)): ?>
<?php $component = $__componentOriginal305b8019981f7a4dfb56f81b193d61d0; ?>
<?php unset($__componentOriginal305b8019981f7a4dfb56f81b193d61d0); ?>
<?php endif; ?><?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if (isset($component)) { $__componentOriginal3607a477fdef7402bc742abad5df9c51 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3607a477fdef7402bc742abad5df9c51 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.empty-state','data' => ['class' => 'col-span-full','title' => 'Tidak ada '.$label.' publik yang cocok.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'col-span-full','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Tidak ada '.$label.' publik yang cocok.')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $attributes = $__attributesOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__attributesOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $component = $__componentOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__componentOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?><?php endif; ?></div></section>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <section><div class="flex items-center justify-between gap-3 border-b border-border pb-4"><h2 class="text-xl font-bold text-text-primary">Forum</h2><a class="public-link" href="<?php echo e(route('forum.index')); ?>">Lihat diskusi <?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
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
<?php endif; ?></a></div><div class="mt-5 grid gap-4 md:grid-cols-2"><?php $__empty_1 = true; $__currentLoopData = $forumThreads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thread): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><article class="sipora-card p-5"><?php if (isset($component)) { $__componentOriginale7f12d76f1f9891524de96ca143dbf2b = $component; } ?>
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
<?php endif; ?><h3 class="mt-2 text-lg font-bold"><a href="<?php echo e(route('forum.show', $thread)); ?>"><?php echo e($thread->title); ?></a></h3><p class="mt-2 line-clamp-2 text-sm text-text-secondary"><?php echo e($thread->body); ?></p></article><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p class="text-sm text-text-muted">Tidak ada diskusi publik yang cocok.</p><?php endif; ?></div></section>
    </div>
<?php endif; ?>
</div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/public/search/index.blade.php ENDPATH**/ ?>