<?php $__env->startSection('title', 'Kelola Password · SIPORA'); ?>
<?php $__env->startSection($forced || $workspace === 'admin' ? 'content' : 'verifier-content'); ?>
<section class="<?php echo e($forced ? 'auth-card' : 'sipora-card mx-auto max-w-2xl'); ?>">
    <h1 class="text-2xl font-bold text-text-primary"><?php echo e($forced ? 'Buat Password Baru' : 'Kelola Password'); ?></h1>
    <p class="mt-3 text-sm leading-6 text-text-secondary"><?php echo e($forced ? 'Anda wajib mengganti password sementara sebelum mengakses workspace. Tidak perlu memasukkan password sementara lagi.' : 'Ubah password akun Anda sendiri. Sesi lain akan diakhiri setelah password diperbarui.'); ?></p>
    <?php if($forced): ?><?php if (isset($component)) { $__componentOriginal451a3cd7c1c98cbcaf5f43140212c528 = $component; } ?>
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
<?php endif; ?><?php endif; ?>
    <form method="POST" action="<?php echo e(route($forced ? 'internal.password.update' : $workspace.'.account.password.update')); ?>" class="mt-6 space-y-5">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
        <?php if(!$forced && $hasPassword): ?><label class="block"><span class="field-label">Password saat ini</span><input class="field" type="password" name="current_password" autocomplete="current-password" maxlength="72" required></label><?php endif; ?>
        <label class="block"><span class="field-label">Password baru</span><input class="field" type="password" name="password" autocomplete="new-password" minlength="8" maxlength="72" required></label>
        <label class="block"><span class="field-label">Konfirmasi password baru</span><input class="field" type="password" name="password_confirmation" autocomplete="new-password" minlength="8" maxlength="72" required></label>
        <p class="text-xs text-text-secondary">Minimal 8 dan maksimal 72 karakter. Gunakan password yang berbeda dari sebelumnya.</p>
        <button class="btn-primary" type="submit">Simpan Password</button>
    </form>
    <?php if($forced): ?><form method="POST" action="<?php echo e(route('logout')); ?>" class="mt-5"><?php echo csrf_field(); ?><button class="btn-outline" type="submit">Keluar</button></form><?php endif; ?>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($forced ? 'layouts.auth' : ($workspace === 'admin' ? 'layouts.admin' : 'layouts.verifier'), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/government/account/password.blade.php ENDPATH**/ ?>