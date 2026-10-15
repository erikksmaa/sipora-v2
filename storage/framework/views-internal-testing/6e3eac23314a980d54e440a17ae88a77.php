<?php ($feedback = session('error') ?? session('status')); ?>
<?php if(is_string($feedback)): ?>
    <p role="<?php echo e(session('error') ? 'alert' : 'status'); ?>" class="mb-5 rounded-lg border border-border bg-surface-soft p-4 text-sm leading-6 text-text-primary"><?php echo e($feedback); ?></p>
<?php endif; ?>
<?php if($errors->any()): ?>
    <div role="alert" class="mb-5 rounded-lg border border-danger/30 bg-surface-soft p-4 text-sm leading-6 text-danger"><p class="font-bold">Periksa kembali permintaan Anda.</p><ul class="mt-2 list-inside list-disc"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($message); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div>
<?php endif; ?>
<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/components/auth-flow-feedback.blade.php ENDPATH**/ ?>