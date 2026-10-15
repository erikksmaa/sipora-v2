
<?php $__env->startSection('title', $thread->title.' · Forum SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<div class="mx-auto max-w-4xl space-y-5 px-5 py-10"><a class="text-sm font-bold text-interactive underline" href="<?php echo e(route('forum.index', ['category' => $thread->category->slug])); ?>">← Forum / <?php echo e($thread->category->name); ?></a><article class="sipora-card p-5 sm:p-8"><div class="flex flex-wrap items-center gap-2 text-xs font-bold text-text-muted"><?php if (isset($component)) { $__componentOriginale7f12d76f1f9891524de96ca143dbf2b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale7f12d76f1f9891524de96ca143dbf2b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forum.category-badge','data' => ['category' => $thread->category]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forum.category-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['category' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($thread->category)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale7f12d76f1f9891524de96ca143dbf2b)): ?>
<?php $attributes = $__attributesOriginale7f12d76f1f9891524de96ca143dbf2b; ?>
<?php unset($__attributesOriginale7f12d76f1f9891524de96ca143dbf2b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale7f12d76f1f9891524de96ca143dbf2b)): ?>
<?php $component = $__componentOriginale7f12d76f1f9891524de96ca143dbf2b; ?>
<?php unset($__componentOriginale7f12d76f1f9891524de96ca143dbf2b); ?>
<?php endif; ?><?php if (isset($component)) { $__componentOriginal525f984c6bf98c494ca20fdba7c09630 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal525f984c6bf98c494ca20fdba7c09630 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.author-identity','data' => ['author' => $thread->author]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.author-identity'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['author' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($thread->author)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal525f984c6bf98c494ca20fdba7c09630)): ?>
<?php $attributes = $__attributesOriginal525f984c6bf98c494ca20fdba7c09630; ?>
<?php unset($__attributesOriginal525f984c6bf98c494ca20fdba7c09630); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal525f984c6bf98c494ca20fdba7c09630)): ?>
<?php $component = $__componentOriginal525f984c6bf98c494ca20fdba7c09630; ?>
<?php unset($__componentOriginal525f984c6bf98c494ca20fdba7c09630); ?>
<?php endif; ?><span>· <time datetime="<?php echo e($thread->created_at->toIso8601String()); ?>"><?php echo e($thread->created_at->translatedFormat('d M Y H:i')); ?></time></span><?php if($thread->status === 'locked'): ?><?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['tone' => 'warning']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => 'warning']); ?>Terkunci <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?><?php endif; ?></div><h1 class="mt-4 font-heading text-2xl font-bold text-text-primary sm:text-3xl"><?php echo e($thread->title); ?></h1><div class="mt-5 whitespace-pre-line break-words text-sm leading-7 text-text-secondary"><?php echo e($thread->body); ?></div><div class="mt-7 flex flex-wrap items-center gap-3 border-t border-border pt-5"><?php if(auth()->guard()->check()): ?> <?php if(auth()->user()->hasRole('youth')): ?><form method="post" action="<?php echo e(route('forum.helpful', $thread)); ?>"><?php echo csrf_field(); ?><button class="btn-outline !min-h-9 !px-3 text-sm" type="submit"><?php echo e($helpful ? 'Batalkan Helpful' : 'Helpful'); ?> · <?php echo e($thread->reactions_count); ?></button></form><?php if($thread->author_id === auth()->user()->getKey() && $thread->status === 'active'): ?><a class="btn-outline !min-h-9 !px-3 text-sm" href="<?php echo e(route('forum.edit', $thread)); ?>">Edit</a><form method="post" action="<?php echo e(route('forum.destroy', $thread)); ?>" onsubmit="return confirm('Sembunyikan diskusi ini dari Forum publik?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="btn-outline !min-h-9 !px-3 text-sm" type="submit">Hapus</button></form><?php endif; ?><details class="w-full text-sm"><summary class="cursor-pointer text-text-muted">Laporkan diskusi</summary><form class="mt-3 flex flex-wrap gap-2" method="post" action="<?php echo e(route('forum.reports.store', $thread)); ?>"><?php echo csrf_field(); ?><select class="field" name="reason" aria-label="Alasan laporan" required><option value="spam">Spam</option><option value="harassment">Pelecehan</option><option value="inappropriate">Konten tidak pantas</option><option value="misinformation">Informasi keliru</option><option value="other">Lainnya</option></select><input class="field" name="details" maxlength="1000" placeholder="Keterangan opsional"><button class="btn-outline !min-h-9 !px-3" type="submit">Kirim laporan</button></form></details><?php endif; ?> <?php endif; ?></div></article>
<section class="sipora-card p-5 sm:p-8"><h2 class="font-heading text-xl font-bold text-text-primary">Balasan</h2><div class="mt-5 space-y-5"><?php $__empty_1 = true; $__currentLoopData = $replies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><article class="border-t border-border pt-5"><div class="flex flex-wrap items-center gap-2 text-xs text-text-muted"><?php if (isset($component)) { $__componentOriginal525f984c6bf98c494ca20fdba7c09630 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal525f984c6bf98c494ca20fdba7c09630 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.author-identity','data' => ['author' => $reply->author]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.author-identity'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['author' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($reply->author)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal525f984c6bf98c494ca20fdba7c09630)): ?>
<?php $attributes = $__attributesOriginal525f984c6bf98c494ca20fdba7c09630; ?>
<?php unset($__attributesOriginal525f984c6bf98c494ca20fdba7c09630); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal525f984c6bf98c494ca20fdba7c09630)): ?>
<?php $component = $__componentOriginal525f984c6bf98c494ca20fdba7c09630; ?>
<?php unset($__componentOriginal525f984c6bf98c494ca20fdba7c09630); ?>
<?php endif; ?><time datetime="<?php echo e($reply->created_at->toIso8601String()); ?>"><?php echo e($reply->created_at->diffForHumans()); ?></time></div><p class="mt-2 whitespace-pre-line break-words text-sm leading-7 text-text-secondary"><?php echo e($reply->body); ?></p><?php if(auth()->guard()->check()): ?> <?php if(auth()->user()->hasRole('youth')): ?><details class="mt-2 text-xs"><summary class="cursor-pointer text-text-muted">Laporkan balasan</summary><form class="mt-2 flex flex-wrap gap-2" method="post" action="<?php echo e(route('forum.reports.store', $thread)); ?>"><?php echo csrf_field(); ?><input type="hidden" name="reply_id" value="<?php echo e($reply->uuid()); ?>"><select class="field" name="reason" aria-label="Alasan laporan balasan"><option value="spam">Spam</option><option value="harassment">Pelecehan</option><option value="inappropriate">Konten tidak pantas</option><option value="misinformation">Informasi keliru</option><option value="other">Lainnya</option></select><button class="btn-outline !min-h-9 !px-3" type="submit">Kirim laporan</button></form></details><?php endif; ?> <?php endif; ?></article><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p class="text-sm text-text-muted">Belum ada balasan.</p><?php endif; ?></div><div class="mt-5"><?php echo e($replies->links()); ?></div><?php if($thread->status === 'active'): ?><?php if(auth()->guard()->check()): ?> <?php if(auth()->user()->hasRole('youth')): ?><form method="post" action="<?php echo e(route('forum.replies.store', $thread)); ?>" class="mt-7 border-t border-border pt-5"><?php echo csrf_field(); ?><label class="block text-sm font-bold text-text-primary">Tulis balasan<textarea class="field mt-2 min-h-32 w-full" name="body" minlength="3" maxlength="5000" required><?php echo e(old('body')); ?></textarea></label><button class="btn-primary mt-3" type="submit">Kirim balasan</button></form><?php endif; ?> <?php else: ?> <a class="btn-outline mt-6" href="<?php echo e(route('login')); ?>">Masuk untuk membalas</a> <?php endif; ?> <?php else: ?> <p class="mt-6 text-sm font-semibold text-text-muted">Diskusi dikunci. Balasan baru tidak tersedia.</p><?php endif; ?></section></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/forum/show.blade.php ENDPATH**/ ?>