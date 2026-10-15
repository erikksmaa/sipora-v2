<?php $__env->startSection('title', 'Notifikasi · SIPORA'); ?>
<?php $__env->startSection(isset($organization) ? 'manager-content' : 'content'); ?>
<div class="mx-auto max-w-4xl space-y-6"><?php if (isset($component)) { $__componentOriginal8e0a96c106aaf9b655be7405167c4255 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8e0a96c106aaf9b655be7405167c4255 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.page-header','data' => ['eyebrow' => 'Pusat notifikasi','title' => 'Notifikasi','description' => 'Pembaruan penting dari workflow SIPORA.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Pusat notifikasi','title' => 'Notifikasi','description' => 'Pembaruan penting dari workflow SIPORA.']); ?><form method="POST" action="<?php echo e(route('notifications.read-all')); ?>"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?><button class="btn-secondary" type="submit">Tandai semua dibaca</button></form> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8e0a96c106aaf9b655be7405167c4255)): ?>
<?php $attributes = $__attributesOriginal8e0a96c106aaf9b655be7405167c4255; ?>
<?php unset($__attributesOriginal8e0a96c106aaf9b655be7405167c4255); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e0a96c106aaf9b655be7405167c4255)): ?>
<?php $component = $__componentOriginal8e0a96c106aaf9b655be7405167c4255; ?>
<?php unset($__componentOriginal8e0a96c106aaf9b655be7405167c4255); ?>
<?php endif; ?>
<div class="space-y-3"><?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php ($typeIcon = str_contains($notification->notification_type, 'certificate') ? 'award' : (str_contains($notification->notification_type, 'community') ? 'building' : (str_contains($notification->notification_type, 'activity') ? 'activity' : 'bell'))); ?><article class="rounded-2xl border <?php echo e($notification->read_at ? 'border-slate-200 bg-white' : 'border-indigo-200 bg-indigo-50/70'); ?> p-5 shadow-sm"><div class="flex flex-wrap items-start justify-between gap-4"><div class="flex min-w-0 flex-1 gap-3"><span class="grid size-10 shrink-0 place-items-center rounded-xl <?php echo e($notification->read_at ? 'bg-slate-100 text-slate-500' : 'bg-white text-primary'); ?>"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => $typeIcon,'class' => 'size-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($typeIcon),'class' => 'size-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></span><div class="min-w-0"><div class="flex items-center gap-2"><?php if(!$notification->read_at): ?><span class="size-2 rounded-full bg-orange-500" aria-label="Belum dibaca"></span><?php endif; ?><h2 class="font-black text-text-primary"><?php echo e($notification->title); ?></h2></div><p class="mt-2 text-sm leading-6 text-slate-600"><?php echo e($notification->body); ?></p><p class="mt-2 text-xs text-slate-400" title="<?php echo e($notification->created_at->translatedFormat('d M Y H:i')); ?>"><?php echo e($notification->created_at->diffForHumans()); ?></p></div></div><div class="flex flex-wrap gap-2"><?php if($links[$notification->uuid()] ?? null): ?><a class="btn-secondary !min-h-9 !px-3 !py-1.5 text-sm" href="<?php echo e($links[$notification->uuid()]); ?>">Buka</a><?php endif; ?> <?php if(!$notification->read_at): ?><form method="POST" action="<?php echo e(route('notifications.read', $notification)); ?>"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?><button class="btn-secondary !min-h-9 !px-3 !py-1.5 text-sm" type="submit">Tandai dibaca</button></form><?php endif; ?></div></div></article><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if (isset($component)) { $__componentOriginal3607a477fdef7402bc742abad5df9c51 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3607a477fdef7402bc742abad5df9c51 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.empty-state','data' => ['title' => 'Belum ada notifikasi','description' => 'Pembaruan penting akan muncul di sini.','icon' => 'bell']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Belum ada notifikasi','description' => 'Pembaruan penting akan muncul di sini.','icon' => 'bell']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $attributes = $__attributesOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__attributesOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $component = $__componentOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__componentOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?><?php endif; ?></div><?php if($notifications->hasPages()): ?><div class="mt-8"><?php echo e($notifications->links()); ?></div><?php endif; ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(isset($organization) ? 'layouts.manager' : (auth()->user()->hasRole('admin') ? 'layouts.admin' : (auth()->user()->hasRole('youth') ? 'layouts.youth' : 'layouts.base')), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/notifications/index.blade.php ENDPATH**/ ?>