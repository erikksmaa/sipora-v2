<?php if(config('services.google.client_id') && config('services.google.client_secret')): ?>
    <a href="<?php echo e(route('google.redirect')); ?>" class="btn-secondary mt-5 flex justify-center">Continue with Google</a>
    <p class="mt-3 text-sm text-slate-600">Google sign-in verifies account access, not your SIPORA identity.</p>
<?php endif; ?>
<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/auth/google-link.blade.php ENDPATH**/ ?>