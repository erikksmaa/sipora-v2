<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['domain', 'identity']));

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

foreach (array_filter((['domain', 'identity']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php ($photos = \App\Support\Demo\DemoAssetManifest::gallery($domain, $identity)); ?>
<?php if($photos): ?>
<section class="public-panel mt-8">
    <h2 class="text-xl font-bold text-text-primary">Galeri dokumentasi</h2>
    <p class="mt-2 text-sm text-text-secondary">Dokumentasi fiktif untuk demo SIPORA.</p>
    <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <?php $__currentLoopData = $photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e($photo); ?>" target="_blank" rel="noopener"><img src="<?php echo e($photo); ?>" alt="Dokumentasi <?php echo e($identity); ?>, foto <?php echo e($loop->iteration); ?>" class="aspect-[4/3] w-full rounded-xl object-cover" loading="lazy"></a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</section>
<?php endif; ?>
<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/components/media/demo-gallery.blade.php ENDPATH**/ ?>