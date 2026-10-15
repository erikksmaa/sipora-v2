<?php $__env->startSection('title', 'Keahlian Saya · SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<div class="mx-auto max-w-3xl">
    <a class="text-sm font-bold text-[#243378]" href="<?php echo e(route('youth.profile.show')); ?>">← Kembali ke profil</a>
    <section class="sipora-card mt-4">
        <p class="text-xs font-bold uppercase tracking-wider text-orange-600">Pengembangan Diri</p>
        <h1 class="mt-2 text-3xl font-extrabold">Keahlian yang kamu miliki</h1>
        <p class="mt-2 text-slate-600">Pilih satu atau lebih keahlian. Kamu bisa menambahkan keahlian baru nanti.</p>
        <form class="mt-6" method="post" action="<?php echo e(route('youth.skills.update')); ?>" x-data="{ other: <?php echo e(old('custom_skill') ? 'true' : 'false'); ?> }">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <?php if($skills->isEmpty()): ?>
                <p class="rounded-xl bg-slate-50 p-6 text-center text-slate-500">Daftar keahlian belum tersedia. Administrator akan segera mengisi master data.</p>
            <?php else: ?>
                <div class="grid gap-3 sm:grid-cols-2">
                    <?php $__currentLoopData = $skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 p-4 has-checked:border-[#3346a8] has-checked:bg-[#eef0ff]">
                            <input class="size-5 accent-[#243378]" type="checkbox" name="skills[]" value="<?php echo e($skill->uuid()); ?>" <?php if(in_array($skill->uuid(), old('skills', $selectedSkills))): echo 'checked'; endif; ?>>
                            <span class="font-semibold"><?php echo e($skill->name); ?></span>
                        </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php $__errorArgs = ['skills'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="field-error mt-3"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <?php endif; ?>
            <label class="mt-5 flex items-center gap-3"><input type="checkbox" class="size-5" x-model="other" name="other_selected" value="1"><span class="font-semibold">Lainnya — keahlian belum ada di daftar</span></label>
            <label x-show="other" x-cloak class="mt-3 block"><span class="field-label">Masukkan keahlian lainnya</span><input class="field" name="custom_skill" value="<?php echo e(old('custom_skill')); ?>" maxlength="120" :required="other" :disabled="!other"><?php $__errorArgs = ['custom_skill'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
            <div class="mt-6 flex justify-end">
                <button class="btn" type="submit">Simpan keahlian</button>
            </div>
        </form>
        <?php if($customSkills->isNotEmpty()): ?><div class="mt-6 border-t border-border pt-5"><h2 class="font-bold">Keahlian lainnya milikmu</h2><div class="mt-3 flex flex-wrap gap-2"><?php $__currentLoopData = $customSkills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><form method="post" action="<?php echo e(route('youth.custom-tags.destroy', $tag)); ?>" data-confirm="Hapus keahlian <?php echo e($tag->name); ?>?"><span class="interest-chip"><?php echo e($tag->name); ?> <button class="ml-1" type="submit" aria-label="Hapus keahlian <?php echo e($tag->name); ?>">×</button></span><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?></form><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div></div><?php endif; ?>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.youth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/youth/skills.blade.php ENDPATH**/ ?>