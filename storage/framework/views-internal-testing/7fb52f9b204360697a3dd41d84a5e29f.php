<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['coverage', 'isAdmin' => false, 'threshold' => 5]));

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

foreach (array_filter((['coverage', 'isAdmin' => false, 'threshold' => 5]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
        <section class="eco-map-panel" aria-labelledby="eco-map-title">
            <div class="eco-map-toolbar"><fieldset class="eco-map-layers"><legend id="eco-map-title">Pilih lapisan peta</legend><div class="eco-map-layer-options"><?php $__currentLoopData = ['youth' => 'Youth', 'community' => 'Community', 'activity' => 'Activity', 'program' => 'Program']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><label><input type="radio" name="map-layer" value="<?php echo e($key); ?>" <?php if($key === 'youth'): echo 'checked'; endif; ?>><span><?php echo e($label); ?></span></label><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div></fieldset><label class="eco-map-district-picker">Kecamatan<select id="eco-map-district"><option value="">Semua kecamatan</option><?php $__currentLoopData = $coverage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($row['code']); ?>"><?php echo e($row['district']); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label></div>
            <div class="eco-map-layout"><div class="eco-map-canvas-wrap"><div id="eco-map" class="eco-map-canvas" data-geometry-url="<?php echo e(asset('data/pemalang-kecamatan.geojson')); ?>" role="region" aria-label="Peta interaktif 14 kecamatan Kabupaten Pemalang"></div><p id="eco-map-error" class="eco-map-error" role="status" hidden>Peta belum dapat dimuat. Tabel kecamatan di bawah tetap tersedia.</p><div class="eco-map-legend"><span><i class="eco-map-swatch eco-map-swatch-zero"></i>0</span><?php if (! ($isAdmin)): ?><span><i class="eco-map-swatch eco-map-swatch-hidden"></i>&lt;<?php echo e($threshold); ?></span><?php endif; ?><span><i class="eco-map-swatch eco-map-swatch-low"></i>Lebih sedikit</span><span><i class="eco-map-swatch eco-map-swatch-high"></i>Lebih banyak</span></div></div><aside class="eco-map-detail" aria-live="polite"><p class="eco-map-eyebrow">Rincian wilayah</p><h3 id="eco-map-selected-title">Semua kecamatan</h3><p id="eco-map-selected-copy">Pilih wilayah di peta atau daftar untuk melihat angka agregat.</p><dl id="eco-map-selected-values" class="eco-map-selected-values"></dl><button type="button" id="eco-map-reset" class="btn-secondary" hidden>Tampilkan semua</button></aside></div>
        </section>
<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/components/public/ecosystem-map-panel.blade.php ENDPATH**/ ?>