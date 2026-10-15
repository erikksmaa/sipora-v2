<?php $__env->startSection('title', $organization->name.' · Pengajuan Komunitas'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <a class="text-sm font-bold text-[#243378]" href="<?php echo e(route('youth.communities.index')); ?>">← Semua pengajuan</a>
    <section class="overflow-hidden rounded-3xl bg-[#18245c] text-white shadow-xl"><div class="grid gap-6 p-6 sm:p-8 md:grid-cols-[120px_1fr_auto] md:items-center">
        <div class="grid size-28 place-items-center overflow-hidden rounded-2xl bg-white/10 text-4xl font-extrabold"><?php if($organization->logo_path): ?><img class="size-full object-cover" src="<?php echo e(route('youth.communities.logo', $organization)); ?>" alt="Logo <?php echo e($organization->name); ?>"><?php else: ?><?php echo e(mb_substr($organization->name, 0, 1)); ?><?php endif; ?></div>
        <div><div class="flex flex-wrap items-center gap-2"><span class="rounded-full bg-white/10 px-3 py-1 text-xs font-bold"><?php echo e($organization->category->name); ?></span><?php if (isset($component)) { $__componentOriginal14e36fdec6e120ea22b80172b70f253c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal14e36fdec6e120ea22b80172b70f253c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.community.status-badge','data' => ['status' => $organization->review_status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('community.status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($organization->review_status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal14e36fdec6e120ea22b80172b70f253c)): ?>
<?php $attributes = $__attributesOriginal14e36fdec6e120ea22b80172b70f253c; ?>
<?php unset($__attributesOriginal14e36fdec6e120ea22b80172b70f253c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal14e36fdec6e120ea22b80172b70f253c)): ?>
<?php $component = $__componentOriginal14e36fdec6e120ea22b80172b70f253c; ?>
<?php unset($__componentOriginal14e36fdec6e120ea22b80172b70f253c); ?>
<?php endif; ?></div><h1 class="mt-3 text-3xl font-extrabold sm:text-4xl"><?php echo e($organization->name); ?></h1><p class="mt-2 text-indigo-100"><?php echo e($organization->administrativeArea?->name ?? 'Wilayah belum dipilih'); ?></p></div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $organization)): ?><a class="landing-btn-primary" href="<?php echo e(route('youth.communities.edit', $organization)); ?>">Edit draft</a><?php endif; ?>
    </div></section>

    <?php if($organization->review_status === 'pending_review'): ?><div class="rounded-2xl border border-amber-300 bg-amber-50 p-5"><h2 class="font-bold text-amber-900">Sedang menunggu verifikasi</h2><p class="mt-1 text-sm text-amber-800">Data dikunci selama peninjauan agar isi pengajuan tetap konsisten.</p></div><?php endif; ?>
    <?php if($organization->review_status === 'revision' && $organization->latestVerificationRequest?->review_notes): ?><div class="rounded-2xl border border-orange-300 bg-orange-50 p-5"><h2 class="font-bold text-orange-900">Pengajuan perlu direvisi</h2><p class="mt-1 text-sm text-orange-800"><?php echo e($organization->latestVerificationRequest->review_notes); ?></p></div><?php endif; ?>

    <div class="grid gap-6 lg:grid-cols-[1fr_340px]"><section class="sipora-card"><h2 class="text-xl font-bold text-[#243378]">Tentang komunitas</h2><p class="mt-4 whitespace-pre-line text-slate-600"><?php echo e($organization->description ?: 'Deskripsi belum dilengkapi.'); ?></p><h3 class="mt-7 font-bold">Alamat</h3><p class="mt-2 text-sm text-slate-600"><?php echo e($organization->address_text ?: 'Belum dilengkapi'); ?></p></section>
    <aside class="space-y-6"><section class="sipora-card"><h2 class="font-bold text-[#243378]">Status pengajuan</h2><div class="mt-3"><?php if (isset($component)) { $__componentOriginal14e36fdec6e120ea22b80172b70f253c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal14e36fdec6e120ea22b80172b70f253c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.community.status-badge','data' => ['status' => $organization->review_status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('community.status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($organization->review_status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal14e36fdec6e120ea22b80172b70f253c)): ?>
<?php $attributes = $__attributesOriginal14e36fdec6e120ea22b80172b70f253c; ?>
<?php unset($__attributesOriginal14e36fdec6e120ea22b80172b70f253c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal14e36fdec6e120ea22b80172b70f253c)): ?>
<?php $component = $__componentOriginal14e36fdec6e120ea22b80172b70f253c; ?>
<?php unset($__componentOriginal14e36fdec6e120ea22b80172b70f253c); ?>
<?php endif; ?></div><p class="mt-3 text-sm text-slate-500">Status operasional: <?php echo e($organization->operational_status === 'inactive' ? 'Belum aktif' : ucfirst($organization->operational_status)); ?></p></section><section class="sipora-card"><h2 class="font-bold text-[#243378]">Kontak</h2><dl class="mt-3 space-y-2 text-sm"><div><dt class="text-slate-500">Email</dt><dd><?php echo e($organization->contact_email ?: '—'); ?></dd></div><div><dt class="text-slate-500">Telepon</dt><dd><?php echo e($organization->contact_phone ?: '—'); ?></dd></div><div><dt class="text-slate-500">Situs</dt><dd class="break-all"><?php echo e($organization->website_url ?: '—'); ?></dd></div></dl></section></aside></div>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('submit', $organization)): ?><section class="sipora-card border-indigo-200"><div class="flex flex-col justify-between gap-5 md:flex-row md:items-end"><div><p class="text-xs font-bold uppercase tracking-wider text-orange-600">Langkah terakhir</p><h2 class="mt-1 text-xl font-bold text-[#243378]">Kirim untuk verifikasi</h2><p class="mt-1 text-sm text-slate-600">Pastikan semua data benar. Setelah dikirim, pengajuan dikunci selama peninjauan.</p></div><form method="post" action="<?php echo e(route('youth.communities.submit', $organization)); ?>" class="w-full max-w-md"><?php echo csrf_field(); ?><label class="field-label" for="submission_notes">Catatan pengajuan (opsional)</label><textarea class="field min-h-20" id="submission_notes" name="submission_notes" maxlength="2000"></textarea><button class="btn mt-3 w-full" type="submit">Kirim pengajuan</button></form></div></section><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.youth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/youth/communities/show.blade.php ENDPATH**/ ?>