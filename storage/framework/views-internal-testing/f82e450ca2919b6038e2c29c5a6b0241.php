
<?php $__env->startSection('title','Pendaftar dan Peserta Program'); ?>
<?php $__env->startSection($organization ? 'manager-content' : 'verifier-content'); ?>
<?php ($baseRoute=$organization ? 'manager.programs.applications' : 'verifier.programs.applications'); ?>
<?php ($baseParams=$organization ? [$organization,$program] : [$program]); ?>
<div class="space-y-6">
    <?php if (isset($component)) { $__componentOriginal91a231a9270579fa1ae9246bd51fb785 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91a231a9270579fa1ae9246bd51fb785 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.page-header','data' => ['title' => 'Pendaftar & Peserta Program','description' => $program->title]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Pendaftar & Peserta Program','description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($program->title)]); ?> <?php $__env->slot('actions', null, []); ?> <?php if($organization): ?><a class="btn-outline" href="<?php echo e(route('manager.programs.show',[$organization,$program])); ?>">Kembali ke Program</a><?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('configureIntake',$program)): ?><a class="btn-accent" href="<?php echo e(route('manager.programs.intake.edit',[$organization,$program])); ?>">Pengaturan Pendaftaran</a><?php endif; ?> <?php else: ?><a class="btn-outline" href="<?php echo e(route('verifier.programs.intake.index')); ?>">Daftar Program</a><?php endif; ?> <?php $__env->endSlot(); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $attributes = $__attributesOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $component = $__componentOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__componentOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?>
    <div class="grid grid-cols-2 gap-3 lg:grid-cols-4"><?php $__currentLoopData = ['total'=>'Total Pendaftar','accepted'=>'Peserta Aktif','waitlisted'=>'Daftar Tunggu','rejected'=>'Tidak Lolos']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="sipora-card p-4"><p class="text-xs text-text-secondary"><?php echo e($label); ?></p><strong class="mt-2 block text-2xl"><?php echo e($key==='total' ? $summary['total'] : $summary['counts'][$key]); ?></strong></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div>
    <div class="sipora-card flex flex-wrap gap-x-6 gap-y-3 text-sm"><p>Kuota: <strong><?php echo e($summary['quota'] ?? 'Tanpa batas'); ?></strong></p><p>Sisa: <strong><?php echo e($summary['remaining'] ?? 'Tanpa batas'); ?></strong></p><p>Diajukan: <strong><?php echo e($summary['counts']['submitted']); ?></strong></p><p>Ditinjau: <strong><?php echo e($summary['counts']['under_review']); ?></strong></p><p>Mengundurkan diri: <strong><?php echo e($summary['counts']['withdrawn']); ?></strong></p></div>
    <form method="GET" class="sipora-card grid gap-3 md:grid-cols-[1fr_1fr_auto_auto]"><label><span class="label">Cari nama/email akun</span><input class="field" name="q" maxlength="120" value="<?php echo e($search); ?>"></label><label><span class="label">Status</span><select name="status" class="field"><option value="all">Semua</option><?php $__currentLoopData = \App\Models\ProgramApplication::LABELS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($value); ?>" <?php if($status===$value): echo 'selected'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label><button class="btn-accent self-end">Terapkan</button><a class="btn-outline self-end" href="<?php echo e(route($baseRoute.'.index',$baseParams)); ?>">Reset</a></form>
    <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('decision')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('decision'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
    <section class="table-shell"><div class="overflow-x-auto"><table class="min-w-[850px] w-full"><caption class="sr-only"><?php echo e($status==='accepted' ? 'Peserta aktif yang diterima' : 'Daftar aplikasi Program'); ?></caption><thead><tr><th>Pemohon / Portfolio</th><th>Status</th><th>Catatan</th><th>Keputusan seleksi</th></tr></thead><tbody>
        <?php $__empty_1 = true; $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php ($visibility=$application->user->profileVisibility); ?>
        <tr class="border-t border-border"><td class="align-top"><?php if (isset($component)) { $__componentOriginal525f984c6bf98c494ca20fdba7c09630 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal525f984c6bf98c494ca20fdba7c09630 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.author-identity','data' => ['author' => $application->user]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.author-identity'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['author' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($application->user)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal525f984c6bf98c494ca20fdba7c09630)): ?>
<?php $attributes = $__attributesOriginal525f984c6bf98c494ca20fdba7c09630; ?>
<?php unset($__attributesOriginal525f984c6bf98c494ca20fdba7c09630); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal525f984c6bf98c494ca20fdba7c09630)): ?>
<?php $component = $__componentOriginal525f984c6bf98c494ca20fdba7c09630; ?>
<?php unset($__componentOriginal525f984c6bf98c494ca20fdba7c09630); ?>
<?php endif; ?><p class="mt-2 text-xs text-text-muted"><?php echo e($application->created_at->timezone(config('sipora.display_timezone'))->format('d M Y H:i')); ?> WIB</p>
        <?php if($visibility?->is_profile_public && $visibility->show_interests): ?><p class="mt-2 max-w-64 text-xs text-text-secondary">Minat: <?php echo e($application->user->interests->pluck('name')->join(', ') ?: 'Belum diisi'); ?></p><?php endif; ?>
        <?php if($visibility?->is_profile_public && $visibility->show_skills): ?><p class="mt-2 max-w-64 text-xs text-text-secondary">Skill: <?php echo e($application->user->skills->pluck('name')->join(', ') ?: 'Belum diisi'); ?></p><?php endif; ?></td>
        <td class="align-top"><?php if (isset($component)) { $__componentOriginalada223afdb9ef419aff51c89a95f5cb7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalada223afdb9ef419aff51c89a95f5cb7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.program.application-status','data' => ['status' => $application->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('program.application-status'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($application->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalada223afdb9ef419aff51c89a95f5cb7)): ?>
<?php $attributes = $__attributesOriginalada223afdb9ef419aff51c89a95f5cb7; ?>
<?php unset($__attributesOriginalada223afdb9ef419aff51c89a95f5cb7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalada223afdb9ef419aff51c89a95f5cb7)): ?>
<?php $component = $__componentOriginalada223afdb9ef419aff51c89a95f5cb7; ?>
<?php unset($__componentOriginalada223afdb9ef419aff51c89a95f5cb7); ?>
<?php endif; ?></td><td class="max-w-56 whitespace-pre-line align-top text-sm"><?php echo e($application->review_notes ?: '—'); ?></td><td class="align-top">
        <?php if($program->execution_status==='running' && in_array($application->status,['submitted','under_review','waitlisted'],true)): ?>
            <form class="grid min-w-56 gap-2" method="POST" action="<?php echo e(route($baseRoute.'.review',array_merge($baseParams,[$application]))); ?>" data-confirm-title="Simpan keputusan seleksi?" data-confirm="Keputusan akan memperbarui status pemohon. Penerimaan memakai kuota Program."><?php echo csrf_field(); ?><input type="hidden" name="expected_status" value="<?php echo e($application->status); ?>">
                <label><span class="sr-only">Keputusan untuk pemohon <?php echo e($loop->iteration); ?></span><select class="field" name="decision" required><option value="">Pilih keputusan</option><?php if($application->status!=='under_review'): ?><option value="under_review">Tinjau</option><?php endif; ?><option value="accepted">Terima</option><?php if($application->status!=='waitlisted'): ?><option value="waitlisted">Daftar tunggu</option><?php endif; ?><option value="rejected">Tolak</option></select></label><label><span class="sr-only">Catatan keputusan pemohon <?php echo e($loop->iteration); ?></span><input class="field" name="notes" maxlength="2000" placeholder="Catatan (opsional)"></label><button class="btn-outline">Simpan Keputusan</button>
            </form>
        <?php else: ?><p class="text-xs text-text-secondary">Status tersimpan; tidak ada tindakan seleksi tersedia.</p><?php endif; ?></td></tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="4"><?php if (isset($component)) { $__componentOriginal3607a477fdef7402bc742abad5df9c51 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3607a477fdef7402bc742abad5df9c51 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.empty-state','data' => ['title' => 'Belum ada pendaftar','description' => 'Pendaftaran yang sesuai filter akan tampil di sini.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Belum ada pendaftar','description' => 'Pendaftaran yang sesuai filter akan tampil di sini.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $attributes = $__attributesOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__attributesOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $component = $__componentOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__componentOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?></td></tr><?php endif; ?>
    </tbody></table></div></section><?php echo e($applications->links()); ?>

    <p class="text-xs leading-6 text-text-secondary">Hanya status Diterima menjadi peserta aktif. Daftar Tunggu dan Tidak Lolos tidak memakai kuota. Informasi Portfolio mengikuti visibilitas pemilik; dokumen identitas dan evidence privat tidak ditampilkan.</p>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($organization ? 'layouts.manager' : 'layouts.verifier', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/programs/applications.blade.php ENDPATH**/ ?>