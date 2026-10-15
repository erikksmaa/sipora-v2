
<?php $__env->startSection('title', 'Verifikasi Email · SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<section class="auth-card">
    <p class="mb-3 text-xs font-bold uppercase tracking-wide text-text-secondary">Aktivasi akun</p>
    <h1 class="mb-4 text-2xl font-bold">Verifikasi email Anda</h1>
    <?php if (isset($component)) { $__componentOriginal451a3cd7c1c98cbcaf5f43140212c528 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal451a3cd7c1c98cbcaf5f43140212c528 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-flow-feedback','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-flow-feedback'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal451a3cd7c1c98cbcaf5f43140212c528)): ?>
<?php $attributes = $__attributesOriginal451a3cd7c1c98cbcaf5f43140212c528; ?>
<?php unset($__attributesOriginal451a3cd7c1c98cbcaf5f43140212c528); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal451a3cd7c1c98cbcaf5f43140212c528)): ?>
<?php $component = $__componentOriginal451a3cd7c1c98cbcaf5f43140212c528; ?>
<?php unset($__componentOriginal451a3cd7c1c98cbcaf5f43140212c528); ?>
<?php endif; ?>
    <p class="mb-3 text-sm leading-6 text-text-secondary">Periksa tautan verifikasi yang dikirim ke <strong class="break-all text-text-primary"><?php echo e(auth()->user()->email); ?></strong>. Tautan berlaku selama 60 menit. Periksa juga folder spam.</p>
    <p class="mb-6 text-sm leading-6 text-text-secondary">Verifikasi email mengonfirmasi akses akun. Verifikasi identitas SIPORA dilakukan secara terpisah melalui peninjauan dokumen.</p>
    <form action="<?php echo e(route('verification.send')); ?>" method="post" x-data="{ busy: false }" @submit="busy = true" data-confirm="false">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn-accent w-full" :disabled="busy" x-text="busy ? 'Mengirim…' : 'Kirim ulang email verifikasi'">Kirim ulang email verifikasi</button>
    </form>
    <p class="mt-4 text-xs leading-5 text-text-muted">Jika belum menerima email, kirim ulang tautan. Pengiriman dibatasi untuk mencegah spam.</p>
    <form action="<?php echo e(route('logout')); ?>" method="post" class="mt-6" data-confirm="false"><?php echo csrf_field(); ?><button class="btn-outline w-full" type="submit">Keluar / gunakan akun lain</button></form>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/auth/verify-email.blade.php ENDPATH**/ ?>