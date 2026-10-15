<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['action', 'recaptcha' => null]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['action', 'recaptcha' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php ($useRecaptcha = $recaptcha && config('recaptcha.enabled')); ?>
<form method="post" action="<?php echo e($action); ?>" class="space-y-5" <?php if($recaptcha === 'login'): ?> data-confirm="false" <?php endif; ?>
    <?php if($useRecaptcha): ?>
        x-data="recaptchaForm(<?php echo \Illuminate\Support\Js::from(config('recaptcha.site_key'))->toHtml() ?>, <?php echo \Illuminate\Support\Js::from(config('recaptcha.actions.'.$recaptcha))->toHtml() ?>)"
        @submit.prevent="submit"
    <?php endif; ?>>
    <?php echo csrf_field(); ?>
    <?php echo e($slot); ?>

    <?php if($useRecaptcha): ?>
        <input type="hidden" name="recaptcha_token" x-ref="token">
        <p x-cloak x-show="error" x-text="error" role="alert" class="text-sm text-red-700"></p>
        <button type="submit" class="btn w-full" :disabled="busy" x-text="busy ? 'Verifying…' : <?php echo \Illuminate\Support\Js::from(trim((string) ($button ?? 'Continue')))->toHtml() ?>"><?php echo e($button ?? 'Continue'); ?></button>
        <p class="text-xs text-slate-500">Protected by reCAPTCHA. Google’s <a href="https://policies.google.com/privacy" class="underline">Privacy Policy</a> and <a href="https://policies.google.com/terms" class="underline">Terms of Service</a> apply.</p>
        <?php if (! $__env->hasRenderedOnce('844989c6-0ea2-4794-b7c8-04c66642e9b3')): $__env->markAsRenderedOnce('844989c6-0ea2-4794-b7c8-04c66642e9b3'); ?>
            <?php $__env->startPush('head'); ?>
                <?php if(config('recaptcha.site_key')): ?>
                    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo e(urlencode(config('recaptcha.site_key'))); ?>" defer></script>
                <?php endif; ?>
            <?php $__env->stopPush(); ?>
        <?php endif; ?>
    <?php else: ?>
        <button type="submit" class="btn w-full"><?php echo e($button ?? 'Continue'); ?></button>
    <?php endif; ?>
</form>
<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/components/auth-form.blade.php ENDPATH**/ ?>