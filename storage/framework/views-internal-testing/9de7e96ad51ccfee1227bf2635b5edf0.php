<?php $__env->startSection('title', 'Sertifikat Activity · SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-6"><?php if (isset($component)) { $__componentOriginal8e0a96c106aaf9b655be7405167c4255 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8e0a96c106aaf9b655be7405167c4255 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.page-header','data' => ['eyebrow' => 'Kredensial SIPORA','title' => 'Sertifikat Activity','description' => 'Sertifikat terverifikasi yang diterbitkan dari partisipasi Activity yang telah selesai.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Kredensial SIPORA','title' => 'Sertifikat Activity','description' => 'Sertifikat terverifikasi yang diterbitkan dari partisipasi Activity yang telah selesai.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8e0a96c106aaf9b655be7405167c4255)): ?>
<?php $attributes = $__attributesOriginal8e0a96c106aaf9b655be7405167c4255; ?>
<?php unset($__attributesOriginal8e0a96c106aaf9b655be7405167c4255); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e0a96c106aaf9b655be7405167c4255)): ?>
<?php $component = $__componentOriginal8e0a96c106aaf9b655be7405167c4255; ?>
<?php unset($__componentOriginal8e0a96c106aaf9b655be7405167c4255); ?>
<?php endif; ?>
<div class="grid gap-4 lg:grid-cols-2"><?php $__empty_1 = true; $__currentLoopData = $certificates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $certificate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><article class="sipora-card"><div class="flex items-start justify-between gap-3"><div><p class="text-xs font-bold uppercase tracking-wider text-orange-600">SIPORA Verified Certificate</p><h2 class="mt-1 text-xl font-black text-[#14205c]"><?php echo e($certificate->name); ?></h2></div><span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">Terverifikasi</span></div><p class="mt-4 text-sm text-slate-600"><?php echo e($certificate->issuer_name); ?></p><p class="mt-2 break-all font-mono text-xs text-slate-500"><?php echo e($certificate->certificate_number); ?></p><div class="mt-5 flex flex-wrap gap-3"><a class="btn-primary" href="<?php echo e(route('youth.certificates.show',$certificate)); ?>">Lihat</a><a class="btn-secondary" href="<?php echo e(route('youth.certificates.download',$certificate)); ?>">Unduh PDF</a><a class="btn-secondary" href="<?php echo e(route('certificates.verify',$certificate->verification_code)); ?>" target="_blank" rel="noopener">Verifikasi</a></div></article><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><div class="col-span-full rounded-3xl border-2 border-dashed border-slate-200 bg-white px-6 py-14 text-center"><h2 class="text-xl font-bold text-[#14205c]">Belum ada sertifikat</h2><p class="mt-2 text-sm text-slate-500">Sertifikat akan tersedia setelah diterbitkan oleh pengelola Activity.</p></div><?php endif; ?></div><?php echo e($certificates->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.youth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/youth/certificates/index.blade.php ENDPATH**/ ?>