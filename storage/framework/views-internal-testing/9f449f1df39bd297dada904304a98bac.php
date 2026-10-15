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
    [$label, $classes] = match($status) {
        'draft' => ['Draft', 'bg-slate-100 text-slate-700'],
        'submitted' => ['Diajukan', 'bg-amber-100 text-amber-800'],
        'under_review' => ['Sedang ditinjau', 'bg-blue-100 text-blue-800'],
        'revision' => ['Perlu revisi', 'bg-orange-100 text-orange-800'],
        'rejected' => ['Ditolak', 'bg-rose-100 text-rose-800'],
        'approved' => ['Disetujui', 'bg-emerald-100 text-emerald-800'],
        default => [str($status)->headline(), 'bg-slate-100 text-slate-700'],
    };
?>
<span <?php echo e($attributes->class("inline-flex rounded-full px-3 py-1 text-xs font-extrabold $classes")); ?>><?php echo e($label); ?></span>
<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/components/program/proposal-status-badge.blade.php ENDPATH**/ ?>