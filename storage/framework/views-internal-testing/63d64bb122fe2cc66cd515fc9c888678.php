<?php $__env->startSection('title', 'Biodata · SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<?php
    $initialStep = 0;
    foreach (['administrative_area_id' => 1, 'address_line' => 1, 'rt' => 1, 'rw' => 1, 'postal_code' => 1, 'phone' => 2, 'instagram' => 2, 'facebook' => 2, 'linkedin' => 2] as $field => $number) {
        if ($errors->has($field)) $initialStep = max($initialStep, $number);
    }
?>
<div class="mx-auto max-w-5xl space-y-5" x-data="biodataStepper(<?php echo e($initialStep); ?>)">
    <?php if (isset($component)) { $__componentOriginal8e0a96c106aaf9b655be7405167c4255 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8e0a96c106aaf9b655be7405167c4255 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.page-header','data' => ['eyebrow' => 'Persiapan Akun','title' => 'Lengkapi Biodata','description' => 'Data administratif ini privat dan dipakai untuk menyiapkan verifikasi identitas.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Persiapan Akun','title' => 'Lengkapi Biodata','description' => 'Data administratif ini privat dan dipakai untuk menyiapkan verifikasi identitas.']); ?>
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
        <ol class="biodata-steps" aria-label="Langkah pengisian Biodata">
            <template x-for="(label, index) in steps" :key="label">
                <li :class="step === index ? 'is-current' : (step > index ? 'is-done' : '')"><span class="biodata-step-number" x-text="step > index ? '✓' : index + 1"></span><span x-text="label"></span></li>
            </template>
        </ol>
        <form method="post" action="<?php echo e(route('youth.biodata.update')); ?>" class="mt-6" novalidate>
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div x-ref="panels">
                <div data-step="0" x-show="step === 0" x-cloak class="grid gap-5 sm:grid-cols-2">
                    <label><span class="field-label">Nama lengkap <span aria-hidden="true">*</span></span><input class="field" name="full_name" value="<?php echo e(old('full_name', $user->profile?->full_name ?? $user->name)); ?>" maxlength="160" required><?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
                    <label><span class="field-label">Tanggal lahir <span aria-hidden="true">*</span></span><input class="field" type="date" name="birth_date" value="<?php echo e(old('birth_date', $user->profile?->birth_date?->format('Y-m-d'))); ?>" max="<?php echo e(now()->format('Y-m-d')); ?>" required><?php $__errorArgs = ['birth_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
                    <label><span class="field-label">Tempat lahir</span><input class="field" name="birth_place" value="<?php echo e(old('birth_place', $user->profile?->birth_place)); ?>" maxlength="120"><?php $__errorArgs = ['birth_place'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
                    <label><span class="field-label">Gender</span><select class="field" name="gender"><option value="">Pilih</option><?php $__currentLoopData = ['male'=>'Laki-laki','female'=>'Perempuan','other'=>'Lainnya','prefer_not_to_say'=>'Tidak ingin menyebutkan']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($value); ?>" <?php if(old('gender', $user->profile?->gender)===$value): echo 'selected'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
                </div>
                <div data-step="1" x-show="step === 1" x-cloak class="grid gap-5 sm:grid-cols-2">
                    <label><span class="field-label">Kecamatan domisili <span aria-hidden="true">*</span></span><select class="field" name="administrative_area_id" required><option value="">Pilih kecamatan</option><?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($area->uuid()); ?>" <?php if(old('administrative_area_id', $user->primaryDomicile?->administrativeArea?->uuid()) === $area->uuid()): echo 'selected'; endif; ?>><?php echo e($area->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><?php $__errorArgs = ['administrative_area_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
                    <label class="sm:col-span-2"><span class="field-label">Alamat detail <span class="font-normal">(opsional, privat)</span></span><textarea class="field" name="address_line" maxlength="500"><?php echo e(old('address_line', $user->primaryDomicile?->address_line)); ?></textarea><?php $__errorArgs = ['address_line'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
                    <label><span class="field-label">RT</span><input class="field" name="rt" value="<?php echo e(old('rt', $user->primaryDomicile?->rt)); ?>" inputmode="numeric" maxlength="4"><?php $__errorArgs = ['rt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
                    <label><span class="field-label">RW</span><input class="field" name="rw" value="<?php echo e(old('rw', $user->primaryDomicile?->rw)); ?>" inputmode="numeric" maxlength="4"><?php $__errorArgs = ['rw'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
                    <label><span class="field-label">Kode pos</span><input class="field" name="postal_code" value="<?php echo e(old('postal_code', $user->primaryDomicile?->postal_code)); ?>" inputmode="numeric" maxlength="10"><?php $__errorArgs = ['postal_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
                </div>
                <div data-step="2" x-show="step === 2" x-cloak class="grid gap-5 sm:grid-cols-2">
                    <label><span class="field-label">WhatsApp / telepon <span aria-hidden="true">*</span></span><input class="field" type="tel" name="phone" value="<?php echo e(old('phone', $user->profile?->phone)); ?>" maxlength="32" autocomplete="tel" required><?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
                    <label><span class="field-label">Instagram (opsional)</span><input class="field" name="instagram" value="<?php echo e(old('instagram', $user->contactLinks?->instagram)); ?>" maxlength="30" placeholder="nama_pengguna"><?php $__errorArgs = ['instagram'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
                    <label><span class="field-label">Facebook (opsional)</span><input class="field" name="facebook" value="<?php echo e(old('facebook', $user->contactLinks?->facebook)); ?>" maxlength="255" placeholder="nama pengguna atau URL profil"><?php $__errorArgs = ['facebook'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
                    <label><span class="field-label">LinkedIn (opsional)</span><input class="field" name="linkedin" value="<?php echo e(old('linkedin', $user->contactLinks?->linkedin)); ?>" maxlength="255" placeholder="nama pengguna atau URL /in/"><?php $__errorArgs = ['linkedin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
                    <p class="text-sm text-text-secondary sm:col-span-2">Semua kontak ini privat. Email akun dikelola terpisah di Informasi Akun dan tidak tampil pada Portfolio publik.</p>
                </div>
                <div data-step="3" x-show="step === 3" x-cloak class="rounded-lg border border-border bg-surface-soft p-5">
                    <h3 class="font-extrabold">Verifikasi Identitas</h3><p class="mt-2 text-sm text-text-secondary">Setelah menyimpan Biodata, lanjutkan dengan mengirim KTP, KIA, atau Kartu Pelajar pada langkah verifikasi identitas. Dokumen disimpan privat dan ditinjau Admin Dindikpora.</p>
                </div>
                <div data-step="4" x-show="step === 4" x-cloak class="rounded-lg border border-border bg-surface-soft p-5">
                    <h3 class="font-extrabold">Konfirmasi Biodata</h3><p class="mt-2 text-sm text-text-secondary">Periksa kembali isian pada setiap langkah. Menyimpan Biodata akan membuka pengiriman dokumen identitas; belum berarti identitasmu terverifikasi.</p>
                </div>
            </div>
            <div class="mt-7 flex flex-wrap gap-3 border-t border-border pt-5">
                <button x-show="step > 0" type="button" class="btn-secondary" @click="previous">Sebelumnya</button>
                <button x-show="step < steps.length - 1" type="button" class="btn" @click="next">Selanjutnya</button>
                <button x-show="step === steps.length - 1" x-cloak type="submit" class="btn">Simpan &amp; lanjut ke Identitas</button>
            </div>
        </form>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.youth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/youth/biodata.blade.php ENDPATH**/ ?>