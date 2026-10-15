<?php $__env->startSection('title', 'Password Sementara · SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<section class="sipora-card mx-auto max-w-2xl space-y-5" x-data="{copied:false}" x-init="window.addEventListener('pagehide',()=>{$refs.temporaryPassword.value=''})">
    <h1 class="text-2xl font-bold">Password sementara berhasil dibuat.</h1>
    <p class="text-sm text-text-secondary">Akun: <strong><?php echo e($user->name); ?></strong>. Pengguna wajib mengganti password setelah login. Password ini tidak akan dapat dilihat kembali setelah halaman ditutup.</p>
    <label class="block"><span class="field-label">Password sementara</span><input x-ref="temporaryPassword" class="field font-mono" type="text" readonly autocomplete="off" value="<?php echo e($temporaryPassword); ?>"></label>
    <p class="text-xs text-text-secondary">Sampaikan hanya kepada pemilik akun melalui saluran yang aman. Jangan bagikan melalui komentar atau log aplikasi.</p>
    <div class="flex flex-wrap gap-3"><button type="button" class="btn-primary" @click="navigator.clipboard.writeText($refs.temporaryPassword.value).then(()=>copied=true)"><span x-text="copied?'Tersalin':'Salin Password'">Salin Password</span></button><a class="btn-outline" href="<?php echo e(route('admin.internal-users.show',$user)); ?>" @click="$refs.temporaryPassword.value=''">Tutup</a></div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/admin/internal-users/temporary.blade.php ENDPATH**/ ?>