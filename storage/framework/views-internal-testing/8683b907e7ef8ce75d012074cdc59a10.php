<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['opportunity', 'compact' => false]));

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

foreach (array_filter((['opportunity', 'compact' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<div class="relative">
    <?php if (isset($component)) { $__componentOriginal6936dc520652c52aa45e41e68a9b74a8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6936dc520652c52aa45e41e68a9b74a8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.media.representative','data' => ['domain' => 'opportunities','identity' => $opportunity->slug,'compact' => $compact,'label' => 'Ilustrasi peluang pengembangan pemuda']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('media.representative'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['domain' => 'opportunities','identity' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($opportunity->slug),'compact' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($compact),'label' => 'Ilustrasi peluang pengembangan pemuda']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6936dc520652c52aa45e41e68a9b74a8)): ?>
<?php $attributes = $__attributesOriginal6936dc520652c52aa45e41e68a9b74a8; ?>
<?php unset($__attributesOriginal6936dc520652c52aa45e41e68a9b74a8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6936dc520652c52aa45e41e68a9b74a8)): ?>
<?php $component = $__componentOriginal6936dc520652c52aa45e41e68a9b74a8; ?>
<?php unset($__componentOriginal6936dc520652c52aa45e41e68a9b74a8); ?>
<?php endif; ?>
    <?php ($organizerLogo = \App\Support\Demo\DemoAssetManifest::publicAsset('opportunities', $opportunity->slug, 'organizer_logo')); ?>
    <?php if($organizerLogo): ?>
        <img src="<?php echo e($organizerLogo); ?>" alt="<?php echo e($opportunity->provider_name); ?>" class="absolute bottom-3 right-3 size-12 rounded-xl bg-white p-2" loading="lazy">
    <?php elseif($opportunity->organization && $opportunity->organization->review_status === 'approved' && $opportunity->organization->operational_status === 'active'): ?>
        <?php if (isset($component)) { $__componentOriginal07b43047b7122c3dd6f0c222a0b57c03 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal07b43047b7122c3dd6f0c222a0b57c03 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.media.community','data' => ['community' => $opportunity->organization,'class' => 'absolute bottom-3 right-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('media.community'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['community' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($opportunity->organization),'class' => 'absolute bottom-3 right-3']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal07b43047b7122c3dd6f0c222a0b57c03)): ?>
<?php $attributes = $__attributesOriginal07b43047b7122c3dd6f0c222a0b57c03; ?>
<?php unset($__attributesOriginal07b43047b7122c3dd6f0c222a0b57c03); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal07b43047b7122c3dd6f0c222a0b57c03)): ?>
<?php $component = $__componentOriginal07b43047b7122c3dd6f0c222a0b57c03; ?>
<?php unset($__componentOriginal07b43047b7122c3dd6f0c222a0b57c03); ?>
<?php endif; ?>
    <?php endif; ?>
</div>

<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/components/media/opportunity.blade.php ENDPATH**/ ?>