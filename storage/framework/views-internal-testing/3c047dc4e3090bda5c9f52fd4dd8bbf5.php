<?php $__env->startSection('title', 'Detail Akun Internal · SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <?php if (isset($component)) { $__componentOriginal91a231a9270579fa1ae9246bd51fb785 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91a231a9270579fa1ae9246bd51fb785 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.page-header','data' => ['eyebrow' => 'Pengguna Internal','title' => $target->name,'description' => $target->email]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Pengguna Internal','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($target->name),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($target->email)]); ?> <?php $__env->slot('actions', null, []); ?> <a class="btn-outline" href="<?php echo e(route('admin.internal-users.index')); ?>">Kembali</a><?php if($canManage): ?><a class="btn-primary" href="<?php echo e(route('admin.internal-users.edit',$target)); ?>">Edit Akun</a><?php endif; ?> <?php $__env->endSlot(); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $attributes = $__attributesOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $component = $__componentOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__componentOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?>
    <section class="sipora-card flex flex-wrap gap-3"><?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['tone' => 'info']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => 'info']); ?><?php echo e($target->hasRole('admin')?'Administrator':'Verifier'); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?><?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['tone' => $target->internal_active?'success':'danger']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($target->internal_active?'success':'danger')]); ?><?php echo e($target->internal_active?'Aktif':'Nonaktif'); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?><?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['tone' => $target->hasVerifiedEmail()?'success':'warning']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($target->hasVerifiedEmail()?'success':'warning')]); ?><?php echo e($target->hasVerifiedEmail()?'Email terverifikasi':'Email belum terverifikasi'); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?><?php if($target->must_change_password): ?><?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['tone' => 'warning']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => 'warning']); ?>Wajib ganti password <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?><?php endif; ?></section>
    <?php if($canManage): ?>
    <div class="grid gap-5 lg:grid-cols-2">
        <form method="POST" action="<?php echo e(route('admin.internal-users.reset-link',$target)); ?>" class="sipora-card space-y-4"><?php echo csrf_field(); ?><h2 class="text-lg font-bold">Kirim Link Reset Password</h2><p class="text-sm text-text-secondary">Pengguna memilih password baru melalui tautan Laravel yang berlaku sementara dan hanya dapat digunakan sekali.</p><?php echo $__env->make('admin.internal-users.admin-password', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><button class="btn-primary">Kirim Link Reset Password</button></form>
        <form method="POST" action="<?php echo e(route('admin.internal-users.temporary',$target)); ?>" class="sipora-card space-y-4" data-confirm-title="Reset password sementara?" data-confirm="Sesi aktif akan dihentikan. Pengguna wajib mengganti password setelah login. Password sementara ditampilkan sekali."><?php echo csrf_field(); ?><h2 class="text-lg font-bold">Password Sementara</h2><p class="text-sm text-text-secondary">Gunakan jika akses perlu dipulihkan langsung. Password permanen tetap ditentukan pengguna.</p><?php echo $__env->make('admin.internal-users.admin-password', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><button class="btn-outline">Reset dengan Password Sementara</button></form>
        <?php if(!$target->hasVerifiedEmail()): ?><form method="POST" action="<?php echo e(route('admin.internal-users.verification',$target)); ?>" class="sipora-card space-y-4"><?php echo csrf_field(); ?><h2 class="text-lg font-bold">Verifikasi Email</h2><p class="text-sm text-text-secondary">Kirim ulang tautan verifikasi. Status verifikasi tidak dapat diubah secara manual.</p><?php echo $__env->make('admin.internal-users.admin-password', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><button class="btn-outline">Kirim Ulang Verifikasi Email</button></form><?php endif; ?>
        <form method="POST" action="<?php echo e(route('admin.internal-users.active',$target)); ?>" class="sipora-card space-y-4" data-confirm-title="Ubah status akun?" data-confirm="<?php echo e($target->internal_active?'Akun tidak dapat masuk dan sesi aktif akan dihentikan. Riwayat review tetap disimpan.':'Aktifkan kembali akses akun internal ini?'); ?>"><?php echo csrf_field(); ?><input type="hidden" name="active" value="<?php echo e($target->internal_active?'0':'1'); ?>"><h2 class="text-lg font-bold">Status Akun</h2><p class="text-sm text-text-secondary">Akun dinonaktifkan tanpa menghapus kepemilikan review, evaluasi, atau log aktivitas.</p><?php echo $__env->make('admin.internal-users.admin-password', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><button class="btn-outline"><?php echo e($target->internal_active?'Nonaktifkan Akun':'Aktifkan Akun'); ?></button></form>
    </div>
    <?php else: ?><section class="sipora-card"><p class="text-sm text-text-secondary">Akun Anda sendiri dilindungi dari tindakan administrasi ini.</p><a class="btn-primary mt-4" href="<?php echo e(route('admin.account.password.edit')); ?>">Kelola Password Saya</a></section><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/admin/internal-users/show.blade.php ENDPATH**/ ?>