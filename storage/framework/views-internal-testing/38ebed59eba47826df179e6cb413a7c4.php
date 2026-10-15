<?php $__env->startSection('title', 'Keamanan Akun · SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<div class="mx-auto max-w-2xl space-y-5">
    <?php if (isset($component)) { $__componentOriginal8e0a96c106aaf9b655be7405167c4255 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8e0a96c106aaf9b655be7405167c4255 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.page-header','data' => ['eyebrow' => 'Akun','title' => $hasPassword ? 'Ubah Password' : 'Buat Password','description' => 'Password lokal memberi pilihan lain untuk masuk ke akun SIPORA.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Akun','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($hasPassword ? 'Ubah Password' : 'Buat Password'),'description' => 'Password lokal memberi pilihan lain untuk masuk ke akun SIPORA.']); ?>
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
    <section class="sipora-card">
        <form method="post" action="<?php echo e(route('youth.account.security.update')); ?>" class="space-y-5">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <?php if($hasPassword): ?>
                <label><span class="field-label">Password saat ini</span><input class="field" type="password" name="current_password" autocomplete="current-password" required><?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
            <?php else: ?>
                <p class="rounded-xl border border-border bg-surface-soft p-4 text-sm text-text-secondary">Akun ini belum memiliki password lokal. Buat password tanpa perlu memasukkan password saat ini.</p>
            <?php endif; ?>
            <label><span class="field-label">Password baru</span><input class="field" type="password" name="password" autocomplete="new-password" minlength="8" required><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
            <label><span class="field-label">Ulangi password baru</span><input class="field" type="password" name="password_confirmation" autocomplete="new-password" minlength="8" required></label>
            <button class="btn" type="submit"><?php echo e($hasPassword ? 'Ubah Password' : 'Buat Password'); ?></button>
        </form>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.youth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/youth/account/security.blade.php ENDPATH**/ ?>