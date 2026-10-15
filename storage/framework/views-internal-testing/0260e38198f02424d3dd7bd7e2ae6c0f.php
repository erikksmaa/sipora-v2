<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['profile']));

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

foreach (array_filter((['profile']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php ($initials = collect(explode(' ', $profile['name']))->filter()->take(2)->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))->implode('')); ?>
<article <?php echo e($attributes->class('card-interactive group flex h-full flex-col overflow-hidden rounded-2xl border border-border bg-white shadow-xs transition duration-200 hover:-translate-y-0.5 hover:border-primary-200 hover:shadow-sm')); ?>>
    <div class="flex gap-4 p-5">
        <div class="grid size-20 shrink-0 place-items-center overflow-hidden rounded-2xl bg-primary-100 text-lg font-black text-primary" aria-hidden="<?php echo e($profile['photo_url'] ? 'true' : 'false'); ?>">
            <?php if($profile['photo_url']): ?><img data-media-fallback class="h-full w-full object-cover" src="<?php echo e($profile['photo_url']); ?>" alt="Foto <?php echo e($profile['name']); ?>" loading="lazy"><?php else: ?><span><?php echo e($initials ?: 'YP'); ?></span><?php endif; ?>
        </div>
        <div class="min-w-0 flex-1"><h2 class="text-lg font-bold leading-6 text-text-primary"><?php echo e($profile['name']); ?></h2><?php if($profile['headline']): ?><p class="mt-1 text-sm font-semibold text-primary"><?php echo e($profile['headline']); ?></p><?php endif; ?> <?php if($profile['domicile']): ?><p class="mt-1 flex items-center gap-1.5 text-xs text-text-muted"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'map-pin','class' => 'size-3.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'map-pin','class' => 'size-3.5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?><?php echo e($profile['domicile']); ?>, Pemalang</p><?php endif; ?></div>
    </div>
    <div class="flex flex-1 flex-col px-5 pb-5"><?php if($profile['bio']): ?><p class="line-clamp-3 text-sm leading-6 text-text-secondary"><?php echo e($profile['bio']); ?></p><?php endif; ?>
        <?php if($profile['skills']): ?><div class="mt-4 flex flex-wrap gap-2"><?php $__currentLoopData = $profile['skills']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><span class="rounded-full bg-primary-50 px-2.5 py-1 text-xs font-semibold text-primary"><?php echo e($skill); ?></span><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div><?php endif; ?>
        <?php if(!is_null($profile['activities_count']) || !is_null($profile['communities_count']) || !is_null($profile['certificates_count'])): ?><dl class="mt-5 flex flex-wrap gap-x-5 gap-y-2 border-t border-border pt-4 text-xs text-text-secondary"><?php if(!is_null($profile['activities_count'])): ?><div><dt class="sr-only">Activity terverifikasi</dt><dd><strong class="text-text-primary"><?php echo e($profile['activities_count']); ?></strong> Activity</dd></div><?php endif; ?> <?php if(!is_null($profile['communities_count'])): ?><div><dt class="sr-only">Community publik</dt><dd><strong class="text-text-primary"><?php echo e($profile['communities_count']); ?></strong> Community</dd></div><?php endif; ?> <?php if(!is_null($profile['certificates_count'])): ?><div><dt class="sr-only">Sertifikat terverifikasi</dt><dd><strong class="text-text-primary"><?php echo e($profile['certificates_count']); ?></strong> Sertifikat</dd></div><?php endif; ?></dl><?php endif; ?>
        <a href="<?php echo e($profile['url']); ?>" class="card-link mt-5 inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-primary-200 px-4 text-sm font-bold text-primary transition hover:bg-primary-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">Lihat Portfolio <?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'chevron-right','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-right','class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></a>
    </div>
</article>
<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/components/discovery/youth-card.blade.php ENDPATH**/ ?>