
<?php $__env->startSection('title', 'Direktori Pemuda · SIPORA'); ?>
<?php $__env->startSection('meta_description', 'Temukan Portfolio publik pemuda dalam ekosistem SIPORA Kabupaten Pemalang.'); ?>
<?php $__env->startSection('canonical', route('youth-directory.index')); ?>
<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginal780d11c2fd95c33fc53cd02cfb936d73 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal780d11c2fd95c33fc53cd02cfb936d73 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.page-intro','data' => ['eyebrow' => 'Direktori Publik','title' => 'Pemuda SIPORA','description' => 'Temukan profil dan rekam pengalaman pemuda yang memilih membagikan Portfolio-nya secara publik.','icon' => 'user','variant' => 'youth']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.page-intro'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Direktori Publik','title' => 'Pemuda SIPORA','description' => 'Temukan profil dan rekam pengalaman pemuda yang memilih membagikan Portfolio-nya secara publik.','icon' => 'user','variant' => 'youth']); ?>
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
    <section class="bg-surface-soft px-5 py-12">
        <div class="mx-auto max-w-7xl">
            <div class="mb-7 grid grid-cols-2 gap-3 xl:grid-cols-4">
                <?php $__currentLoopData = [['registered', 'Pemuda Terdaftar', 'users'], ['identity_verified', 'Identitas Terverifikasi', 'shield-check'], ['public_portfolio', 'Portfolio Publik', 'user'], ['activity_participated', 'Pernah Mengikuti Activity', 'activity']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$key, $label, $icon]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if (isset($component)) { $__componentOriginal7b56645fd65e0290724a89216e5d8eee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7b56645fd65e0290724a89216e5d8eee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.stat-card','data' => ['icon' => $icon,'value' => $youthStatistics[$key] === null ? '<5' : number_format($youthStatistics[$key]),'label' => $label,'tone' => 'blue']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($icon),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($youthStatistics[$key] === null ? '<5' : number_format($youthStatistics[$key])),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($label),'tone' => 'blue']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7b56645fd65e0290724a89216e5d8eee)): ?>
<?php $attributes = $__attributesOriginal7b56645fd65e0290724a89216e5d8eee; ?>
<?php unset($__attributesOriginal7b56645fd65e0290724a89216e5d8eee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7b56645fd65e0290724a89216e5d8eee)): ?>
<?php $component = $__componentOriginal7b56645fd65e0290724a89216e5d8eee; ?>
<?php unset($__componentOriginal7b56645fd65e0290724a89216e5d8eee); ?>
<?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <p class="mb-5 text-sm text-text-secondary">Berdasarkan pemuda yang terdaftar di SIPORA. Direktori ini hanya menampilkan Portfolio yang dipilih sebagai publik. <a class="font-bold text-primary underline" href="<?php echo e(route('statistics.index')); ?>">Lihat Statistik Pemuda</a></p>
            <form method="GET" action="<?php echo e(route('youth-directory.index')); ?>"
                class="grid gap-3 rounded-2xl border border-border bg-white p-4 shadow-xs md:grid-cols-[minmax(0,1fr)_260px_auto]"
                role="search">
                <label><span class="field-label">Cari nama, bio, atau skill publik</span><input class="field" type="search"
                        name="q" value="<?php echo e($query); ?>" maxlength="120" placeholder="Cari pemuda..."></label>
                <label><span class="field-label">Area domisili</span><select class="field" name="area">
                        <option value="">Semua area</option><?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($area); ?>" <?php if($selectedArea === $area): echo 'selected'; endif; ?>><?php echo e($area); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select></label>
                <div class="flex items-end gap-2"><button class="landing-btn-primary flex-1"
                        type="submit">Terapkan</button><?php if($query || $selectedArea): ?><a class="btn-secondary !min-h-11"
                        href="<?php echo e(route('youth-directory.index')); ?>">Reset</a><?php endif; ?></div>
            </form>
            <div class="mt-8 flex flex-wrap items-end justify-between gap-3">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-accent-600">Portfolio yang dibagikan</p>
                    <h2 class="public-section-title mt-1"><?php echo e($profiles->total()); ?> profil publik</h2>
                </div>
                <p class="max-w-xl text-sm text-text-secondary">Urutan nama bersifat netral dan bukan peringkat aktivitas
                    atau capaian.</p>
            </div>
            <div class="mt-7 public-card-grid">
                <?php $__empty_1 = true; $__currentLoopData = $profiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $profile): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if (isset($component)) { $__componentOriginal305b8019981f7a4dfb56f81b193d61d0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal305b8019981f7a4dfb56f81b193d61d0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.discovery.youth-card','data' => ['profile' => $profile]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('discovery.youth-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['profile' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($profile)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal305b8019981f7a4dfb56f81b193d61d0)): ?>
<?php $attributes = $__attributesOriginal305b8019981f7a4dfb56f81b193d61d0; ?>
<?php unset($__attributesOriginal305b8019981f7a4dfb56f81b193d61d0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal305b8019981f7a4dfb56f81b193d61d0)): ?>
<?php $component = $__componentOriginal305b8019981f7a4dfb56f81b193d61d0; ?>
<?php unset($__componentOriginal305b8019981f7a4dfb56f81b193d61d0); ?>
<?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if (isset($component)) { $__componentOriginal3607a477fdef7402bc742abad5df9c51 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3607a477fdef7402bc742abad5df9c51 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.empty-state','data' => ['class' => 'col-span-full','icon' => 'users','title' => 'Belum ada profil pemuda publik yang sesuai.','description' => 'Coba kata pencarian atau area lain.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'col-span-full','icon' => 'users','title' => 'Belum ada profil pemuda publik yang sesuai.','description' => 'Coba kata pencarian atau area lain.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $attributes = $__attributesOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__attributesOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $component = $__componentOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__componentOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?><?php endif; ?></div>
            <?php if($profiles->hasPages()): ?>
            <div class="mt-8"><?php echo e($profiles->links()); ?></div><?php endif; ?>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/public/youth/index.blade.php ENDPATH**/ ?>