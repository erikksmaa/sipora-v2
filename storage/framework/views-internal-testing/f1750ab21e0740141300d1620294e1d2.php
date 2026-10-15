<?php $__env->startSection('title','Dashboard Admin · SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-7"><?php if (isset($component)) { $__componentOriginal91a231a9270579fa1ae9246bd51fb785 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91a231a9270579fa1ae9246bd51fb785 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.page-header','data' => ['eyebrow' => 'Government Workspace','title' => 'Dashboard Tata Kelola SIPORA','description' => 'Ringkasan faktual ekosistem SIPORA untuk pekerjaan administratif Dindikpora.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Government Workspace','title' => 'Dashboard Tata Kelola SIPORA','description' => 'Ringkasan faktual ekosistem SIPORA untuk pekerjaan administratif Dindikpora.']); ?> <?php $__env->slot('actions', null, []); ?> <a class="btn-primary" href="<?php echo e(route('admin.identity-verifications.index')); ?>"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'shield-check','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'shield-check','class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>Tinjau identitas</a><a class="btn-secondary" href="<?php echo e(route('admin.opportunities.index')); ?>">Kelola Opportunity</a> <?php $__env->endSlot(); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $attributes = $__attributesOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $component = $__componentOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__componentOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?>
<section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Metrik utama"><?php if (isset($component)) { $__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.metric-card','data' => ['title' => 'Youth terdaftar','value' => number_format($analytics['youth']['registered']),'icon' => 'users','href' => route('admin.directory.youth.index'),'action' => 'Lihat direktori']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.metric-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Youth terdaftar','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($analytics['youth']['registered'])),'icon' => 'users','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.directory.youth.index')),'action' => 'Lihat direktori']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe)): ?>
<?php $attributes = $__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe; ?>
<?php unset($__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe)): ?>
<?php $component = $__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe; ?>
<?php unset($__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe); ?>
<?php endif; ?><?php if (isset($component)) { $__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.metric-card','data' => ['title' => 'Community aktif','value' => number_format($analytics['communities']['active']),'icon' => 'building','tone' => 'info','href' => route('admin.directory.communities.index', ['status'=>'approved','operational'=>'active']),'action' => 'Lihat Community aktif']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.metric-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Community aktif','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($analytics['communities']['active'])),'icon' => 'building','tone' => 'info','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.directory.communities.index', ['status'=>'approved','operational'=>'active'])),'action' => 'Lihat Community aktif']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe)): ?>
<?php $attributes = $__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe; ?>
<?php unset($__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe)): ?>
<?php $component = $__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe; ?>
<?php unset($__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe); ?>
<?php endif; ?><?php if (isset($component)) { $__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.metric-card','data' => ['title' => 'Activity publik','value' => number_format($analytics['activities']['published']),'icon' => 'clipboard-check','tone' => 'accent','href' => route('admin.directory.activities.index', ['status'=>'approved','publication'=>'published']),'action' => 'Lihat Activity terbit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.metric-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Activity publik','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($analytics['activities']['published'])),'icon' => 'clipboard-check','tone' => 'accent','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.directory.activities.index', ['status'=>'approved','publication'=>'published'])),'action' => 'Lihat Activity terbit']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe)): ?>
<?php $attributes = $__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe; ?>
<?php unset($__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe)): ?>
<?php $component = $__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe; ?>
<?php unset($__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe); ?>
<?php endif; ?><?php if (isset($component)) { $__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.metric-card','data' => ['title' => 'Opportunity terbuka','value' => number_format($analytics['opportunities']['open_deadlines']),'icon' => 'briefcase','tone' => 'success','href' => route('admin.opportunities.index', ['status'=>'published']),'action' => 'Kelola Opportunity']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.metric-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Opportunity terbuka','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($analytics['opportunities']['open_deadlines'])),'icon' => 'briefcase','tone' => 'success','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.opportunities.index', ['status'=>'published'])),'action' => 'Kelola Opportunity']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe)): ?>
<?php $attributes = $__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe; ?>
<?php unset($__attributesOriginalb1c0d43ce2b7e6614df99c318557a7fe); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe)): ?>
<?php $component = $__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe; ?>
<?php unset($__componentOriginalb1c0d43ce2b7e6614df99c318557a7fe); ?>
<?php endif; ?></section>
<div class="grid gap-6 xl:grid-cols-[1.1fr_.9fr]"><?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><div class="flex items-center gap-3"><span class="grid size-10 place-items-center rounded-xl bg-warning-soft text-warning"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'shield-check']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'shield-check']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></span><div><h2 class="text-lg font-bold text-text-primary">Perlu perhatian</h2><p class="text-sm text-text-secondary">Antrean faktual tanpa skor atau tren rekaan.</p></div></div><div class="mt-5 divide-y divide-border"><a class="flex items-center justify-between gap-3 py-4" href="<?php echo e(route('admin.identity-verifications.index')); ?>"><span><strong class="block text-text-primary">Verifikasi Identitas Pemuda</strong><span class="text-sm text-text-secondary">Buka antrean pengajuan identitas</span></span><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'chevron-right','class' => 'text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-right','class' => 'text-primary']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></a><a class="flex items-center justify-between gap-3 py-4" href="<?php echo e(route('admin.opportunities.index',['status'=>'draft'])); ?>"><span><strong class="block text-text-primary">Draft Opportunity</strong><span class="text-sm text-text-secondary">Tinjau konten sebelum publikasi</span></span><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'chevron-right','class' => 'text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-right','class' => 'text-primary']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></a></div> <?php echo $__env->renderComponent(); ?>
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
<?php $component->withAttributes([]); ?><h2 class="text-lg font-bold text-text-primary">Status ekosistem</h2><dl class="mt-4 space-y-3 text-sm"><div class="flex justify-between rounded-xl bg-surface-soft px-4 py-3"><dt>Profil Youth tersedia</dt><dd class="font-bold text-primary"><?php echo e(number_format($analytics['youth']['with_profile'])); ?></dd></div><div class="flex justify-between rounded-xl bg-surface-soft px-4 py-3"><dt>Community menunggu</dt><dd class="font-bold text-primary"><?php echo e(number_format($analytics['communities']['pending'])); ?></dd></div><div class="flex justify-between rounded-xl bg-surface-soft px-4 py-3"><dt>Activity mendatang</dt><dd class="font-bold text-primary"><?php echo e(number_format($analytics['activities']['upcoming'])); ?></dd></div><div class="flex justify-between rounded-xl bg-surface-soft px-4 py-3"><dt>Registrasi Activity</dt><dd class="font-bold text-primary"><?php echo e(number_format($analytics['activities']['registrations'])); ?></dd></div></dl> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?></div>
<?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><h2 class="text-lg font-bold text-text-primary">Status Program</h2><p class="mt-1 text-sm text-text-secondary">Distribusi sesuai status eksekusi yang tersimpan.</p><div class="mt-5 grid gap-3 sm:grid-cols-2 xl:grid-cols-4"><?php $__empty_1 = true; $__currentLoopData = $analytics['programs']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status=>$total): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><a href="<?php echo e(route('admin.directory.programs.index', ['status'=>$status])); ?>" class="surface-card surface-card-hover block p-4" aria-label="Lihat Program <?php echo e(str($status)->replace('_',' ')->title()); ?>"><span class="text-sm font-semibold text-text-secondary"><?php echo e(str($status)->replace('_',' ')->title()); ?></span><strong class="mt-2 block text-2xl text-primary"><?php echo e(number_format($total)); ?></strong></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if (isset($component)) { $__componentOriginal3607a477fdef7402bc742abad5df9c51 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3607a477fdef7402bc742abad5df9c51 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.empty-state','data' => ['class' => 'sm:col-span-2','title' => 'Belum ada Program']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'sm:col-span-2','title' => 'Belum ada Program']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $attributes = $__attributesOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__attributesOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $component = $__componentOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__componentOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?><?php endif; ?></div> <?php echo $__env->renderComponent(); ?>
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
<?php $component->withAttributes([]); ?>
    <div class="flex flex-wrap items-end justify-between gap-3"><div><h2 class="text-lg font-bold text-text-primary">Statistik Pemuda SIPORA</h2><p class="mt-1 text-sm text-text-secondary">Berdasarkan pemuda yang terdaftar di SIPORA. Angka operasional dapat mencakup kelompok kecil.</p></div><div class="flex flex-wrap gap-2"><a class="btn-secondary" href="<?php echo e(route('admin.ecosystem-map.index')); ?>">Peta Ekosistem</a><a class="btn-secondary" href="<?php echo e(route('statistics.index')); ?>">Lihat tampilan publik</a></div></div>
    <div class="mt-5 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <?php $__currentLoopData = [['registered', 'Pemuda Terdaftar'], ['identity_verified', 'Identitas Terverifikasi'], ['public_portfolio', 'Portfolio Publik'], ['activity_participated', 'Pernah Mengikuti Activity']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$key, $label]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="rounded-xl border border-border bg-surface-soft p-4"><p class="text-sm text-text-secondary"><?php echo e($label); ?></p><strong class="mt-2 block text-2xl text-text-primary"><?php echo e(number_format($youthStatistics['totals'][$key])); ?></strong></div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <h3 class="mt-7 font-bold text-text-primary">Tahapan partisipasi</h3>
    <div class="mt-3 grid gap-2 sm:grid-cols-2 xl:grid-cols-3"><?php $__currentLoopData = $youthStatistics['funnel']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="flex justify-between gap-2 rounded-lg bg-surface-soft px-4 py-3 text-sm"><span><?php echo e($step['label']); ?></span><strong><?php echo e(number_format($step['count'])); ?></strong></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div>
    <div class="mt-7 grid gap-5 lg:grid-cols-2 xl:grid-cols-3">
        <?php $__currentLoopData = [['growth_monthly', 'Pendaftaran per bulan'], ['growth_yearly', 'Pendaftaran per tahun'], ['age', 'Rentang usia'], ['gender', 'Gender'], ['districts', 'Kecamatan'], ['interests', 'Top Interest'], ['skills', 'Top Skill']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$key, $title]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <section class="rounded-xl border border-border p-4"><h3 class="font-bold text-text-primary"><?php echo e($title); ?></h3><dl class="mt-3 space-y-2 text-sm"><?php $__empty_1 = true; $__currentLoopData = $youthStatistics[$key]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><div class="flex justify-between gap-3"><dt class="min-w-0 truncate text-text-secondary"><?php echo e($key === 'gender' ? (['male' => 'Laki-laki', 'female' => 'Perempuan', 'other' => 'Lainnya', 'prefer_not_to_say' => 'Memilih tidak menyebutkan', 'unknown' => 'Belum diisi'][$row['label']] ?? $row['label']) : $row['label']); ?></dt><dd class="font-bold text-text-primary"><?php echo e(number_format($row['count'])); ?></dd></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p class="text-text-muted">Belum ada data.</p><?php endif; ?></dl></section>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <h3 class="mt-8 font-bold text-text-primary">Keterlibatan nyata</h3>
    <p class="mt-1 text-sm text-text-secondary">Setiap metrik menghitung Youth unik. Tidak semua metrik merupakan tahap berurutan.</p>
    <div class="mt-3 grid gap-3 sm:grid-cols-2 xl:grid-cols-3"><?php $__currentLoopData = $youthStatistics['participation']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="flex justify-between gap-3 border border-border bg-surface-soft p-4 text-sm"><span><?php echo e($row['label']); ?></span><strong class="text-text-primary"><?php echo e(number_format($row['count'])); ?></strong></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div>
    <h3 class="mt-8 font-bold text-text-primary">Cakupan per kecamatan</h3>
    <p class="mt-1 text-sm text-text-secondary">Domisili Youth, Youth dengan pendaftaran Activity diterima, lokasi Activity terbit, Community aktif, dan Program publik. Record tanpa kecamatan tidak dihitung di tabel ini.</p>
    <div class="mt-3 overflow-x-auto"><table class="min-w-[46rem] w-full text-sm"><thead class="bg-surface-soft text-left"><tr><th class="p-3">Kecamatan</th><th class="p-3 text-right">Youth</th><th class="p-3 text-right">Ikut Activity</th><th class="p-3 text-right">Partisipasi</th><th class="p-3 text-right">Community</th><th class="p-3 text-right">Activity</th><th class="p-3 text-right">Program</th></tr></thead><tbody><?php $__currentLoopData = $youthStatistics['coverage']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><tr class="border-b border-border"><th scope="row" class="p-3 text-left font-semibold"><?php echo e($row['district']); ?></th><td class="p-3 text-right tabular-nums"><?php echo e(number_format($row['youth'])); ?></td><td class="p-3 text-right tabular-nums"><?php echo e(number_format($row['participated'])); ?></td><td class="p-3 text-right tabular-nums"><?php echo e($row['youth'] ? number_format(100 * $row['participated'] / $row['youth'], 1).'%' : '—'); ?></td><?php $__currentLoopData = ['community','activity','program']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><td class="p-3 text-right tabular-nums"><?php echo e(number_format($row[$key])); ?></td><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></tbody></table></div>
    <div class="mt-8 grid gap-5 xl:grid-cols-2">
        <?php $__currentLoopData = [['interest_by_district','Minat per kecamatan','district'],['skill_by_age','Keahlian per rentang usia','age']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$key,$title,$dimension]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <section class="rounded-xl border border-border p-4"><h3 class="font-bold text-text-primary"><?php echo e($title); ?></h3><p class="mt-1 text-xs text-text-secondary">20 kombinasi dengan jumlah tertinggi. Kelompok kecil disamarkan.</p><div class="mt-3 max-h-80 overflow-auto"><table class="w-full text-sm"><thead><tr class="text-left"><th class="py-2"><?php echo e($dimension === 'age' ? 'Usia' : 'Kecamatan'); ?></th><th>Minat/keahlian</th><th class="text-right">Youth</th></tr></thead><tbody><?php $__empty_1 = true; $__currentLoopData = $youthStatistics['cross_insights'][$key]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr class="border-t border-border"><td class="py-2"><?php echo e($row[$dimension]); ?></td><td><?php echo e($row['label']); ?></td><td class="text-right tabular-nums"><?php echo e($row['count'] < 5 && $row['count'] > 0 ? '<5' : number_format($row['count'])); ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="3" class="py-3 text-text-secondary">Belum ada data.</td></tr><?php endif; ?></tbody></table></div></section>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <section class="mt-5 rounded-xl border border-border p-4"><h3 class="font-bold text-text-primary">Penyelesaian Activity per kategori</h3><p class="mt-1 text-xs text-text-secondary">Youth unik dengan pendaftaran diterima dan Youth unik yang menyelesaikan Activity pada kategori tersebut.</p><div class="mt-3 overflow-x-auto"><table class="w-full min-w-[32rem] text-sm"><thead><tr class="text-left"><th class="py-2">Kategori</th><th class="text-right">Diterima</th><th class="text-right">Selesai</th></tr></thead><tbody><?php $__empty_1 = true; $__currentLoopData = $youthStatistics['cross_insights']['completion_by_category']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr class="border-t border-border"><th scope="row" class="py-2 text-left font-medium"><?php echo e($row['label']); ?></th><td class="text-right tabular-nums"><?php echo e(number_format($row['accepted'])); ?></td><td class="text-right tabular-nums"><?php echo e(number_format($row['completed'])); ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="3" class="py-3 text-text-secondary">Belum ada data.</td></tr><?php endif; ?></tbody></table></div></section>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/workspaces/admin.blade.php ENDPATH**/ ?>