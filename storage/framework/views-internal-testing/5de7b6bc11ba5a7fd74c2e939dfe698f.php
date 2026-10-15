
<?php $__env->startSection('title', 'Lupa Password · SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<section class="auth-card">
    <h1 class="mb-3 text-2xl font-bold">Lupa password?</h1>
    <p class="mb-6 text-sm leading-6 text-text-secondary">Masukkan email akun SIPORA. Kami akan mengirim tautan untuk membuat password baru jika akun tersedia.</p>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-form','data' => ['action' => route('password.email'),'recaptcha' => 'forgot_password']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('password.email')),'recaptcha' => 'forgot_password']); ?>
        <?php if (isset($component)) { $__componentOriginalc0e029268c67200fb6fd23d87d552d20 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc0e029268c67200fb6fd23d87d552d20 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-input','data' => ['name' => 'email','label' => 'Email akun','type' => 'email','autocomplete' => 'email']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'email','label' => 'Email akun','type' => 'email','autocomplete' => 'email']); ?>
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
         <?php $__env->slot('button', null, []); ?> Kirim Tautan Reset <?php $__env->endSlot(); ?>
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
    <a href="<?php echo e(route('login')); ?>" class="btn-outline mt-6 w-full">Kembali ke Masuk</a>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>