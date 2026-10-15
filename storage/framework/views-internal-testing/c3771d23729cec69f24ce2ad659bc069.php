<?php $__env->startSection('title', 'Privasi Portfolio · SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<?php
    $settings = [
        'is_profile_public' => ['Publikasikan Portfolio', 'Izinkan orang membuka halaman Portfolio publik Anda.'],
        'show_photo' => ['Foto profil', 'Tampilkan foto pada Portfolio publik.'],
        'show_bio' => ['Bio', 'Tampilkan ringkasan diri dan pekerjaan.'],
        'show_interests' => ['Minat', 'Tampilkan topik yang Anda minati.'],
        'show_skills' => ['Keahlian', 'Tampilkan keahlian yang Anda laporkan sendiri.'],
        'show_education' => ['Pendidikan', 'Tampilkan riwayat pendidikan.'],
        'show_organization_experience' => ['Pengalaman organisasi', 'Tampilkan pengalaman organisasi eksternal atau historis.'],
        'show_community_membership' => ['Community SIPORA', 'Tampilkan Community aktif beserta peran kontekstual Anda.'],
        'show_activity_passport' => ['Activity Passport', 'Tampilkan Activity SIPORA yang telah diselesaikan.'],
        'show_certificates' => ['Sertifikat', 'Tampilkan kredensial SIPORA yang telah diterbitkan.'],
        'show_achievements' => ['Prestasi', 'Tampilkan prestasi yang Anda laporkan.'],
    ];
?>
<div class="mx-auto max-w-5xl space-y-6">
    <?php if (isset($component)) { $__componentOriginal8e0a96c106aaf9b655be7405167c4255 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8e0a96c106aaf9b655be7405167c4255 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.page-header','data' => ['eyebrow' => 'Profile','title' => 'Kontrol Privasi','description' => 'Pilih bagian Portfolio yang dapat dilihat publik. Data identitas, kontak, dan dokumen tetap privat.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Profile','title' => 'Kontrol Privasi','description' => 'Pilih bagian Portfolio yang dapat dilihat publik. Data identitas, kontak, dan dokumen tetap privat.']); ?>
        <a class="btn-secondary" href="<?php echo e(route('youth.portfolio.show')); ?>">Pratinjau Portfolio</a>
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
    <form method="post" action="<?php echo e(route('youth.profile.visibility.update')); ?>" class="rounded-2xl border border-indigo-100 bg-white shadow-card">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
        <div class="divide-y divide-slate-100">
            <?php $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => [$label, $description]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <label class="flex min-h-20 cursor-pointer items-center justify-between gap-5 px-5 py-4 hover:bg-indigo-50/40 sm:px-6">
                    <span><strong class="block text-sm text-text-primary"><?php echo e($label); ?></strong><span class="mt-1 block text-xs leading-5 text-text-secondary"><?php echo e($description); ?></span></span>
                    <span class="relative shrink-0"><input type="hidden" name="<?php echo e($key); ?>" value="0"><input class="peer sr-only" type="checkbox" name="<?php echo e($key); ?>" value="1" <?php if($user->profileVisibility?->{$key} ?? true): echo 'checked'; endif; ?>><span class="block h-7 w-12 rounded-full bg-slate-300 transition peer-checked:bg-primary peer-focus-visible:ring-2 peer-focus-visible:ring-orange-400 peer-focus-visible:ring-offset-2"></span><span class="absolute left-1 top-1 size-5 rounded-full bg-white shadow transition peer-checked:translate-x-5"></span></span>
                </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-indigo-100 bg-surface-muted px-5 py-4 sm:px-6"><p class="text-xs text-slate-500">Perubahan berlaku pada halaman Portfolio publik.</p><button class="btn" type="submit">Simpan privasi</button></div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.youth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/youth/profile/privacy.blade.php ENDPATH**/ ?>