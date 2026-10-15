<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['author']));

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

foreach (array_filter((['author']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php
    $public = $author->profileVisibility?->is_profile_public && filled($author->profile?->public_slug) && filled($author->profile?->full_name);
    $name = \App\Support\ForumDisplayName::for($author);
    $photo = $public && $author->profileVisibility?->show_photo && filled($author->profile?->profile_photo_path);
    $initials = $public ? collect(explode(' ', $name))->filter()->take(2)->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))->implode('') : 'PS';
?>
<?php if($public): ?><a class="author-identity card-independent text-text-primary hover:text-interactive" href="<?php echo e(route('portfolio.show', $author->profile->public_slug)); ?>" aria-label="Lihat Portfolio <?php echo e($name); ?>"><?php else: ?><span class="author-identity text-text-secondary"><?php endif; ?>
    <span class="author-avatar" aria-hidden="true"><?php if($photo): ?><img data-media-fallback src="<?php echo e(route('portfolio.photo', $author->profile->public_slug)); ?>" alt="" loading="lazy"><?php else: ?><?php echo e($initials); ?><?php endif; ?></span>
    <span class="min-w-0 break-words font-semibold"><?php echo e($name); ?></span>
<?php if($public): ?></a><?php else: ?></span><?php endif; ?>
<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/components/public/author-identity.blade.php ENDPATH**/ ?>