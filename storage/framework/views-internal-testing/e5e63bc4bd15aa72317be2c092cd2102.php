<?php if(auth()->guard()->check()): ?> <?php if(auth()->user()->hasRole('youth')): ?>
<form method="post" action="<?php echo e(route('social.follow')); ?>"><?php echo csrf_field(); ?>
<input type="hidden" name="target_type" value="<?php echo e($targetType); ?>"><input type="hidden" name="target_id" value="<?php echo e($targetId); ?>"><input type="hidden" name="following" value="<?php echo e($followed ? 0 : 1); ?>">
<button class="btn-outline !min-h-10 !px-3 text-sm" type="submit"><?php echo e($followed ? 'Berhenti mengikuti' : 'Ikuti '.$targetType); ?></button></form>
<?php endif; ?> <?php endif; ?>
<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/social/follow.blade.php ENDPATH**/ ?>