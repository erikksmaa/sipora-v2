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
<header <?php echo e($attributes->class(['flex flex-col justify-between gap-4 border-b border-indigo-100 pb-5 sm:flex-row sm:items-end'])); ?>>
    <div class="min-w-0">
        <?php if($eyebrow): ?><p class="text-xs font-extrabold uppercase tracking-[.16em] text-interactive"><?php echo e($eyebrow); ?></p><?php endif; ?>
        <h1 class="font-heading mt-1 text-3xl font-bold tracking-tight text-text-primary"><?php echo e($title); ?></h1>
        <?php if($description): ?><p class="mt-2 max-w-3xl text-sm leading-6 text-text-secondary"><?php echo e($description); ?></p><?php endif; ?>
    </div>
    <?php if(trim((string) $slot)): ?><div class="flex shrink-0 flex-wrap gap-2"><?php echo e($slot); ?></div><?php endif; ?>
</header>
<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/components/workspace/page-header.blade.php ENDPATH**/ ?>