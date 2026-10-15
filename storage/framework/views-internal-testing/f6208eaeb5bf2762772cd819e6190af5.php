<?php $__env->startSection('title', 'Profil Saya · SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-6">

<?php if (isset($component)) { $__componentOriginal8e0a96c106aaf9b655be7405167c4255 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8e0a96c106aaf9b655be7405167c4255 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.page-header','data' => ['eyebrow' => 'Profil','title' => 'Profil Saya','description' => 'Kelola identitas publik, minat, keahlian, dan riwayat yang membentuk Portfolio SIPORA.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Profil','title' => 'Profil Saya','description' => 'Kelola identitas publik, minat, keahlian, dan riwayat yang membentuk Portfolio SIPORA.']); ?>
    <a class="btn-secondary" href="<?php echo e(route('youth.identity-verification')); ?>"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'shield-check','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'shield-check','class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?> Verifikasi identitas</a>
    <?php if($onboarding['profileComplete']): ?><a class="btn-secondary" href="<?php echo e(route('youth.profile.privacy')); ?>"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'shield-check','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'shield-check','class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?> Privasi</a><?php endif; ?>
    <a class="btn" href="<?php echo e(route('youth.profile.edit')); ?>"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'edit','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'edit','class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?> Edit profil</a>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8e0a96c106aaf9b655be7405167c4255)): ?>
<?php $attributes = $__attributesOriginal8e0a96c106aaf9b655be7405167c4255; ?>
<?php unset($__attributesOriginal8e0a96c106aaf9b655be7405167c4255); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e0a96c106aaf9b655be7405167c4255)): ?>
<?php $component = $__componentOriginal8e0a96c106aaf9b655be7405167c4255; ?>
<?php unset($__componentOriginal8e0a96c106aaf9b655be7405167c4255); ?>
<?php endif; ?>

<?php if(!$onboarding['profileComplete']): ?>
<section class="sipora-card"><h2 class="text-lg font-extrabold">Lengkapi Profil terlebih dahulu</h2><p class="mt-2 text-sm text-text-secondary">Isi status dan bio singkat untuk membuka Keahlian, Minat, serta bagian Portfolio lainnya.</p><a class="btn mt-4" href="<?php echo e(route('youth.profile.edit')); ?>">Lengkapi Profil</a></section>
<?php endif; ?>


<section class="sipora-card">
    <div class="flex flex-col gap-6 sm:flex-row sm:items-center">
        <div class="grid size-28 shrink-0 place-items-center overflow-hidden rounded-full bg-[#eef0ff] text-3xl font-black text-[#243378]">
            <?php if($user->profile?->profile_photo_path): ?>
                <img data-media-fallback class="size-full object-cover" src="<?php echo e(route('youth.profile.photo')); ?>" alt="Foto profil <?php echo e($user->profile->full_name); ?>">
            <?php else: ?>
                <?php echo e(str($user->profile?->full_name ?? $user->name)->substr(0,1)->upper()); ?>

            <?php endif; ?>
        </div>
        <div class="min-w-0 flex-1">
            <div class="flex flex-wrap items-center gap-2">
                <h2 class="text-3xl font-extrabold"><?php echo e($user->profile?->full_name ?? $user->name); ?></h2>
                <span class="rounded-full px-3 py-1 text-xs font-bold <?php echo e($user->identity?->verification_status === 'verified' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700'); ?>">
                    <?php echo e($user->identity?->verification_status === 'verified' ? 'Youth terverifikasi' : 'Belum terverifikasi'); ?>

                </span>
            </div>
            <p class="mt-2 text-slate-600">
                <?php echo e($user->profile?->occupation_title ?: 'Tambahkan pekerjaan atau institusi'); ?>

                <?php if($user->primaryDomicile): ?> · <?php echo e($user->primaryDomicile->administrativeArea->name); ?><?php endif; ?>
            </p>
            <div class="mt-4 flex flex-wrap gap-2">
                <?php $__currentLoopData = $user->interests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $interest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="interest-chip"><?php echo e($interest->name); ?></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($user->interests->isEmpty()): ?>
                    <a class="text-sm font-semibold text-[#3346a8]" href="<?php echo e(route('youth.interests.edit')); ?>">+ Tambah minat</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>


<?php if($onboarding['profileComplete']): ?><?php if (isset($component)) { $__componentOriginal7edd5ec0b67531e2ddd199c565f71133 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7edd5ec0b67531e2ddd199c565f71133 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.profile-completion','data' => ['completion' => $completion]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('profile-completion'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['completion' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($completion)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7edd5ec0b67531e2ddd199c565f71133)): ?>
<?php $attributes = $__attributesOriginal7edd5ec0b67531e2ddd199c565f71133; ?>
<?php unset($__attributesOriginal7edd5ec0b67531e2ddd199c565f71133); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7edd5ec0b67531e2ddd199c565f71133)): ?>
<?php $component = $__componentOriginal7edd5ec0b67531e2ddd199c565f71133; ?>
<?php unset($__componentOriginal7edd5ec0b67531e2ddd199c565f71133); ?>
<?php endif; ?><?php endif; ?>

<div>

    
    <section class="sipora-card">
        <h2 class="text-xl font-bold">Tentang saya</h2>
        <p class="mt-3 whitespace-pre-line text-slate-600"><?php echo e($user->profile?->bio ?: 'Bio belum diisi.'); ?></p>
        <dl class="mt-6 grid gap-4 text-sm sm:grid-cols-2">
            <div><dt class="font-semibold text-slate-500">Status</dt><dd class="mt-1"><?php echo e($user->profile?->occupation_status ?: 'Belum diisi'); ?></dd></div>
            <div><dt class="font-semibold text-slate-500">Kelayakan usia</dt><dd class="mt-1"><?php echo e($eligibility['eligible'] === true ? 'Segmen inti Youth' : ($eligibility['eligible'] === false ? 'Di luar segmen inti' : 'Belum dihitung')); ?></dd></div>
        </dl>
    </section>

</div>

<?php if($onboarding['profileComplete']): ?>
<section class="sipora-card">
    <h2 class="text-xl font-extrabold">Lanjutkan Portfolio</h2>
    <p class="mt-2 text-sm text-text-secondary">Kelola setiap catatan dan bukti di halaman Portfolio masing-masing.</p>
    <div class="mt-4 flex flex-wrap gap-2">
        <a class="btn-secondary" href="<?php echo e(route('youth.portfolio.show')); ?>">Portfolio Saya</a>
        <a class="btn-secondary" href="<?php echo e(route('youth.skills.edit')); ?>">Keahlian</a>
        <a class="btn-secondary" href="<?php echo e(route('youth.interests.edit')); ?>">Minat</a>
        <a class="btn-secondary" href="<?php echo e(route('youth.portfolio.sections.show', 'education')); ?>">Pendidikan</a>
        <a class="btn-secondary" href="<?php echo e(route('youth.portfolio.sections.show', 'experience')); ?>">Pengalaman</a>
        <a class="btn-secondary" href="<?php echo e(route('youth.portfolio.sections.show', 'achievements')); ?>">Prestasi</a>
        <a class="btn-secondary" href="<?php echo e(route('youth.portfolio.sections.show', 'training')); ?>">Pelatihan &amp; Sertifikasi</a>
    </div>
</section>
<?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.youth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/youth/profile/show.blade.php ENDPATH**/ ?>