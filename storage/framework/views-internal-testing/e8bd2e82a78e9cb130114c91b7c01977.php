<?php $__env->startSection('title', 'Verifikasi Identitas · SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<div class="mx-auto max-w-4xl space-y-6">
    <div>
        <a class="text-sm font-bold text-[#243378]" href="<?php echo e(route('youth.onboarding')); ?>">← Kembali ke tahapan akun</a>
        <p class="mt-3 text-xs font-extrabold uppercase tracking-widest text-accent-600">Aktivasi Akun · Identitas dan Konfirmasi</p>
        <h1 class="mt-2 text-3xl font-extrabold">Verifikasi Identitas Youth</h1>
        <p class="mt-2 text-slate-600">Verifikasi identitas berbeda dari login Google dan verifikasi email. Dokumenmu disimpan secara privat dan hanya dapat diakses oleh admin SIPORA yang berwenang.</p>
    </div>

    <?php if($latestIdentityNotification): ?>
        <section class="rounded-2xl border border-indigo-200 bg-indigo-50 p-5" role="status">
            <p class="text-xs font-bold uppercase tracking-wider text-[#3346a8]">Notifikasi terbaru</p>
            <h2 class="mt-2 font-extrabold text-[#18245c]"><?php echo e($latestIdentityNotification->title); ?></h2>
            <p class="mt-1 text-sm text-indigo-900"><?php echo e($latestIdentityNotification->body); ?></p>
        </section>
    <?php endif; ?>

    
    <section class="sipora-card">
        <div class="flex items-center gap-4">
            <?php
                $statusColor = match($identity->verification_status) {
                    'verified'  => 'bg-green-100 text-green-700',
                    'pending'   => 'bg-amber-100 text-amber-700',
                    'rejected'  => 'bg-red-100 text-red-700',
                    'revision'  => 'bg-blue-100 text-blue-700',
                    default     => 'bg-slate-100 text-slate-600',
                };
                $statusLabel = match($identity->verification_status) {
                    'verified'  => 'Terverifikasi',
                    'pending'   => 'Sedang ditinjau',
                    'rejected'  => 'Ditolak',
                    'revision'  => 'Perlu revisi',
                    default     => 'Belum diverifikasi',
                };
            ?>
            <span class="grid size-14 place-items-center rounded-full <?php echo e($statusColor); ?> text-2xl font-bold">
                <?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => $identity->verification_status === 'verified' ? 'badge-check' : ($identity->verification_status === 'rejected' ? 'x' : 'clock'),'class' => 'size-7']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($identity->verification_status === 'verified' ? 'badge-check' : ($identity->verification_status === 'rejected' ? 'x' : 'clock')),'class' => 'size-7']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
            </span>
            <div>
                <p class="text-sm font-semibold text-slate-500">Status verifikasi identitas</p>
                <h2 class="text-xl font-bold"><?php echo e($statusLabel); ?></h2>
                <?php if($latestVerification): ?>
                    <p class="mt-1 text-xs text-slate-400">Terakhir diajukan: <?php echo e($latestVerification->submitted_at->diffForHumans()); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <?php if($identity->verification_status === 'verified'): ?>
            <div class="mt-5 rounded-xl bg-green-50 p-4 text-sm text-green-800">
                Identitas Anda telah terverifikasi secara resmi. Tidak ada aksi lebih lanjut yang diperlukan.
            </div>
        <?php elseif($identity->verification_status === 'pending'): ?>
            <div class="mt-5 rounded-xl bg-amber-50 p-4 text-sm text-amber-800">
                Dokumen Anda sedang dalam antrean peninjauan oleh admin SIPORA. Anda akan mendapat notifikasi setelah proses selesai.
            </div>
        <?php elseif($identity->verification_status === 'rejected'): ?>
            <div class="mt-5 rounded-xl bg-red-50 p-4 text-sm text-red-800">
                Pengajuan sebelumnya ditolak.
                <?php if($latestVerification?->review_notes): ?>
                    <strong class="block mt-1">Alasan: <?php echo e($latestVerification->review_notes); ?></strong>
                <?php endif; ?>
                Silakan ajukan ulang dengan dokumen yang benar.
            </div>
        <?php elseif($identity->verification_status === 'revision'): ?>
            <div class="mt-5 rounded-xl bg-blue-50 p-4 text-sm text-blue-800">
                Pengajuan perlu diperbaiki sebelum dapat ditinjau kembali.
                <?php if($latestVerification?->review_notes): ?><strong class="mt-1 block">Catatan admin: <?php echo e($latestVerification->review_notes); ?></strong><?php endif; ?>
                Silakan kirim dokumen pengganti melalui formulir di bawah.
            </div>
        <?php endif; ?>
    </section>

    
    <?php if(in_array($identity->verification_status, ['unverified', 'revision', 'rejected'], true)): ?>
        <section class="sipora-card">
            <h2 class="text-xl font-bold">Ajukan Verifikasi Identitas</h2>
            <p class="mt-2 text-sm text-slate-600">Unggah salah satu dokumen identitas berikut. Informasi NIK / nomor dokumen dienkripsi dan tidak pernah ditampilkan secara publik.</p>

            <form method="post" action="<?php echo e(route('youth.identity-verification.store')); ?>" enctype="multipart/form-data" class="mt-6 space-y-5">
                <?php echo csrf_field(); ?>

                
                <div>
                    <p class="field-label">Jenis dokumen</p>
                    <div class="mt-2 grid gap-3 sm:grid-cols-3">
                        <?php $__currentLoopData = ['ktp' => ['label' => 'KTP', 'desc' => 'Kartu Tanda Penduduk (usia 17+)'], 'kia' => ['label' => 'KIA', 'desc' => 'Kartu Identitas Anak (di bawah 17 tahun)'], 'student_card' => ['label' => 'Kartu Pelajar', 'desc' => 'Alternatif sesuai kebijakan SIPORA']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="flex cursor-pointer flex-col rounded-xl border border-slate-200 p-4 has-checked:border-[#3346a8] has-checked:bg-[#eef0ff]">
                                <input class="sr-only" type="radio" name="document_type" value="<?php echo e($value); ?>" <?php if(old('document_type') === $value): echo 'checked'; endif; ?> required>
                                <span class="font-bold"><?php echo e($doc['label']); ?></span>
                                <span class="mt-1 text-xs text-slate-500"><?php echo e($doc['desc']); ?></span>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php $__errorArgs = ['document_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="field-error mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <label>
                    <span class="field-label">Nomor dokumen <span class="text-red-600">*</span></span>
                    <input class="field" type="text" name="document_number" value="<?php echo e(old('document_number')); ?>" maxlength="32" required placeholder="KTP/KIA harus 16 digit">
                    <p class="mt-1 text-xs text-slate-400">Nomor disimpan terenkripsi. Kartu pelajar adalah dokumen pendukung dan bukan identitas nasional.</p>
                    <?php $__errorArgs = ['document_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </label>

                
                <label>
                    <span class="field-label">Foto / scan dokumen <span class="text-red-600">*</span></span>
                    <input class="field file:mr-3 file:rounded-lg file:border-0 file:bg-[#eef0ff] file:px-3 file:py-2 file:font-semibold file:text-[#243378]"
                           type="file" name="document_file" accept=".jpg,.jpeg,.png,.pdf" required>
                    <p class="mt-1 text-xs text-slate-400">Format: JPG, PNG, atau PDF. Maks. 4 MB. Pastikan dokumen terbaca jelas.</p>
                    <?php $__errorArgs = ['document_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </label>

                
                <div class="rounded-xl border border-indigo-100 bg-indigo-50 p-4 text-sm text-indigo-800">
                    <strong>Privasi &amp; Keamanan:</strong>
                    <ul class="mt-2 space-y-1">
                        <li>· Berkas disimpan di penyimpanan privat dan tidak dapat diakses publik.</li>
                        <li>· Nomor dokumen dienkripsi; hash digunakan untuk deteksi duplikasi.</li>
                        <li>· Hanya admin SIPORA Dindikpora Pemalang yang berwenang dapat mengakses berkas.</li>
                        <li>· Kamu dapat mengajukan ulang jika diminta revisi atau pengajuan ditolak.</li>
                    </ul>
                </div>

                <label class="flex items-start gap-3 text-sm text-text-secondary"><input class="mt-1 size-4" type="checkbox" required><span>Saya sudah memeriksa jenis dokumen, nomor, dan file yang akan diajukan. Pengajuan ini akan ditinjau Admin.</span></label>

                <div class="flex justify-end">
                    <button class="btn" type="submit">Kirim pengajuan verifikasi</button>
                </div>
            </form>
        </section>
    <?php endif; ?>

    
    <?php if($identity->verifications && $identity->verifications->count() > 0): ?>
        <section class="sipora-card">
            <h2 class="text-xl font-bold">Riwayat Pengajuan</h2>
            <div class="mt-4 space-y-3">
                <?php $__currentLoopData = $identity->verifications->sortByDesc('submitted_at'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center justify-between rounded-xl border border-slate-200 p-4">
                        <div>
                            <p class="font-semibold"><?php echo e(strtoupper($ver->document_type)); ?></p>
                            <p class="text-xs text-slate-400">Diajukan <?php echo e($ver->submitted_at->diffForHumans()); ?></p>
                        </div>
                        <span class="rounded-full px-3 py-1 text-xs font-bold <?php echo e($ver->status === 'verified' ? 'bg-green-100 text-green-700' : ($ver->status === 'rejected' ? 'bg-red-100 text-red-700' : ($ver->status === 'revision' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700'))); ?>">
                            <?php echo e(ucfirst($ver->status)); ?>

                        </span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </section>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.youth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/youth/identity-verification.blade.php ENDPATH**/ ?>