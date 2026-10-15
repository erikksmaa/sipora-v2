<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['activity', 'context' => 'public']));

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

foreach (array_filter((['activity', 'context' => 'public']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php
    $public = $activity->review_status === 'approved' && $activity->publication_status === 'published'
        && $activity->organization?->review_status === 'approved' && $activity->organization?->operational_status === 'active';
    $url = null;
    if ($activity->poster_path && ($public || in_array($context, ['manager', 'verifier'], true))) {
        $url = match ($context) {
            'manager' => route('manager.activities.poster', [$activity->organization, $activity]),
            'verifier' => route('verifier.activity-verifications.poster', $activity),
            default => route('activities.poster', $activity),
        };
    }
?>
<div <?php echo e($attributes->class(['media-activity'])); ?>>
    <?php if($url): ?><img data-media-fallback src="<?php echo e($url); ?>" alt="Poster <?php echo e($activity->title); ?>" loading="lazy" decoding="async" width="1200" height="675">
    <?php else: ?><?php if (isset($component)) { $__componentOriginal6936dc520652c52aa45e41e68a9b74a8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6936dc520652c52aa45e41e68a9b74a8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.media.representative','data' => ['domain' => 'activities','identity' => $activity->slug,'compact' => true,'label' => 'Ilustrasi kegiatan kepemudaan']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('media.representative'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['domain' => 'activities','identity' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activity->slug),'compact' => true,'label' => 'Ilustrasi kegiatan kepemudaan']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6936dc520652c52aa45e41e68a9b74a8)): ?>
<?php $attributes = $__attributesOriginal6936dc520652c52aa45e41e68a9b74a8; ?>
<?php unset($__attributesOriginal6936dc520652c52aa45e41e68a9b74a8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6936dc520652c52aa45e41e68a9b74a8)): ?>
<?php $component = $__componentOriginal6936dc520652c52aa45e41e68a9b74a8; ?>
<?php unset($__componentOriginal6936dc520652c52aa45e41e68a9b74a8); ?>
<?php endif; ?><?php endif; ?>
</div>

<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/components/media/activity.blade.php ENDPATH**/ ?>