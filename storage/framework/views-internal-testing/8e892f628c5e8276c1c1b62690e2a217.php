<?php $__env->startSection('title', 'Bukti Portfolio · SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<?php
    $title = match ($type) {
        'education' => $item->institution_name,
        'organization' => $item->organization_name,
        default => $item->title,
    };
    $hasEvidence = $type === 'achievement' ? filled($item->evidence_path) : $evidence !== null;
    $verified = $type === 'achievement' && $item->verification_status === 'verified';
?>
<div class="mx-auto max-w-3xl space-y-6">
    <?php if (isset($component)) { $__componentOriginal8e0a96c106aaf9b655be7405167c4255 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8e0a96c106aaf9b655be7405167c4255 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.page-header','data' => ['eyebrow' => 'Portfolio · Bukti Pendukung','title' => $title,'description' => 'Bukti ini privat. Mengunggah file tidak mengubah klaim menjadi catatan terverifikasi SIPORA.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Portfolio · Bukti Pendukung','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($title),'description' => 'Bukti ini privat. Mengunggah file tidak mengubah klaim menjadi catatan terverifikasi SIPORA.']); ?>
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
    <section class="sipora-card space-y-4">
        <div class="flex flex-wrap gap-2"><span class="status-badge"><?php echo e($verified ? 'SIPORA Verified' : 'Self-reported'); ?></span><span class="status-badge"><?php echo e($hasEvidence ? 'Bukti terlampir' : 'Tanpa bukti'); ?></span></div>
        <dl class="grid gap-4 text-sm sm:grid-cols-2">
            <div><dt class="font-bold">Jenis catatan</dt><dd><?php echo e(match ($type) { 'education' => 'Pendidikan', 'organization' => 'Pengalaman organisasi', default => 'Prestasi' }); ?></dd></div>
            <div><dt class="font-bold">Visibilitas catatan</dt><dd><?php echo e($visible ? 'Mengikuti pengaturan Portfolio publik' : 'Disembunyikan dari publik'); ?></dd></div>
            <div><dt class="font-bold">Visibilitas file</dt><dd>Privat, hanya akun pemilik</dd></div>
            <?php if($evidence): ?><div><dt class="font-bold">Nama file</dt><dd class="break-all"><?php echo e($evidence->original_name); ?></dd></div><div><dt class="font-bold">Jenis dan ukuran</dt><dd><?php echo e($evidence->mime_type); ?> · <?php echo e(number_format($evidence->size_bytes / 1024, 1)); ?> KB</dd></div><?php endif; ?>
            <?php if($achievementEvidenceInfo): ?><div><dt class="font-bold">Jenis dan ukuran bukti prestasi</dt><dd><?php echo e($achievementEvidenceInfo['mime_type']); ?> · <?php echo e(number_format($achievementEvidenceInfo['size_bytes'] / 1024, 1)); ?> KB</dd></div><?php endif; ?>
        </dl>
        <?php if($hasEvidence): ?><a class="btn-secondary" href="<?php echo e(route('youth.portfolio.evidence.file', [$type, $item->uuid()])); ?>" target="_blank" rel="noopener">Lihat / unduh bukti privat</a><?php endif; ?>
    </section>
    <?php if (! ($verified)): ?>
        <section class="sipora-card space-y-4">
            <h2 class="text-lg font-extrabold"><?php echo e($hasEvidence ? 'Ganti bukti' : 'Lampirkan bukti'); ?></h2>
            <p class="text-sm text-text-secondary">PDF, JPG, atau PNG. Maksimum 5 MB. File tetap privat dan tidak memperoleh status verifikasi otomatis.</p>
            <form method="post" enctype="multipart/form-data" action="<?php echo e(route('youth.portfolio.evidence.store', [$type, $item->uuid()])); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>
                <label><span class="field-label">File bukti</span><input class="field" type="file" name="evidence" accept=".pdf,.jpg,.jpeg,.png" required><?php $__errorArgs = ['evidence'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
                <button class="btn" type="submit">Simpan bukti privat</button>
            </form>
            <?php if($hasEvidence): ?><form method="post" action="<?php echo e(route('youth.portfolio.evidence.destroy', [$type, $item->uuid()])); ?>" data-confirm="Hapus bukti ini?"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="btn-secondary" type="submit">Hapus bukti</button></form><?php endif; ?>
        </section>
    <?php endif; ?>
    <a class="btn-secondary" href="<?php echo e(route('youth.profile.show')); ?>">Kembali ke Profil</a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.youth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/youth/portfolio-evidence.blade.php ENDPATH**/ ?>