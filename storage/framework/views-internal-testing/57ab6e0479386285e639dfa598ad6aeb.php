<?php $__env->startSection('title','Review '.$organization->name.' · SIPORA'); ?>
<?php $__env->startSection('verifier-content'); ?>
<div class="space-y-6"><?php if (isset($component)) { $__componentOriginal91a231a9270579fa1ae9246bd51fb785 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91a231a9270579fa1ae9246bd51fb785 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.page-header','data' => ['eyebrow' => 'Review Community','title' => $organization->name,'description' => 'Diajukan '.($organization->latestVerificationRequest?->submitted_at?->translatedFormat('d M Y, H:i') ?? '—')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Review Community','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($organization->name),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Diajukan '.($organization->latestVerificationRequest?->submitted_at?->translatedFormat('d M Y, H:i') ?? '—'))]); ?> <?php $__env->slot('actions', null, []); ?> <a class="btn-secondary" href="<?php echo e(route('verifier.community-verifications.index')); ?>">Kembali</a><?php if (isset($component)) { $__componentOriginal14e36fdec6e120ea22b80172b70f253c = $component; } ?>
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
<?php endif; ?> <?php $__env->endSlot(); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $attributes = $__attributesOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $component = $__componentOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__componentOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?>
<div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_380px]"><main class="space-y-6"><?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><div class="flex items-center gap-4"><span class="grid size-16 shrink-0 place-items-center overflow-hidden rounded-2xl bg-primary-50 text-2xl font-bold text-primary"><?php if($organization->logo_path): ?><img src="<?php echo e(route('verifier.community-verifications.logo',$organization)); ?>" class="size-full object-cover" alt="Logo <?php echo e($organization->name); ?>"><?php else: ?><?php echo e(mb_substr($organization->name,0,1)); ?><?php endif; ?></span><div><?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['tone' => 'primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => 'primary']); ?><?php echo e($organization->category->name); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?><h2 class="mt-2 text-xl font-bold text-text-primary">Profil Community</h2></div></div><p class="mt-5 whitespace-pre-line leading-7 text-text-secondary"><?php echo e($organization->description ?: 'Deskripsi belum dilengkapi.'); ?></p><dl class="mt-5 grid gap-4 border-t border-border pt-5 text-sm sm:grid-cols-2"><div><dt class="text-text-muted">Wilayah</dt><dd class="font-semibold"><?php echo e($organization->administrativeArea?->name ?? '—'); ?></dd></div><div><dt class="text-text-muted">Alamat</dt><dd class="font-semibold"><?php echo e($organization->address_text ?: '—'); ?></dd></div><div><dt class="text-text-muted">Email</dt><dd class="font-semibold"><?php echo e($organization->contact_email ?: '—'); ?></dd></div><div><dt class="text-text-muted">Telepon</dt><dd class="font-semibold"><?php echo e($organization->contact_phone ?: '—'); ?></dd></div></dl> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><h2 class="text-lg font-bold text-text-primary">Informasi pengajuan</h2><dl class="mt-4 grid gap-4 text-sm sm:grid-cols-2"><div><dt class="text-text-muted">Pemohon</dt><dd class="font-semibold"><?php echo e($organization->creator?->profile?->full_name ?? $organization->creator?->name ?? '—'); ?></dd><dd class="text-text-secondary"><?php echo e($organization->creator?->email); ?></dd></div><div><dt class="text-text-muted">Catatan pemohon</dt><dd><?php echo e($organization->latestVerificationRequest?->submission_notes ?: '—'); ?></dd></div></dl> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if($previousReview): ?><?php if (isset($component)) { $__componentOriginal52d89e0fa152624786a863357dd18ad1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal52d89e0fa152624786a863357dd18ad1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.review-history','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.review-history'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><article><?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['tone' => 'warning']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => 'warning']); ?>Keputusan sebelumnya <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?><p class="mt-2 text-sm text-text-secondary"><?php echo e($previousReview->review_notes); ?></p></article> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal52d89e0fa152624786a863357dd18ad1)): ?>
<?php $attributes = $__attributesOriginal52d89e0fa152624786a863357dd18ad1; ?>
<?php unset($__attributesOriginal52d89e0fa152624786a863357dd18ad1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal52d89e0fa152624786a863357dd18ad1)): ?>
<?php $component = $__componentOriginal52d89e0fa152624786a863357dd18ad1; ?>
<?php unset($__componentOriginal52d89e0fa152624786a863357dd18ad1); ?>
<?php endif; ?><?php endif; ?></main>
<aside class="h-fit xl:sticky xl:top-24"><?php if($organization->review_status==='pending_review'): ?><?php if (isset($component)) { $__componentOriginal797da58f16556f641c23b0e72d0d5590 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal797da58f16556f641c23b0e72d0d5590 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.decision-panel','data' => ['title' => 'Keputusan Verifier','action' => route('verifier.community-verifications.review',$organization),'notesName' => 'review_notes','approveLabel' => 'Setujui & aktifkan']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.decision-panel'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Keputusan Verifier','action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('verifier.community-verifications.review',$organization)),'notes-name' => 'review_notes','approve-label' => 'Setujui & aktifkan']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal797da58f16556f641c23b0e72d0d5590)): ?>
<?php $attributes = $__attributesOriginal797da58f16556f641c23b0e72d0d5590; ?>
<?php unset($__attributesOriginal797da58f16556f641c23b0e72d0d5590); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal797da58f16556f641c23b0e72d0d5590)): ?>
<?php $component = $__componentOriginal797da58f16556f641c23b0e72d0d5590; ?>
<?php unset($__componentOriginal797da58f16556f641c23b0e72d0d5590); ?>
<?php endif; ?><?php else: ?><?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><h2 class="text-lg font-bold text-text-primary">Keputusan tersimpan</h2><p class="mt-2 text-sm text-text-secondary"><?php echo e($organization->latestVerificationRequest?->review_notes ?: 'Tidak ada catatan tambahan.'); ?></p> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?><?php endif; ?></aside></div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.verifier', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/verifier/communities/show.blade.php ENDPATH**/ ?>