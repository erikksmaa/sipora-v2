<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['decision']));

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

foreach (array_filter((['decision']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php
$labels=['approved'=>'Disetujui','revision'=>'Tindak lanjut','rejected'=>'Ditolak'];
$styles=['approved'=>'bg-emerald-100 text-emerald-800','revision'=>'bg-orange-100 text-orange-800','rejected'=>'bg-rose-100 text-rose-800'];
?>
<span <?php echo e($attributes->class(['inline-flex rounded-full px-3 py-1 text-xs font-extrabold', $styles[$decision] ?? 'bg-slate-100 text-slate-700'])); ?>><?php echo e($labels[$decision] ?? $decision); ?></span>
<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/components/program/evaluation-decision-badge.blade.php ENDPATH**/ ?>