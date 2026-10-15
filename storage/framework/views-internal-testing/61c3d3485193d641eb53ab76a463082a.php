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
$styles = ['pending' => 'bg-amber-100 text-amber-800', 'completed' => 'bg-emerald-100 text-emerald-800', 'no_show' => 'bg-slate-200 text-slate-700'];
$labels = ['pending' => 'Menunggu penyelesaian', 'completed' => 'Selesai terverifikasi', 'no_show' => 'Tidak hadir'];
?>
<span <?php echo e($attributes->class(['inline-flex rounded-full px-2.5 py-1 text-xs font-bold', $styles[$status] ?? 'bg-slate-100 text-slate-700'])); ?>><?php echo e($labels[$status] ?? ucfirst($status)); ?></span>
<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/components/activity/completion-badge.blade.php ENDPATH**/ ?>