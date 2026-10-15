<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['eyebrow', 'title', 'description' => null, 'centered' => false]));

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

foreach (array_filter((['eyebrow', 'title', 'description' => null, 'centered' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="<?php echo \Illuminate\Support\Arr::toCssClasses(['mx-auto max-w-3xl text-center' => $centered]); ?>">
    <p class="text-xs font-bold uppercase tracking-[0.2em] text-accent-600"><?php echo e($eyebrow); ?></p>
    <h2 class="font-heading mt-3 text-3xl font-bold tracking-tight text-text-primary sm:text-4xl"><?php echo e($title); ?></h2>
    <?php if($description): ?>
    <p class="mt-4 text-base leading-7 text-text-secondary sm:text-lg"><?php echo e($description); ?></p><?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/components/public/section-heading.blade.php ENDPATH**/ ?>