<?php $__env->startSection('title', $opportunity->title.' · SIPORA'); ?>
<?php $__env->startSection('meta_description', 'Informasi publik Opportunity '.$opportunity->title.' di SIPORA Kabupaten Pemalang.'); ?>
<?php $__env->startSection('canonical', route('opportunities.show', $opportunity)); ?>
<?php $__env->startSection('content'); ?>
<article class="bg-surface-muted"><?php if (isset($component)) { $__componentOriginal780d11c2fd95c33fc53cd02cfb936d73 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal780d11c2fd95c33fc53cd02cfb936d73 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.page-intro','data' => ['eyebrow' => 'Peluang eksternal terkurasi','title' => $opportunity->title,'description' => $opportunity->provider_name,'icon' => 'briefcase','variant' => 'opportunity','backHref' => route('opportunities.index'),'backLabel' => 'Opportunity']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.page-intro'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Peluang eksternal terkurasi','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($opportunity->title),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($opportunity->provider_name),'icon' => 'briefcase','variant' => 'opportunity','back-href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('opportunities.index')),'back-label' => 'Opportunity']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal780d11c2fd95c33fc53cd02cfb936d73)): ?>
<?php $attributes = $__attributesOriginal780d11c2fd95c33fc53cd02cfb936d73; ?>
<?php unset($__attributesOriginal780d11c2fd95c33fc53cd02cfb936d73); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal780d11c2fd95c33fc53cd02cfb936d73)): ?>
<?php $component = $__componentOriginal780d11c2fd95c33fc53cd02cfb936d73; ?>
<?php unset($__componentOriginal780d11c2fd95c33fc53cd02cfb936d73); ?>
<?php endif; ?><div class="mx-auto max-w-6xl"><?php if (isset($component)) { $__componentOriginala1039ef214ec526091346fea43c55ad6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala1039ef214ec526091346fea43c55ad6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.media.opportunity','data' => ['opportunity' => $opportunity]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('media.opportunity'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['opportunity' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($opportunity)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala1039ef214ec526091346fea43c55ad6)): ?>
<?php $attributes = $__attributesOriginala1039ef214ec526091346fea43c55ad6; ?>
<?php unset($__attributesOriginala1039ef214ec526091346fea43c55ad6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala1039ef214ec526091346fea43c55ad6)): ?>
<?php $component = $__componentOriginala1039ef214ec526091346fea43c55ad6; ?>
<?php unset($__componentOriginala1039ef214ec526091346fea43c55ad6); ?>
<?php endif; ?></div><div class="mx-auto grid max-w-6xl gap-8 px-5 py-10 lg:grid-cols-[minmax(0,1fr)_340px]"><div class="space-y-8"><section class="public-panel"><?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['tone' => 'orange','icon' => 'briefcase']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => 'orange','icon' => 'briefcase']); ?><?php echo e($opportunity->category->name); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?><h2 class="public-section-title mt-5">Tentang peluang</h2><p class="mt-4 whitespace-pre-line leading-8 text-slate-700"><?php echo e($opportunity->description); ?></p></section><section class="grid gap-4 sm:grid-cols-3"><?php if (isset($component)) { $__componentOriginal7b56645fd65e0290724a89216e5d8eee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7b56645fd65e0290724a89216e5d8eee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.stat-card','data' => ['icon' => 'map-pin','value' => $opportunity->location_text ?: $opportunity->administrativeArea?->name ?: 'Fleksibel','label' => 'Lokasi','tone' => 'blue']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'map-pin','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($opportunity->location_text ?: $opportunity->administrativeArea?->name ?: 'Fleksibel'),'label' => 'Lokasi','tone' => 'blue']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.stat-card','data' => ['icon' => 'calendar','value' => $opportunity->deadline_at?->translatedFormat('d M Y') ?? 'Tidak ditentukan','label' => 'Batas pendaftaran','tone' => 'orange']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'calendar','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($opportunity->deadline_at?->translatedFormat('d M Y') ?? 'Tidak ditentukan'),'label' => 'Batas pendaftaran','tone' => 'orange']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.stat-card','data' => ['icon' => 'clock','value' => $opportunity->starts_at?->translatedFormat('d M Y') ?? 'Fleksibel','label' => 'Mulai','tone' => 'green']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'clock','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($opportunity->starts_at?->translatedFormat('d M Y') ?? 'Fleksibel'),'label' => 'Mulai','tone' => 'green']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7b56645fd65e0290724a89216e5d8eee)): ?>
<?php $attributes = $__attributesOriginal7b56645fd65e0290724a89216e5d8eee; ?>
<?php unset($__attributesOriginal7b56645fd65e0290724a89216e5d8eee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7b56645fd65e0290724a89216e5d8eee)): ?>
<?php $component = $__componentOriginal7b56645fd65e0290724a89216e5d8eee; ?>
<?php unset($__componentOriginal7b56645fd65e0290724a89216e5d8eee); ?>
<?php endif; ?></section></div><aside class="space-y-5 lg:sticky lg:top-28 lg:self-start"><section class="rounded-2xl border border-orange-200 bg-orange-50 p-6"><p class="text-xs font-extrabold uppercase tracking-wider text-orange-700">Proses pendaftaran di situs penyedia</p><h2 class="mt-2 text-xl font-extrabold text-text-primary">Siap melihat detail lengkap?</h2><p class="mt-2 text-sm leading-6 text-slate-600">Ketentuan dan proses akhir berada pada penyedia Opportunity.</p><a class="landing-btn-primary mt-5 w-full" href="<?php echo e($opportunity->external_url); ?>" target="_blank" rel="noopener noreferrer external">Daftar / Lihat Informasi <?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'external-link','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'external-link','class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></a><?php if(auth()->guard()->check()): ?> <?php if (\Illuminate\Support\Facades\Blade::check('role', 'youth')): ?><form class="mt-3" method="POST" action="<?php echo e($bookmarked ? route('youth.opportunities.bookmark.destroy', $opportunity) : route('youth.opportunities.bookmark', $opportunity)); ?>"><?php echo csrf_field(); ?> <?php if($bookmarked): ?> <?php echo method_field('DELETE'); ?> <?php endif; ?><button class="btn-secondary w-full" type="submit"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
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
<?php endif; ?><?php echo e($bookmarked ? 'Hapus dari tersimpan' : 'Simpan Opportunity'); ?></button></form><?php endif; ?> <?php endif; ?></section><section class="public-panel"><p class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Penyedia</p><p class="mt-2 font-extrabold text-text-primary"><?php if($providerCommunity): ?><a class="hover:text-primary" href="<?php echo e(route('communities.show', $providerCommunity)); ?>"><?php echo e($opportunity->provider_name); ?></a><?php else: ?><?php echo e($opportunity->provider_name); ?><?php endif; ?></p><p class="mt-3 text-xs leading-5 text-slate-500">SIPORA menampilkan informasi yang dikurasi dan tidak mengelola proses pendaftaran eksternal.</p></section></aside></div></article>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/public/opportunities/show.blade.php ENDPATH**/ ?>