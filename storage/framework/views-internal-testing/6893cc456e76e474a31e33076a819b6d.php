<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title' => 'Riwayat review']));

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

foreach (array_filter((['title' => 'Riwayat review']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<section <?php echo e($attributes->class(['rounded-2xl border border-border bg-white p-5 shadow-sm sm:p-6'])); ?>><h2 class="text-xl font-bold text-text-primary"><?php echo e($title); ?></h2><div class="mt-4 border-l-2 border-primary-100 pl-5"><?php echo e($slot); ?></div></section>
<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/components/workspace/review-history.blade.php ENDPATH**/ ?>