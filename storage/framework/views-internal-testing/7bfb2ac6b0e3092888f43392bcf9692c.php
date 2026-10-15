<?php $__env->startSection('title', 'Alur Aktivasi Akun · SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<?php
    $steps = [
        ['key' => 'email', 'title' => 'Verifikasi Akun / Email', 'description' => 'Pastikan alamat email akunmu sudah terverifikasi.'],
        ['key' => 'biodata', 'title' => 'Lengkapi Biodata', 'description' => 'Isi data pribadi, domisili, dan kontak untuk keperluan administrasi.'],
        ['key' => 'identity', 'title' => 'Verifikasi Identitas', 'description' => 'Kirim kartu identitas privat. Admin SIPORA akan meninjau pengajuanmu.'],
        ['key' => 'profile', 'title' => 'Lengkapi Profil', 'description' => 'Tulis perkenalan dan statusmu setelah identitas disetujui.'],
        ['key' => 'portfolio', 'title' => 'Lengkapi Portfolio Dasar', 'description' => 'Pilih minimal satu Keahlian dan satu Minat. Bukti pengalaman bersifat opsional.'],
        ['key' => 'complete', 'title' => 'Mulai Jelajahi & Berpartisipasi', 'description' => 'Akunmu siap untuk Activity, Community, dan peluang lainnya.'],
    ];
?>
<div class="mx-auto max-w-4xl space-y-6">
    <?php if (isset($component)) { $__componentOriginal8e0a96c106aaf9b655be7405167c4255 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8e0a96c106aaf9b655be7405167c4255 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.page-header','data' => ['eyebrow' => 'Perjalanan Youth','title' => 'Alur Aktivasi Akun','description' => 'Ikuti langkah yang terbuka. Progres dihitung dari data akunmu yang terbaru.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Perjalanan Youth','title' => 'Alur Aktivasi Akun','description' => 'Ikuti langkah yang terbuka. Progres dihitung dari data akunmu yang terbaru.']); ?>
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
    <div class="sipora-card flex items-center justify-between gap-4" role="status">
        <div><p class="text-xs font-extrabold uppercase tracking-widest text-interactive">Kesiapan Profil</p><p class="mt-1 text-xl font-extrabold"><?php echo e(count($onboarding['completedSteps'])); ?> dari 6 tahap selesai</p></div>
        <?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'badge-check','class' => 'size-8 text-interactive']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'badge-check','class' => 'size-8 text-interactive']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
    </div>
    <ol class="onboarding-timeline" aria-label="Tahap aktivasi akun">
        <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $done = in_array($step['key'], $onboarding['completedSteps'], true);
                $current = $step['key'] === $onboarding['currentStep'];
                $locked = in_array($step['key'], $onboarding['lockedSteps'], true);
            ?>
            <li class="onboarding-timeline-item" <?php if($current): ?> aria-current="step" <?php endif; ?>>
                <span class="onboarding-timeline-marker <?php echo e($done ? 'is-done' : ($current ? 'is-current' : '')); ?>" aria-hidden="true">
                    <?php if($done): ?><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'check','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'check','class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?><?php else: ?><?php echo e($index + 1); ?><?php endif; ?>
                </span>
                <section class="sipora-card <?php echo e($current ? 'onboarding-current' : 'onboarding-quiet'); ?>">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <h2 class="text-base font-extrabold"><?php echo e($step['title']); ?></h2>
                        <span class="text-xs font-bold uppercase tracking-wide <?php echo e($done ? 'text-success' : ($current ? 'text-interactive' : 'text-text-muted')); ?>"><?php echo e($done ? 'Selesai' : ($current ? 'Langkah aktif' : 'Terkunci')); ?></span>
                    </div>
                    <?php if($current): ?>
                        <p class="mt-3 text-sm text-text-secondary"><?php echo e($step['description']); ?></p>
                        <?php if($step['key'] === 'identity' && $onboarding['identityStatus'] === 'pending'): ?>
                            <p class="mt-2 text-sm text-text-secondary">Pengajuan sedang ditinjau Admin. Profil akan terbuka setelah disetujui.</p>
                        <?php elseif($step['key'] === 'identity' && $onboarding['identityStatus'] === 'revision'): ?>
                            <p class="mt-2 text-sm text-text-secondary">Admin meminta revisi. Buka catatan peninjauan, lalu kirim ulang dokumen.</p>
                        <?php elseif($step['key'] === 'identity' && $onboarding['identityStatus'] === 'rejected'): ?>
                            <p class="mt-2 text-sm text-text-secondary">Pengajuan ditolak. Akun tetap aktif; periksa alasannya sebelum mengajukan kembali.</p>
                        <?php endif; ?>
                        <a class="btn mt-4" href="<?php echo e(route($onboarding['nextAction']['route'])); ?>"><?php echo e($onboarding['nextAction']['label']); ?></a>
                    <?php elseif($locked): ?>
                        <p class="mt-1 text-xs text-text-muted">Selesaikan langkah sebelumnya untuk membuka tahap ini.</p>
                    <?php endif; ?>
                </section>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ol>
    <a class="btn-secondary" href="<?php echo e(route('home')); ?>">Jelajahi halaman publik</a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.youth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/youth/onboarding.blade.php ENDPATH**/ ?>