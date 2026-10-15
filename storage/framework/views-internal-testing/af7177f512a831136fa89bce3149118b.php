<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['domain', 'identity' => '', 'label' => 'Ilustrasi representatif', 'compact' => false]));

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

foreach (array_filter((['domain', 'identity' => '', 'label' => 'Ilustrasi representatif', 'compact' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<figure <?php echo e($attributes->class(['media-representative', 'media-representative-compact' => $compact])); ?>>
    <img data-media-fallback src="<?php echo e(\App\Support\Media\RepresentativeMedia::url($domain, (string) $identity)); ?>" alt="<?php echo e($label); ?>" loading="lazy" decoding="async" width="1200" height="675">
    <?php if (! ($compact)): ?><figcaption><?php echo e(\App\Support\Demo\DemoAssetManifest::publicAsset($domain, $identity, $domain === 'opportunities' ? 'card' : 'cover') ? 'Dokumentasi demo SIPORA' : 'Ilustrasi representatif'); ?></figcaption><?php endif; ?>
</figure>

<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/components/media/representative.blade.php ENDPATH**/ ?>