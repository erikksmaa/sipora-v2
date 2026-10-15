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
    $label = ['pending'=>'Menunggu review','accepted'=>'Diterima','rejected'=>'Ditolak','cancelled'=>'Dibatalkan'][$status] ?? $status;
    $class = match($status) {
        'accepted' => 'bg-[#E9F7F0] text-[#16865C] border border-emerald-200',
        'pending' => 'bg-[#FFF6DF] text-[#C2410C] border border-orange-200',
        'rejected' => 'bg-[#FDECEC] text-[#D64545] border border-red-200',
        default => 'bg-surface-soft text-text-secondary border border-border'
    };
?>
<span <?php echo e($attributes->class("inline-flex rounded-full px-2.5 py-1 text-xs font-bold $class")); ?>><?php echo e($label); ?></span>
<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/components/activity/registration-badge.blade.php ENDPATH**/ ?>