<?php $__env->startSection('title', 'Buat Pengajuan Komunitas · SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<div class="mx-auto max-w-4xl"><div class="mb-6"><a class="text-sm font-bold text-[#243378]" href="<?php echo e(route('youth.communities.index')); ?>">← Kembali ke pengajuan</a><h1 class="mt-3 text-3xl font-extrabold text-[#243378]">Buat draft komunitas</h1><p class="mt-2 text-slate-600">Simpan informasi secara bertahap. Kamu dapat meninjaunya sebelum dikirim.</p></div><?php echo $__env->make('youth.communities._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.youth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/youth/communities/create.blade.php ENDPATH**/ ?>