<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['status']));

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

foreach (array_filter((['status']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php
    $styles = [
        'planned' => 'bg-primary-50 text-primary border border-primary-200',
        'running' => 'bg-success-soft text-success border border-emerald-200',
        'completed' => 'bg-surface-soft text-text-secondary border border-border',
        'cancelled' => 'bg-danger-soft text-danger border border-red-200'
    ];
    $labels = ['planned' => 'Rencana', 'running' => 'Berjalan', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'];
?>
<span <?php echo e($attributes->class(['inline-flex rounded-full px-3 py-1 text-xs font-bold', $styles[$status] ?? 'bg-surface-soft text-text-secondary border border-border'])); ?>><?php echo e($labels[$status] ?? ucfirst($status)); ?></span>
<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/components/program/status-badge.blade.php ENDPATH**/ ?>