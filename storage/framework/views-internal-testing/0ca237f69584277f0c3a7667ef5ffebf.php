
<?php $__env->startSection('title', $program->title.' · SIPORA'); ?>
<?php $__env->startSection('meta_description', str($program->description ?: 'Program pengembangan pemuda di SIPORA.')->squish()->limit(155)); ?>
<?php $__env->startSection('canonical', route('programs.show', $program)); ?>
<?php $__env->startSection('content'); ?>
<article class="bg-surface-muted"><?php if (isset($component)) { $__componentOriginal780d11c2fd95c33fc53cd02cfb936d73 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal780d11c2fd95c33fc53cd02cfb936d73 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.page-intro','data' => ['eyebrow' => 'Program '.e($program->execution_status === 'completed' ? 'selesai' : 'berjalan').'','title' => $program->title,'description' => 'Diselenggarakan oleh '.$program->organization->name,'icon' => 'notebook','variant' => 'program','backHref' => route('programs.index'),'backLabel' => 'Program']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.page-intro'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Program '.e($program->execution_status === 'completed' ? 'selesai' : 'berjalan').'','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($program->title),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Diselenggarakan oleh '.$program->organization->name),'icon' => 'notebook','variant' => 'program','back-href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('programs.index')),'back-label' => 'Program']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal780d11c2fd95c33fc53cd02cfb936d73)): ?>
<?php $attributes = $__attributesOriginal780d11c2fd95c33fc53cd02cfb936d73; ?>
<?php unset($__attributesOriginal780d11c2fd95c33fc53cd02cfb936d73); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal780d11c2fd95c33fc53cd02cfb936d73)): ?>
<?php $component = $__componentOriginal780d11c2fd95c33fc53cd02cfb936d73; ?>
<?php unset($__componentOriginal780d11c2fd95c33fc53cd02cfb936d73); ?>
<?php endif; ?><?php if (isset($component)) { $__componentOriginal6936dc520652c52aa45e41e68a9b74a8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6936dc520652c52aa45e41e68a9b74a8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.media.representative','data' => ['class' => 'mx-auto max-w-6xl','domain' => 'programs','identity' => $program->slug]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('media.representative'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mx-auto max-w-6xl','domain' => 'programs','identity' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($program->slug)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6936dc520652c52aa45e41e68a9b74a8)): ?>
<?php $attributes = $__attributesOriginal6936dc520652c52aa45e41e68a9b74a8; ?>
<?php unset($__attributesOriginal6936dc520652c52aa45e41e68a9b74a8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6936dc520652c52aa45e41e68a9b74a8)): ?>
<?php $component = $__componentOriginal6936dc520652c52aa45e41e68a9b74a8; ?>
<?php unset($__componentOriginal6936dc520652c52aa45e41e68a9b74a8); ?>
<?php endif; ?><div class="mx-auto max-w-6xl px-5 py-10"><section class="grid gap-4 sm:grid-cols-3"><?php if (isset($component)) { $__componentOriginal7b56645fd65e0290724a89216e5d8eee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7b56645fd65e0290724a89216e5d8eee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.stat-card','data' => ['icon' => 'badge-check','value' => $program->execution_status === 'completed' ? 'Selesai' : 'Berjalan','label' => 'Status Program','tone' => 'green']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'badge-check','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($program->execution_status === 'completed' ? 'Selesai' : 'Berjalan'),'label' => 'Status Program','tone' => 'green']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7b56645fd65e0290724a89216e5d8eee)): ?>
<?php $attributes = $__attributesOriginal7b56645fd65e0290724a89216e5d8eee; ?>
<?php unset($__attributesOriginal7b56645fd65e0290724a89216e5d8eee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7b56645fd65e0290724a89216e5d8eee)): ?>
<?php $component = $__componentOriginal7b56645fd65e0290724a89216e5d8eee; ?>
<?php unset($__componentOriginal7b56645fd65e0290724a89216e5d8eee); ?>
<?php endif; ?><?php if (isset($component)) { $__componentOriginal7b56645fd65e0290724a89216e5d8eee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7b56645fd65e0290724a89216e5d8eee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.stat-card','data' => ['icon' => 'calendar','value' => $program->start_date?->translatedFormat('d M Y') ?? 'Belum ditentukan','label' => 'Mulai','tone' => 'blue']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'calendar','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($program->start_date?->translatedFormat('d M Y') ?? 'Belum ditentukan'),'label' => 'Mulai','tone' => 'blue']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7b56645fd65e0290724a89216e5d8eee)): ?>
<?php $attributes = $__attributesOriginal7b56645fd65e0290724a89216e5d8eee; ?>
<?php unset($__attributesOriginal7b56645fd65e0290724a89216e5d8eee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7b56645fd65e0290724a89216e5d8eee)): ?>
<?php $component = $__componentOriginal7b56645fd65e0290724a89216e5d8eee; ?>
<?php unset($__componentOriginal7b56645fd65e0290724a89216e5d8eee); ?>
<?php endif; ?><?php if (isset($component)) { $__componentOriginal7b56645fd65e0290724a89216e5d8eee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7b56645fd65e0290724a89216e5d8eee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.stat-card','data' => ['icon' => 'calendar','value' => $program->end_date?->translatedFormat('d M Y') ?? 'Belum ditentukan','label' => 'Selesai','tone' => 'orange']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'calendar','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($program->end_date?->translatedFormat('d M Y') ?? 'Belum ditentukan'),'label' => 'Selesai','tone' => 'orange']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7b56645fd65e0290724a89216e5d8eee)): ?>
<?php $attributes = $__attributesOriginal7b56645fd65e0290724a89216e5d8eee; ?>
<?php unset($__attributesOriginal7b56645fd65e0290724a89216e5d8eee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7b56645fd65e0290724a89216e5d8eee)): ?>
<?php $component = $__componentOriginal7b56645fd65e0290724a89216e5d8eee; ?>
<?php unset($__componentOriginal7b56645fd65e0290724a89216e5d8eee); ?>
<?php endif; ?></section><div class="mt-8 grid gap-8 lg:grid-cols-[minmax(0,1fr)_320px]"><div class="space-y-8"><section class="public-panel"><?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['tone' => 'blue']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => 'blue']); ?><?php echo e($program->category->name); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?><h2 class="public-section-title mt-5">Tentang Program</h2><p class="mt-4 whitespace-pre-line leading-8 text-slate-700"><?php echo e($program->description ?: 'Deskripsi Program belum tersedia.'); ?></p><?php if($program->objectives): ?><h3 class="mt-7 text-lg font-extrabold text-text-primary">Tujuan</h3><p class="mt-2 whitespace-pre-line leading-7 text-slate-700"><?php echo e($program->objectives); ?></p><?php endif; ?></section><section><div class="flex flex-wrap items-end justify-between gap-3"><div><p class="text-xs font-extrabold uppercase tracking-wider text-orange-600">Rangkaian publik</p><h2 class="public-section-title mt-1">Activity dalam Program</h2></div></div><div class="mt-5 grid gap-5 md:grid-cols-2"><?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if (isset($component)) { $__componentOriginal446408be3aed06541d4520d25c2b2ddb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal446408be3aed06541d4520d25c2b2ddb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.discovery.activity-card','data' => ['activity' => $activity,'compact' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('discovery.activity-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['activity' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activity),'compact' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal446408be3aed06541d4520d25c2b2ddb)): ?>
<?php $attributes = $__attributesOriginal446408be3aed06541d4520d25c2b2ddb; ?>
<?php unset($__attributesOriginal446408be3aed06541d4520d25c2b2ddb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal446408be3aed06541d4520d25c2b2ddb)): ?>
<?php $component = $__componentOriginal446408be3aed06541d4520d25c2b2ddb; ?>
<?php unset($__componentOriginal446408be3aed06541d4520d25c2b2ddb); ?>
<?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if (isset($component)) { $__componentOriginal3607a477fdef7402bc742abad5df9c51 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3607a477fdef7402bc742abad5df9c51 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.empty-state','data' => ['class' => 'col-span-full','title' => 'Belum ada Activity publik','description' => 'Activity yang memenuhi kriteria publik akan tampil di sini.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'col-span-full','title' => 'Belum ada Activity publik','description' => 'Activity yang memenuhi kriteria publik akan tampil di sini.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $attributes = $__attributesOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__attributesOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $component = $__componentOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__componentOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?><?php endif; ?></div></section></div><aside class="lg:sticky lg:top-28 lg:self-start"><section class="public-panel"><p class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Community penyelenggara</p><div class="mt-4 flex items-center gap-3"><?php if (isset($component)) { $__componentOriginal07b43047b7122c3dd6f0c222a0b57c03 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal07b43047b7122c3dd6f0c222a0b57c03 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.media.community','data' => ['community' => $program->organization]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('media.community'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['community' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($program->organization)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal07b43047b7122c3dd6f0c222a0b57c03)): ?>
<?php $attributes = $__attributesOriginal07b43047b7122c3dd6f0c222a0b57c03; ?>
<?php unset($__attributesOriginal07b43047b7122c3dd6f0c222a0b57c03); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal07b43047b7122c3dd6f0c222a0b57c03)): ?>
<?php $component = $__componentOriginal07b43047b7122c3dd6f0c222a0b57c03; ?>
<?php unset($__componentOriginal07b43047b7122c3dd6f0c222a0b57c03); ?>
<?php endif; ?><div><strong class="block text-text-primary"><?php echo e($program->organization->name); ?></strong><span class="text-xs text-slate-500">Community aktif SIPORA</span></div></div><a class="public-link mt-4" href="<?php echo e(route('communities.show', $program->organization)); ?>">Lihat Community <?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
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
<?php endif; ?></a></section><?php echo $__env->make('public.programs._intake', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></aside></div></div></article>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/public/programs/show.blade.php ENDPATH**/ ?>