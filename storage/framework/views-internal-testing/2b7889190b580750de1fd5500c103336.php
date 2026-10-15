<?php $__env->startSection('title','Peserta · '.$activity->title); ?>
<?php $__env->startSection('manager-content'); ?>
<?php if (isset($component)) { $__componentOriginal8e0a96c106aaf9b655be7405167c4255 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8e0a96c106aaf9b655be7405167c4255 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.page-header','data' => ['eyebrow' => 'Manajemen peserta','title' => 'Peserta Activity','description' => $activity->title]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Manajemen peserta','title' => 'Peserta Activity','description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activity->title)]); ?><a class="btn-secondary" href="<?php echo e(route('manager.activities.show',[$organization,$activity])); ?>">Detail Activity</a><?php if (isset($component)) { $__componentOriginal846b770b82a03494b6267b1ec4369c07 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal846b770b82a03494b6267b1ec4369c07 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.activity.status-badge','data' => ['status' => $activity->execution_status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('activity.status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activity->execution_status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal846b770b82a03494b6267b1ec4369c07)): ?>
<?php $attributes = $__attributesOriginal846b770b82a03494b6267b1ec4369c07; ?>
<?php unset($__attributesOriginal846b770b82a03494b6267b1ec4369c07); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal846b770b82a03494b6267b1ec4369c07)): ?>
<?php $component = $__componentOriginal846b770b82a03494b6267b1ec4369c07; ?>
<?php unset($__componentOriginal846b770b82a03494b6267b1ec4369c07); ?>
<?php endif; ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8e0a96c106aaf9b655be7405167c4255)): ?>
<?php $attributes = $__attributesOriginal8e0a96c106aaf9b655be7405167c4255; ?>
<?php unset($__attributesOriginal8e0a96c106aaf9b655be7405167c4255); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e0a96c106aaf9b655be7405167c4255)): ?>
<?php $component = $__componentOriginal8e0a96c106aaf9b655be7405167c4255; ?>
<?php unset($__componentOriginal8e0a96c106aaf9b655be7405167c4255); ?>
<?php endif; ?>
<p class="mt-3 text-sm text-slate-500"><?php echo e($sessionCount); ?> sesi terjadwal.</p>
<div class="mt-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-4"><?php $__currentLoopData = ['pending'=>'Menunggu','accepted'=>'Diterima','rejected'=>'Ditolak','cancelled'=>'Dibatalkan']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="rounded-2xl bg-white p-5 shadow-sm"><p class="text-xs font-bold uppercase text-slate-500"><?php echo e($label); ?></p><p class="mt-2 text-3xl font-black text-[#14205c]"><?php echo e($counts[$key] ?? 0); ?></p></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div>
<div class="mt-5 flex flex-wrap items-center justify-between gap-3 rounded-2xl bg-white p-4 shadow-sm"><form class="flex flex-wrap gap-2"><select class="field !min-h-10" name="status" onchange="this.form.submit()"><?php $__currentLoopData = ['all'=>'Semua pendaftaran','pending'=>'Menunggu','accepted'=>'Diterima','rejected'=>'Ditolak','cancelled'=>'Dibatalkan']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($value); ?>" <?php if($status===$value): echo 'selected'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><select class="field !min-h-10" name="completion" onchange="this.form.submit()"><?php $__currentLoopData = ['all'=>'Semua penyelesaian','pending'=>'Belum diselesaikan','completed'=>'Selesai','no_show'=>'Tidak hadir']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($value); ?>" <?php if($completion===$value): echo 'selected'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></form><p class="text-sm text-slate-600">Kuota diterima: <strong><?php echo e($counts['accepted'] ?? 0); ?> / <?php echo e($activity->quota ?? '∞'); ?></strong></p></div>
<?php if($activity->execution_status !== 'completed'): ?><div class="mt-4 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">Keputusan penyelesaian peserta dibuka setelah eksekusi Activity berstatus selesai. Presensi tetap menjadi bukti dan tidak menentukan hasil secara otomatis.</div><?php endif; ?>
<div class="table-shell mt-5 overflow-x-auto"><table class="min-w-[850px] text-left text-sm"><thead><tr><th scope="col">Peserta</th><th scope="col">Pendaftaran</th><th scope="col">Bukti presensi</th><th scope="col">Penyelesaian</th><th scope="col">Tindakan</th></tr></thead><tbody>
<?php $__empty_1 = true; $__currentLoopData = $participants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $participant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr class="border-t border-slate-100 align-top"><td class="p-4"><p class="font-bold text-[#14205c]"><?php echo e($participant->user->profile?->full_name ?: $participant->user->name); ?></p><p class="text-xs text-slate-500"><?php echo e($participant->user->email); ?></p><p class="mt-1 text-xs text-slate-500">Daftar <?php echo e($participant->requested_at->translatedFormat('d M Y H:i')); ?></p></td><td class="p-4"><?php if (isset($component)) { $__componentOriginal5ea0e4c370a0edd3f192dc4a63843075 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ea0e4c370a0edd3f192dc4a63843075 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.activity.registration-badge','data' => ['status' => $participant->registration_status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('activity.registration-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($participant->registration_status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5ea0e4c370a0edd3f192dc4a63843075)): ?>
<?php $attributes = $__attributesOriginal5ea0e4c370a0edd3f192dc4a63843075; ?>
<?php unset($__attributesOriginal5ea0e4c370a0edd3f192dc4a63843075); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5ea0e4c370a0edd3f192dc4a63843075)): ?>
<?php $component = $__componentOriginal5ea0e4c370a0edd3f192dc4a63843075; ?>
<?php unset($__componentOriginal5ea0e4c370a0edd3f192dc4a63843075); ?>
<?php endif; ?><?php if($participant->registration_notes): ?><p class="mt-2 max-w-xs text-xs text-slate-500"><?php echo e($participant->registration_notes); ?></p><?php endif; ?></td><td class="p-4"><p class="font-semibold text-emerald-700"><?php echo e($participant->present_count); ?> hadir</p><p class="text-xs text-slate-500"><?php echo e($participant->absent_count); ?> absen · <?php echo e($participant->excused_count); ?> izin · <?php echo e($sessionCount); ?> sesi</p><p class="mt-1 text-[11px] text-slate-400">Bukti pendukung, bukan keputusan otomatis.</p></td><td class="p-4"><?php if (isset($component)) { $__componentOriginal4cc5583ac6f3cb736f99770e4859859b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4cc5583ac6f3cb736f99770e4859859b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.activity.completion-badge','data' => ['status' => $participant->completion_status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('activity.completion-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($participant->completion_status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4cc5583ac6f3cb736f99770e4859859b)): ?>
<?php $attributes = $__attributesOriginal4cc5583ac6f3cb736f99770e4859859b; ?>
<?php unset($__attributesOriginal4cc5583ac6f3cb736f99770e4859859b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4cc5583ac6f3cb736f99770e4859859b)): ?>
<?php $component = $__componentOriginal4cc5583ac6f3cb736f99770e4859859b; ?>
<?php unset($__componentOriginal4cc5583ac6f3cb736f99770e4859859b); ?>
<?php endif; ?><?php if($participant->completed_at): ?><p class="mt-2 text-xs text-slate-500"><?php echo e($participant->completed_at->translatedFormat('d M Y H:i')); ?></p><?php endif; ?></td><td class="p-4">
<?php if($participant->registration_status==='pending'): ?><div class="flex flex-wrap gap-2"><form method="POST" action="<?php echo e(route('manager.activities.participants.review',[$organization,$activity,$participant])); ?>"><?php echo csrf_field(); ?><input type="hidden" name="decision" value="accepted"><button class="btn-primary">Terima</button></form><form method="POST" action="<?php echo e(route('manager.activities.participants.review',[$organization,$activity,$participant])); ?>" class="flex gap-2"><?php echo csrf_field(); ?><input type="hidden" name="decision" value="rejected"><input class="field !min-h-10 w-40" name="notes" required maxlength="3000" placeholder="Alasan penolakan"><button class="btn-secondary !text-red-700">Tolak</button></form></div>
<?php elseif($participant->registration_status==='accepted' && $participant->completion_status==='pending' && $activity->execution_status==='completed'): ?><div class="flex flex-wrap gap-2"><form method="POST" action="<?php echo e(route('manager.activities.participants.complete',[$organization,$activity,$participant])); ?>"><?php echo csrf_field(); ?><input type="hidden" name="decision" value="completed"><button class="btn-primary">Selesaikan</button></form><form method="POST" action="<?php echo e(route('manager.activities.participants.complete',[$organization,$activity,$participant])); ?>" data-confirm="Tandai peserta ini tidak hadir?"><?php echo csrf_field(); ?><input type="hidden" name="decision" value="no_show"><button class="btn-secondary">Tidak hadir</button></form></div>
<?php elseif($participant->registration_status==='accepted' && $participant->completion_status==='completed'): ?>
    <?php if($participant->certificate): ?><a class="btn-secondary" href="<?php echo e(route('certificates.verify',$participant->certificate->verification_code)); ?>" target="_blank" rel="noopener">Lihat sertifikat</a>
    <?php elseif($activity->certificate_enabled): ?><form method="POST" action="<?php echo e(route('manager.activities.participants.certificate',[$organization,$activity,$participant])); ?>" data-confirm="Terbitkan sertifikat untuk peserta ini?"><?php echo csrf_field(); ?><button class="btn-primary">Terbitkan sertifikat</button></form>
    <?php else: ?><span class="text-xs text-slate-400">Sertifikat tidak diaktifkan</span><?php endif; ?>
<?php else: ?><span class="text-xs text-slate-400">Tidak ada tindakan</span><?php endif; ?></td></tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="5" class="p-10 text-center text-slate-500">Belum ada peserta pada filter ini.</td></tr><?php endif; ?></tbody></table></div><div class="mt-5"><?php echo e($participants->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.manager', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/manager/participants/index.blade.php ENDPATH**/ ?>