<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['eyebrow' => null, 'title', 'description' => null]));

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

foreach (array_filter((['eyebrow' => null, 'title', 'description' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<header <?php echo e($attributes->class(['flex flex-col justify-between gap-5 sm:flex-row sm:items-end'])); ?>><div><?php if($eyebrow): ?><p class="text-xs font-bold uppercase tracking-[0.16em] text-accent-600"><?php echo e($eyebrow); ?></p><?php endif; ?><h1 class="font-heading mt-1 text-[1.875rem] font-bold leading-tight tracking-[-0.025em] text-text-primary sm:text-[2rem]"><?php echo e($title); ?></h1><?php if($description): ?><p class="mt-2 max-w-3xl text-sm leading-6 text-text-secondary"><?php echo e($description); ?></p><?php endif; ?></div><?php if(isset($actions)): ?><div class="flex shrink-0 flex-wrap gap-2"><?php echo e($actions); ?></div><?php endif; ?></header>
<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/components/ui/page-header.blade.php ENDPATH**/ ?>