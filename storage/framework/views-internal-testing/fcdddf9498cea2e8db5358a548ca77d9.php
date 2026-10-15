<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['community', 'context' => 'public']));

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

foreach (array_filter((['community', 'context' => 'public']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php
    $public = $community->review_status === 'approved' && $community->operational_status === 'active';
    $url = $community->logo_path && ($public || $context === 'manager')
        ? route($context === 'manager' ? 'youth.communities.logo' : 'communities.logo', $community) : null;
?>
<span <?php echo e($attributes->class(['media-community', 'community-logo-surface'])); ?>>
    <?php if($url): ?><img data-media-fallback data-media-initials="<?php echo e(mb_strtoupper(mb_substr($community->name, 0, 1))); ?>" src="<?php echo e($url); ?>" alt="Logo <?php echo e($community->name); ?>" loading="lazy" decoding="async">
    <?php else: ?><span aria-label="Logo belum tersedia"><?php echo e(mb_strtoupper(mb_substr($community->name, 0, 1))); ?></span><?php endif; ?>
</span>

<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/components/media/community.blade.php ENDPATH**/ ?>