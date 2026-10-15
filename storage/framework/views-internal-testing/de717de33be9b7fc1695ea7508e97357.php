<?php $__env->startSection('title', ($target ? 'Edit' : 'Tambah').' Akun Internal · SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<div class="mx-auto max-w-2xl space-y-6">
    <?php if (isset($component)) { $__componentOriginal91a231a9270579fa1ae9246bd51fb785 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91a231a9270579fa1ae9246bd51fb785 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.page-header','data' => ['eyebrow' => 'Pengguna Internal','title' => $target ? 'Edit Akun Internal' : 'Tambah Akun Internal','description' => 'Role akun existing tidak dapat diubah melalui formulir ini.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Pengguna Internal','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($target ? 'Edit Akun Internal' : 'Tambah Akun Internal'),'description' => 'Role akun existing tidak dapat diubah melalui formulir ini.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $attributes = $__attributesOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $component = $__componentOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__componentOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?>
    <form method="POST" action="<?php echo e($target ? route('admin.internal-users.update',$target) : route('admin.internal-users.store')); ?>" class="sipora-card space-y-5" x-data="{role:<?php echo \Illuminate\Support\Js::from(old('role',$target?->hasRole('admin') ? 'admin' : 'verifier'))->toHtml() ?>}" <?php if(!$target): ?> data-confirm="Buat akun internal dengan role yang dipilih? Pengguna wajib mengganti password sementara setelah login." data-confirm-title="Buat akun internal?" <?php endif; ?>>
        <?php echo csrf_field(); ?> <?php if($target): ?> <?php echo method_field('PATCH'); ?> <?php endif; ?>
        <label class="block"><span class="field-label">Nama</span><input class="field" name="name" value="<?php echo e(old('name',$target?->name)); ?>" maxlength="255" required></label>
        <label class="block"><span class="field-label">Email</span><input class="field" name="email" type="email" value="<?php echo e(old('email',$target?->email)); ?>" maxlength="255" required></label>
        <?php if(!$target): ?><label class="block"><span class="field-label">Role</span><select class="field" name="role" x-model="role"><option value="verifier">Verifier</option><option value="admin">Administrator</option></select></label><?php else: ?><p class="text-sm text-text-secondary">Role tetap: <?php echo e($target->hasRole('admin')?'Administrator':'Verifier'); ?>. Perubahan email menghapus status verifikasi email dan mengakhiri sesi akun tersebut.</p><?php endif; ?>
        <label class="block" x-show="role==='admin'"><span class="field-label">Konfirmasi password Administrator Anda</span><input class="field" name="admin_password" type="password" autocomplete="current-password" maxlength="72" :disabled="role!=='admin'" :required="role==='admin'"></label>
        <?php if(!$target): ?><p class="text-sm text-text-secondary">Password sementara acak ditampilkan sekali setelah akun dibuat. Pengguna menentukan password permanennya sendiri. Email akun belum terverifikasi sampai tautan verifikasi dibuka.</p><?php endif; ?>
        <div class="flex flex-wrap gap-3"><button class="btn-primary"><?php echo e($target?'Simpan Perubahan':'Buat Akun'); ?></button><a class="btn-outline" href="<?php echo e(route('admin.internal-users.index')); ?>">Kembali</a></div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/admin/internal-users/form.blade.php ENDPATH**/ ?>