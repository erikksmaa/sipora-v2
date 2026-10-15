<?php $__env->startSection('title', 'Edit Profil · SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<div class="mx-auto max-w-4xl space-y-5">
    <?php if (isset($component)) { $__componentOriginal8e0a96c106aaf9b655be7405167c4255 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8e0a96c106aaf9b655be7405167c4255 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.page-header','data' => ['eyebrow' => 'Profil','title' => 'Perkenalkan dirimu','description' => 'Profil berisi informasi presentasi diri. Data lahir, domisili, dan kontak dapat diperbarui di Biodata.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Profil','title' => 'Perkenalkan dirimu','description' => 'Profil berisi informasi presentasi diri. Data lahir, domisili, dan kontak dapat diperbarui di Biodata.']); ?>
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
    <section class="sipora-card">
        <form action="<?php echo e(route('youth.profile.update')); ?>" method="post" enctype="multipart/form-data" class="space-y-5">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <label><span class="field-label">Status saat ini</span><select class="field" name="occupation_status" required><option value="">Pilih</option><?php $__currentLoopData = ['student'=>'Pelajar','university_student'=>'Mahasiswa','worker'=>'Pekerja','entrepreneur'=>'Wirausaha','unemployed'=>'Belum bekerja','other'=>'Lainnya']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($value); ?>" <?php if(old('occupation_status', $user->profile?->occupation_status)===$value): echo 'selected'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><?php $__errorArgs = ['occupation_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
            <label><span class="field-label">Pekerjaan / institusi <span class="font-normal">(opsional)</span></span><input class="field" name="occupation_title" value="<?php echo e(old('occupation_title', $user->profile?->occupation_title)); ?>" maxlength="160"><?php $__errorArgs = ['occupation_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
            <label><span class="field-label">Bio singkat</span><textarea class="field min-h-32" name="bio" maxlength="2000" required placeholder="Ceritakan minat dan tujuanmu"><?php echo e(old('bio', $user->profile?->bio)); ?></textarea><?php $__errorArgs = ['bio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
            <label><span class="field-label">Foto profil publik <span class="font-normal">(opsional, maks. 2 MB)</span></span><input class="field" type="file" name="profile_photo" accept="image/jpeg,image/png,image/webp"><?php $__errorArgs = ['profile_photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
            <div class="flex flex-wrap justify-between gap-3"><a class="btn-secondary" href="<?php echo e(route('youth.biodata.edit')); ?>">Lihat Biodata</a><button class="btn" type="submit">Simpan profil</button></div>
        </form>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.youth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/youth/profile/edit.blade.php ENDPATH**/ ?>