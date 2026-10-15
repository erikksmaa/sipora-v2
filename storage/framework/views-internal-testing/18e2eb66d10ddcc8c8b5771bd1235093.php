<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['completion']));

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

foreach (array_filter((['completion']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<section <?php echo e($attributes->class(['sipora-card'])); ?> aria-labelledby="profile-completion-title"><div class="flex flex-col gap-5 sm:flex-row sm:items-center">
<div class="grid size-24 shrink-0 place-items-center rounded-full p-2" style="background: conic-gradient(#f59e0b <?php echo e($completion['percentage']); ?>%, #e8eaf4 0)"><div class="grid size-full place-items-center rounded-full bg-white"><span class="text-2xl font-extrabold text-[#243378]"><?php echo e($completion['percentage']); ?>%</span></div></div>
<div class="min-w-0 flex-1"><p class="text-xs font-bold uppercase tracking-wider text-orange-600">Kelengkapan profil</p><h2 id="profile-completion-title" class="mt-1 text-xl font-bold"><?php echo e($completion['percentage'] === 100 ? 'Profil dasarmu lengkap' : 'Lengkapi profilmu'); ?></h2><p class="mt-1 text-sm text-slate-600">Kelengkapan membantu SIPORA memberi panduan yang relevan. Nilai ini bukan peringkat.</p></div>
<?php if($completion['percentage'] < 100): ?><a class="btn shrink-0" href="<?php echo e(route('youth.onboarding')); ?>">Lanjutkan</a><?php endif; ?></div></section>
<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/components/profile-completion.blade.php ENDPATH**/ ?>