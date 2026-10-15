
<?php $__env->startSection('title', 'Reset Password · SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<section class="auth-card">
    <h1 class="mb-3 text-2xl font-bold">Buat password baru</h1>
    <p class="mb-6 text-sm leading-6 text-text-secondary">Gunakan minimal 8 karakter, maksimal 72 karakter, dan isi konfirmasi yang sama. Tautan reset berlaku selama 60 menit dan hanya dapat digunakan sekali.</p>
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
    <?php if (isset($component)) { $__componentOriginalf356daf5bb8ad2b3823a397252417dee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf356daf5bb8ad2b3823a397252417dee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-form','data' => ['action' => route('password.update')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('password.update'))]); ?>
        <input type="hidden" name="token" value="<?php echo e($token); ?>">
        <?php if (isset($component)) { $__componentOriginalc0e029268c67200fb6fd23d87d552d20 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc0e029268c67200fb6fd23d87d552d20 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-input','data' => ['name' => 'email','label' => 'Email akun','type' => 'email','autocomplete' => 'email','value' => old('email', request('email'))]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'email','label' => 'Email akun','type' => 'email','autocomplete' => 'email','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('email', request('email')))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc0e029268c67200fb6fd23d87d552d20)): ?>
<?php $attributes = $__attributesOriginalc0e029268c67200fb6fd23d87d552d20; ?>
<?php unset($__attributesOriginalc0e029268c67200fb6fd23d87d552d20); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc0e029268c67200fb6fd23d87d552d20)): ?>
<?php $component = $__componentOriginalc0e029268c67200fb6fd23d87d552d20; ?>
<?php unset($__componentOriginalc0e029268c67200fb6fd23d87d552d20); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginalc0e029268c67200fb6fd23d87d552d20 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc0e029268c67200fb6fd23d87d552d20 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-input','data' => ['name' => 'password','label' => 'Password baru','type' => 'password','autocomplete' => 'new-password']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'password','label' => 'Password baru','type' => 'password','autocomplete' => 'new-password']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc0e029268c67200fb6fd23d87d552d20)): ?>
<?php $attributes = $__attributesOriginalc0e029268c67200fb6fd23d87d552d20; ?>
<?php unset($__attributesOriginalc0e029268c67200fb6fd23d87d552d20); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc0e029268c67200fb6fd23d87d552d20)): ?>
<?php $component = $__componentOriginalc0e029268c67200fb6fd23d87d552d20; ?>
<?php unset($__componentOriginalc0e029268c67200fb6fd23d87d552d20); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginalc0e029268c67200fb6fd23d87d552d20 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc0e029268c67200fb6fd23d87d552d20 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-input','data' => ['name' => 'password_confirmation','label' => 'Konfirmasi password baru','type' => 'password','autocomplete' => 'new-password']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'password_confirmation','label' => 'Konfirmasi password baru','type' => 'password','autocomplete' => 'new-password']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc0e029268c67200fb6fd23d87d552d20)): ?>
<?php $attributes = $__attributesOriginalc0e029268c67200fb6fd23d87d552d20; ?>
<?php unset($__attributesOriginalc0e029268c67200fb6fd23d87d552d20); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc0e029268c67200fb6fd23d87d552d20)): ?>
<?php $component = $__componentOriginalc0e029268c67200fb6fd23d87d552d20; ?>
<?php unset($__componentOriginalc0e029268c67200fb6fd23d87d552d20); ?>
<?php endif; ?>
         <?php $__env->slot('button', null, []); ?> Simpan Password Baru <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf356daf5bb8ad2b3823a397252417dee)): ?>
<?php $attributes = $__attributesOriginalf356daf5bb8ad2b3823a397252417dee; ?>
<?php unset($__attributesOriginalf356daf5bb8ad2b3823a397252417dee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf356daf5bb8ad2b3823a397252417dee)): ?>
<?php $component = $__componentOriginalf356daf5bb8ad2b3823a397252417dee; ?>
<?php unset($__componentOriginalf356daf5bb8ad2b3823a397252417dee); ?>
<?php endif; ?>
    <a href="<?php echo e(route('password.request')); ?>" class="btn-outline mt-6 w-full">Minta tautan reset baru</a>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/auth/reset-password.blade.php ENDPATH**/ ?>