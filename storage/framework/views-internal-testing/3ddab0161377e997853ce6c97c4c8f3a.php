<?php ($editing = $program->exists); ?>
<form method="POST" action="<?php echo e($editing ? route('manager.programs.update', [$organization, $program]) : route('manager.programs.store', $organization)); ?>" class="mt-6 space-y-6 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
    <?php echo csrf_field(); ?>
    <?php if($editing): ?> <?php echo method_field('PATCH'); ?> <?php endif; ?>
    <?php if($categories->isEmpty()): ?>
        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">Master kategori Program belum tersedia. Data kategori pengembangan perlu disiapkan terlebih dahulu.</div>
    <?php endif; ?>
    <div class="grid gap-5 md:grid-cols-2">
        <h2 class="border-b border-slate-200 pb-2 text-lg font-bold text-primary-dark md:col-span-2">Identitas Program</h2>
        <label class="md:col-span-2"><span class="label">Nama Program</span><input class="field" name="title" value="<?php echo e(old('title', $program->title)); ?>" required maxlength="220"></label>
        <label><span class="label">Kategori</span><select class="field" name="category_id" required><option value="">Pilih kategori</option><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($category->uuid()); ?>" <?php if(old('category_id', $program->category?->uuid()) === $category->uuid()): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label>
        <div><span class="label">Status</span><div class="field flex items-center"><?php if (isset($component)) { $__componentOriginal08ab5d8782f78718f24293961a718c3b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal08ab5d8782f78718f24293961a718c3b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.program.status-badge','data' => ['status' => $program->execution_status ?: 'planned']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('program.status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($program->execution_status ?: 'planned')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal08ab5d8782f78718f24293961a718c3b)): ?>
<?php $attributes = $__attributesOriginal08ab5d8782f78718f24293961a718c3b; ?>
<?php unset($__attributesOriginal08ab5d8782f78718f24293961a718c3b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal08ab5d8782f78718f24293961a718c3b)): ?>
<?php $component = $__componentOriginal08ab5d8782f78718f24293961a718c3b; ?>
<?php unset($__componentOriginal08ab5d8782f78718f24293961a718c3b); ?>
<?php endif; ?></div><p class="mt-1 text-xs text-slate-500">Status Program mengikuti alur Proposal pada fase berikutnya.</p></div>
        <label><span class="label">Tanggal mulai</span><input class="field" type="date" name="start_date" value="<?php echo e(old('start_date', $program->start_date?->format('Y-m-d'))); ?>"></label>
        <h2 class="border-b border-slate-200 pb-2 pt-3 text-lg font-bold text-primary-dark md:col-span-2">Periode dan tujuan</h2>
        <label><span class="label">Tanggal selesai</span><input class="field" type="date" name="end_date" value="<?php echo e(old('end_date', $program->end_date?->format('Y-m-d'))); ?>"></label>
        <label class="md:col-span-2"><span class="label">Deskripsi</span><textarea class="field min-h-32" name="description" maxlength="10000"><?php echo e(old('description', $program->description)); ?></textarea></label>
        <label class="md:col-span-2"><span class="label">Maksud dan tujuan</span><textarea class="field min-h-36" name="objectives" maxlength="10000"><?php echo e(old('objectives', $program->objectives)); ?></textarea></label>
    </div>
    <?php echo $__env->make('manager.programs._intake-fields', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php if($errors->any()): ?><div class="rounded-xl bg-red-50 p-4 text-sm text-red-800"><ul class="list-disc pl-5"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div><?php endif; ?>
    <div class="flex flex-wrap justify-end gap-3"><a class="btn-secondary" href="<?php echo e(route('manager.programs.index', $organization)); ?>">Batal</a><button class="btn-primary" <?php if($categories->isEmpty()): echo 'disabled'; endif; ?>>Simpan Rencana</button></div>
</form>
<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/manager/programs/_form.blade.php ENDPATH**/ ?>