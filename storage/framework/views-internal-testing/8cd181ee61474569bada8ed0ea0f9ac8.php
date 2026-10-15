<article class="sipora-card space-y-4 p-5 sm:p-6">
<div class="flex flex-wrap items-start justify-between gap-3"><div>
<?php if($post->organization): ?><a class="font-bold text-text-primary" href="<?php echo e(route('communities.show', $post->organization)); ?>"><?php echo e($post->organization->name); ?></a><p class="text-xs text-text-muted">Update Community</p>
<?php else: ?> <?php if (isset($component)) { $__componentOriginal525f984c6bf98c494ca20fdba7c09630 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal525f984c6bf98c494ca20fdba7c09630 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.author-identity','data' => ['author' => $post->author]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.author-identity'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['author' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post->author)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal525f984c6bf98c494ca20fdba7c09630)): ?>
<?php $attributes = $__attributesOriginal525f984c6bf98c494ca20fdba7c09630; ?>
<?php unset($__attributesOriginal525f984c6bf98c494ca20fdba7c09630); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal525f984c6bf98c494ca20fdba7c09630)): ?>
<?php $component = $__componentOriginal525f984c6bf98c494ca20fdba7c09630; ?>
<?php unset($__componentOriginal525f984c6bf98c494ca20fdba7c09630); ?>
<?php endif; ?><p class="text-xs text-text-muted">Youth</p><?php endif; ?>
<time class="text-xs text-text-muted" datetime="<?php echo e($post->created_at->toIso8601String()); ?>"><?php echo e($post->created_at->diffForHumans()); ?></time><?php if($post->visibility === 'private'): ?><?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['tone' => 'warning']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => 'warning']); ?>Privat · pengelola saja <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?><?php endif; ?></div>
<?php if($post->organization): ?><?php echo $__env->make('social.follow', ['targetType'=>'community', 'targetId'=>$post->organization->slug], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php elseif($post->author->profileVisibility?->is_profile_public && $post->author->profile && auth()->user()?->getKey() !== $post->author_id): ?><?php echo $__env->make('social.follow', ['targetType'=>'youth','targetId'=>$post->author->profile->public_slug], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php endif; ?></div>
<p class="whitespace-pre-line break-words text-sm leading-7 text-text-secondary"><?php echo e($post->body); ?></p>
<?php if($post->image_path): ?><div class="social-post-media"><img src="<?php echo e(route('social.image', $post)); ?>" alt="Gambar lampiran post" loading="lazy"></div><?php endif; ?>
<div class="flex flex-wrap items-center gap-3 border-t border-border pt-4"><?php if(auth()->guard()->check()): ?> <?php if(auth()->user()->hasRole('youth') && $post->visibility === 'public'): ?><form method="post" action="<?php echo e(route('social.like', $post)); ?>"><?php echo csrf_field(); ?><input type="hidden" name="liked" value="<?php echo e($liked ? 0 : 1); ?>"><button class="btn-outline !min-h-10 !px-3 text-sm" aria-pressed="<?php echo e($liked ? 'true' : 'false'); ?>" type="submit"><?php echo e($liked ? 'Batal suka' : 'Suka'); ?> · <?php echo e($post->likes_count); ?></button></form><?php endif; ?> <?php endif; ?>
<?php if(!$detail): ?><a class="btn-outline !min-h-10 !px-3 text-sm" href="<?php echo e(route('social.show', $post)); ?>">Komentar · <?php echo e($post->comments_count); ?></a><?php else: ?><p class="text-xs text-text-muted"><?php echo e($post->likes_count); ?> suka</p><?php endif; ?>
</div>
</article>
<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/social/post-card.blade.php ENDPATH**/ ?>