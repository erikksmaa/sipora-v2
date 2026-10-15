<?php $__env->startSection('title', 'Sign in · SIPORA v2'); ?>
<?php $__env->startSection('content'); ?>
<section class="auth-card">
    <h1 class="mb-6 text-2xl font-bold">Sign in</h1>
    <?php if (isset($component)) { $__componentOriginalf356daf5bb8ad2b3823a397252417dee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf356daf5bb8ad2b3823a397252417dee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-form','data' => ['action' => route('login.store'),'recaptcha' => 'login']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('login.store')),'recaptcha' => 'login']); ?>
        <?php if (isset($component)) { $__componentOriginalc0e029268c67200fb6fd23d87d552d20 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc0e029268c67200fb6fd23d87d552d20 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-input','data' => ['name' => 'email','label' => 'Email','type' => 'email','autocomplete' => 'email']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'email','label' => 'Email','type' => 'email','autocomplete' => 'email']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-input','data' => ['name' => 'password','label' => 'Password','type' => 'password','autocomplete' => 'current-password']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'password','label' => 'Password','type' => 'password','autocomplete' => 'current-password']); ?>
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
        <label class="flex min-h-11 items-center gap-2"><input type="checkbox" name="remember" value="1"> Remember me</label>
         <?php $__env->slot('button', null, []); ?> Sign in <?php $__env->endSlot(); ?>
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
    <a class="mt-5 block underline" href="<?php echo e(route('password.request')); ?>">Forgot your password?</a>
    <?php echo $__env->make('auth.google-link', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/auth/login.blade.php ENDPATH**/ ?>