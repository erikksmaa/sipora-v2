
<?php $__env->startSection('title', 'Jelajahi Activity · SIPORA'); ?>
<?php $__env->startSection('meta_description', 'Temukan Activity terverifikasi yang dipublikasikan Community di SIPORA Kabupaten Pemalang.'); ?>
<?php $__env->startSection('canonical', route('activities.index')); ?>
<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginal780d11c2fd95c33fc53cd02cfb936d73 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal780d11c2fd95c33fc53cd02cfb936d73 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.page-intro','data' => ['eyebrow' => 'Eksplorasi publik','title' => 'Temukan Activity untuk bertumbuh','description' => 'Cari kegiatan terverifikasi yang telah dipublikasikan oleh Community di SIPORA.','icon' => 'activity','variant' => 'activity']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.page-intro'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Eksplorasi publik','title' => 'Temukan Activity untuk bertumbuh','description' => 'Cari kegiatan terverifikasi yang telah dipublikasikan oleh Community di SIPORA.','icon' => 'activity','variant' => 'activity']); ?>
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
<div class="bg-surface-soft px-5 py-10"><div class="mx-auto max-w-7xl">
    <?php if (isset($component)) { $__componentOriginal9bcf51b467deef585b101ab8171ad7ba = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9bcf51b467deef585b101ab8171ad7ba = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.filter-panel','data' => ['action' => route('activities.index'),'reset' => route('activities.index'),'label' => 'Filter Activity']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.filter-panel'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('activities.index')),'reset' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('activities.index')),'label' => 'Filter Activity']); ?><div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4"><label class="xl:col-span-2"><span class="label">Cari Activity</span><input class="field" type="search" name="q" value="<?php echo e($filters['q']); ?>" maxlength="120" placeholder="Judul, Community, kategori, atau lokasi"></label><label><span class="label">Kategori</span><select class="field" name="category"><option value="">Semua kategori</option><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($category->slug); ?>" <?php if($filters['category']===$category->slug): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label><label><span class="label">Kecamatan</span><select class="field" name="location"><option value="">Semua lokasi</option><?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($location->code); ?>" <?php if($filters['location']===$location->code): echo 'selected'; endif; ?>><?php echo e($location->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label><label><span class="label">Mode</span><select class="field" name="mode"><option value="">Semua mode</option><?php $__currentLoopData = ['offline'=>'Offline','online'=>'Online','hybrid'=>'Hybrid']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($value); ?>" <?php if($filters['mode']===$value): echo 'selected'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label><label><span class="label">Pendaftaran</span><select class="field" name="registration"><option value="">Semua mekanisme</option><option value="open" <?php if($filters['registration']==='open'): echo 'selected'; endif; ?>>Langsung diterima</option><option value="approval_required" <?php if($filters['registration']==='approval_required'): echo 'selected'; endif; ?>>Memerlukan persetujuan</option></select></label><label><span class="label">Urutkan</span><select class="field" name="sort"><option value="upcoming" <?php if($filters['sort']==='upcoming'): echo 'selected'; endif; ?>>Paling dekat</option><option value="newest" <?php if($filters['sort']==='newest'): echo 'selected'; endif; ?>>Terbaru dipublikasikan</option><option value="closing" <?php if($filters['sort']==='closing'): echo 'selected'; endif; ?>>Pendaftaran segera tutup</option></select></label><label class="flex min-h-11 items-center gap-3"><input class="size-5 rounded border-border text-primary focus:ring-primary" type="checkbox" name="availability" value="open" <?php if($filters['availability']==='open'): echo 'checked'; endif; ?>><span class="text-sm font-semibold text-text-secondary">Pendaftaran tersedia</span></label></div> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9bcf51b467deef585b101ab8171ad7ba)): ?>
<?php $attributes = $__attributesOriginal9bcf51b467deef585b101ab8171ad7ba; ?>
<?php unset($__attributesOriginal9bcf51b467deef585b101ab8171ad7ba); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9bcf51b467deef585b101ab8171ad7ba)): ?>
<?php $component = $__componentOriginal9bcf51b467deef585b101ab8171ad7ba; ?>
<?php unset($__componentOriginal9bcf51b467deef585b101ab8171ad7ba); ?>
<?php endif; ?>
    <div class="mt-9 flex flex-wrap items-end justify-between gap-3"><div><p class="text-xs font-bold uppercase tracking-wider text-accent-600">Activity publik</p><h2 class="mt-1 text-2xl font-bold text-text-primary"><?php echo e($activities->total()); ?> hasil ditemukan</h2></div><a class="public-link" href="<?php echo e(route('communities.index')); ?>">Jelajahi Community <?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
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
    <div class="mt-6 public-card-grid"><?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if (isset($component)) { $__componentOriginal446408be3aed06541d4520d25c2b2ddb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal446408be3aed06541d4520d25c2b2ddb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.discovery.activity-card','data' => ['activity' => $activity]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('discovery.activity-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['activity' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activity)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.empty-state','data' => ['class' => 'col-span-full','title' => 'Tidak ada Activity yang cocok','description' => 'Ubah kata pencarian atau longgarkan filter untuk melihat pilihan lain.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'col-span-full','title' => 'Tidak ada Activity yang cocok','description' => 'Ubah kata pencarian atau longgarkan filter untuk melihat pilihan lain.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $attributes = $__attributesOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__attributesOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $component = $__componentOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__componentOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?><?php endif; ?></div><?php if($activities->hasPages()): ?><div class="mt-10"><?php echo e($activities->links()); ?></div><?php endif; ?>
</div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/public/activities/index.blade.php ENDPATH**/ ?>