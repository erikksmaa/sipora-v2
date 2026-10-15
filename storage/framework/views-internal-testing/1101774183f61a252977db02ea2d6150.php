<?php $__env->startSection('title', 'Presensi · '.$activity->title); ?>
<?php $__env->startSection('manager-content'); ?>
<?php
    $statusLabels = ['present' => 'Hadir', 'absent' => 'Tidak Hadir', 'excused' => 'Izin'];
    $initialStatuses = $participants->mapWithKeys(function ($participant) use ($statusLabels) {
        $status = old('attendance.'.$participant->uuid().'.status', $participant->attendances->first()?->attendance_status ?? 'present');
        return [$participant->uuid() => array_key_exists($status, $statusLabels) ? $status : 'present'];
    })->all();
    $hasSavedAttendance = $counts->sum() > 0;
?>

<?php if (isset($component)) { $__componentOriginal8e0a96c106aaf9b655be7405167c4255 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8e0a96c106aaf9b655be7405167c4255 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.page-header','data' => ['eyebrow' => 'Presensi & kehadiran','title' => 'Manajemen Kehadiran','description' => $activity->title.' · Sesi '.$session->session_number.' — '.$session->title]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Presensi & kehadiran','title' => 'Manajemen Kehadiran','description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activity->title.' · Sesi '.$session->session_number.' — '.$session->title)]); ?>
    <a class="btn-secondary" href="<?php echo e(route('manager.activities.sessions.index', [$organization, $activity])); ?>">Semua sesi</a>
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

<div class="mt-4 rounded-xl border border-border bg-surface-soft px-4 py-3 text-sm font-semibold text-text-primary">
    <?php echo e($session->title); ?> · <?php echo e($session->start_at->translatedFormat('d M Y, H:i')); ?>–<?php echo e($session->end_at->format('H:i')); ?>

</div>

<div class="mt-5" x-data="bulkAttendance(<?php echo \Illuminate\Support\Js::from($initialStatuses)->toHtml() ?>)">
    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4" aria-live="polite">
        <div class="rounded-xl border border-border bg-white p-4 shadow-sm">
            <p class="text-xs font-bold uppercase text-text-muted">Peserta diterima</p>
            <p class="mt-2 text-2xl font-extrabold text-text-primary"><?php echo e($acceptedCount); ?></p>
        </div>
        <?php $__currentLoopData = $statusLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="rounded-xl border border-border bg-white p-4 shadow-sm">
                <p class="text-xs font-bold uppercase text-text-muted"><?php echo e($label); ?></p>
                <p class="mt-2 text-2xl font-extrabold text-text-primary" x-text="count('<?php echo e($status); ?>')"><?php echo e($status === 'present' ? $acceptedCount - ($counts['absent'] ?? 0) - ($counts['excused'] ?? 0) : ($counts[$status] ?? 0)); ?></p>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <form class="mt-5" method="POST" action="<?php echo e(route('manager.activities.attendance.bulk-update', [$organization, $activity, $session])); ?>"
          <?php if($hasSavedAttendance): ?> data-confirm="Presensi yang sudah tersimpan pada sesi ini akan diperbarui." data-confirm-title="Perbarui presensi?" data-confirm-button="Ya, simpan" <?php endif; ?>>
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="rounded-xl border border-border bg-white p-4 shadow-sm">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <label for="session-selector" class="mb-2 block text-xs font-bold uppercase text-text-muted">Pilih sesi</label>
                    <select id="session-selector" class="field !min-h-10 w-full md:w-auto" onchange="if (this.value) window.location = this.value">
                        <?php $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e(route('manager.activities.attendance.index', [$organization, $activity, $option])); ?>" <?php if($option->is($session)): echo 'selected'; endif; ?>>Sesi <?php echo e($option->session_number); ?> — <?php echo e($option->title); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <?php if($acceptedCount): ?>
                    <button type="button" class="btn-secondary !min-h-10" x-on:click="markAllPresent()">Tandai Semua Hadir</button>
                <?php endif; ?>
            </div>
            <p class="mt-3 text-xs text-text-muted"><?php echo e($unmarkedCount); ?> peserta belum memiliki presensi tersimpan. Pilihan Hadir baru disimpan setelah Anda menekan Simpan Presensi.</p>
        </div>

        <div class="mt-5 overflow-hidden rounded-xl border border-border bg-white shadow-sm">
            <div class="hidden gap-4 border-b border-border bg-surface-soft px-5 py-3 text-xs font-bold uppercase text-text-muted md:grid md:grid-cols-[minmax(0,1fr)_11rem_minmax(0,1.5fr)]">
                <span>Peserta</span><span>Status kehadiran</span><span>Catatan</span>
            </div>
            <?php $__empty_1 = true; $__currentLoopData = $participants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $participant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php ($attendance = $participant->attendances->first()); ?>
                <div class="grid gap-3 border-b border-border p-4 last:border-0 md:grid-cols-[minmax(0,1fr)_11rem_minmax(0,1.5fr)] md:items-center md:gap-4 md:px-5">
                    <div class="min-w-0">
                        <p class="font-bold text-text-primary"><?php echo e($participant->user->profile?->full_name ?: $participant->user->name); ?></p>
                        <p class="text-xs text-text-muted">Peserta diterima</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-bold text-text-muted md:sr-only" for="status-<?php echo e($participant->uuid()); ?>">Status kehadiran <?php echo e($participant->user->name); ?></label>
                        <select id="status-<?php echo e($participant->uuid()); ?>" class="field !min-h-10 w-full" name="attendance[<?php echo e($participant->uuid()); ?>][status]" x-model="statuses['<?php echo e($participant->uuid()); ?>']" required>
                            <?php $__currentLoopData = $statusLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>" <?php if($initialStatuses[$participant->uuid()] === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-bold text-text-muted md:sr-only" for="notes-<?php echo e($participant->uuid()); ?>">Catatan <?php echo e($participant->user->name); ?></label>
                        <input id="notes-<?php echo e($participant->uuid()); ?>" class="field !min-h-10 w-full" name="attendance[<?php echo e($participant->uuid()); ?>][notes]" maxlength="3000" value="<?php echo e(old('attendance.'.$participant->uuid().'.notes', $attendance?->notes)); ?>" placeholder="Catatan opsional">
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="p-10 text-center">
                    <p class="font-bold text-text-primary">Belum ada peserta diterima</p>
                    <p class="mt-2 text-sm text-text-muted">Peserta pending, ditolak, atau dibatalkan tidak ditampilkan sebagai target presensi.</p>
                </div>
            <?php endif; ?>
        </div>
        <?php if($acceptedCount): ?>
            <div class="mt-5 flex justify-end"><button type="submit" class="btn-primary">Simpan Presensi</button></div>
        <?php endif; ?>
    </form>
</div>

<div class="mt-6 rounded-xl border border-border bg-surface-soft p-5 text-sm text-text-secondary">
    <p class="font-bold text-text-primary">Presensi adalah bukti partisipasi per sesi.</p>
    <p class="mt-1">Pencatatan di halaman ini tidak mengubah status penyelesaian peserta secara otomatis.</p>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.manager', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/manager/attendance/index.blade.php ENDPATH**/ ?>