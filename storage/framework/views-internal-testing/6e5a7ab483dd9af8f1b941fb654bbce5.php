<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title' => 'Keputusan review', 'description' => 'Pilih keputusan berdasarkan bukti yang tersedia. Validasi status tetap dilakukan server.', 'action', 'notesName' => 'notes', 'notesLabel' => 'Catatan keputusan', 'approveLabel' => 'Setujui', 'approveValue' => 'approved']));

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

foreach (array_filter((['title' => 'Keputusan review', 'description' => 'Pilih keputusan berdasarkan bukti yang tersedia. Validasi status tetap dilakukan server.', 'action', 'notesName' => 'notes', 'notesLabel' => 'Catatan keputusan', 'approveLabel' => 'Setujui', 'approveValue' => 'approved']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<section x-data="{ decision: '<?php echo e(old('decision', $approveValue)); ?>' }" <?php echo e($attributes->class(['rounded-2xl border border-border bg-white p-5 shadow-sm sm:p-6'])); ?>>
    <h2 class="text-xl font-bold text-text-primary"><?php echo e($title); ?></h2><p class="mt-1 text-sm leading-6 text-text-secondary"><?php echo e($description); ?></p>
    <form class="mt-5 space-y-4" method="POST" action="<?php echo e($action); ?>"><?php echo csrf_field(); ?>
        <fieldset><legend class="field-label">Keputusan</legend><div class="grid gap-2 sm:grid-cols-3">
            <?php $__currentLoopData = [$approveValue => [$approveLabel, 'success'], 'revision' => ['Minta revisi', 'warning'], 'rejected' => ['Tolak', 'danger']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => [$label, $tone]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <label class="<?php echo \Illuminate\Support\Arr::toCssClasses(['cursor-pointer rounded-xl border p-3 text-sm font-bold transition', 'border-success bg-success-soft text-success' => $tone === 'success', 'border-warning bg-warning-soft text-[#8A5A0A]' => $tone === 'warning', 'border-danger bg-danger-soft text-danger' => $tone === 'danger']); ?>"><input class="mr-2" type="radio" name="decision" value="<?php echo e($value); ?>" x-model="decision"><?php echo e($label); ?></label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div></fieldset>
        <label><span class="field-label"><?php echo e($notesLabel); ?> <span x-show="decision !== '<?php echo e($approveValue); ?>'" class="text-danger">wajib untuk revisi/penolakan</span></span><textarea class="field min-h-28" name="<?php echo e($notesName); ?>" maxlength="3000" :required="decision !== '<?php echo e($approveValue); ?>'" placeholder="Tuliskan alasan yang spesifik dan dapat ditindaklanjuti."><?php echo e(old($notesName)); ?></textarea></label>
        <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get($notesName)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get($notesName))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?><button class="btn-primary" type="submit">Simpan keputusan</button>
    </form>
</section>
<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/components/workspace/decision-panel.blade.php ENDPATH**/ ?>