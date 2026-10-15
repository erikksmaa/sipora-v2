<?php ($genderLabels = ['male' => 'Laki-laki', 'female' => 'Perempuan', 'other' => 'Lainnya', 'prefer_not_to_say' => 'Memilih tidak menyebutkan', 'unknown' => 'Belum diisi']); ?>
<?php ($statusLabels = ['scheduled' => 'Terjadwal', 'ongoing' => 'Berlangsung', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan', 'active' => 'Aktif', 'inactive' => 'Tidak aktif', 'planned' => 'Direncanakan', 'running' => 'Berjalan']); ?>
<div class="stats-rows" aria-label="Rincian data <?php echo e($kind); ?>">
    <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div><span><?php echo e($kind === 'gender' ? ($genderLabels[$row['label']] ?? $row['label']) : ($kind === 'status' ? ($statusLabels[$row['label']] ?? $row['label']) : $row['label'])); ?></span><strong><?php echo e($row['count'] === null ? '<'.$threshold : number_format($row['count'])); ?></strong></div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p>Belum ada data untuk kategori ini.</p>
    <?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/public/statistics/rows.blade.php ENDPATH**/ ?>