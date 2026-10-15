<section class="public-panel mt-5" aria-labelledby="program-intake-title"><h2 id="program-intake-title" class="text-lg font-bold">Pendaftaran Program</h2>
    <dl class="mt-4 space-y-3 text-sm"><div><dt class="text-text-muted">Jenis</dt><dd><?php echo e($program->selection_mode==='review' ? 'Seleksi oleh pengelola' : 'Terbuka — langsung diterima'); ?></dd></div><div><dt class="text-text-muted">Periode pendaftaran</dt><dd><?php echo e($program->registration_open_at ? $program->registration_open_at->copy()->timezone(config('sipora.display_timezone'))->format('d M Y H:i').' WIB' : 'Tanpa batas awal'); ?> — <?php echo e($program->registration_close_at ? $program->registration_close_at->copy()->timezone(config('sipora.display_timezone'))->format('d M Y H:i').' WIB' : 'Tanpa batas akhir'); ?></dd></div><div><dt class="text-text-muted">Kuota peserta diterima</dt><dd><?php echo e($program->quota ?? 'Tanpa batas'); ?><?php if($program->quota !== null): ?> · Sisa <?php echo e($intakeSummary['remaining']); ?><?php endif; ?></dd></div></dl>
    <?php if($application): ?>
        <div class="mt-5"><p class="mb-2 text-sm font-bold">Status pendaftaran Anda</p><?php if (isset($component)) { $__componentOriginalada223afdb9ef419aff51c89a95f5cb7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalada223afdb9ef419aff51c89a95f5cb7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.program.application-status','data' => ['status' => $application->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('program.application-status'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($application->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalada223afdb9ef419aff51c89a95f5cb7)): ?>
<?php $attributes = $__attributesOriginalada223afdb9ef419aff51c89a95f5cb7; ?>
<?php unset($__attributesOriginalada223afdb9ef419aff51c89a95f5cb7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalada223afdb9ef419aff51c89a95f5cb7)): ?>
<?php $component = $__componentOriginalada223afdb9ef419aff51c89a95f5cb7; ?>
<?php unset($__componentOriginalada223afdb9ef419aff51c89a95f5cb7); ?>
<?php endif; ?><?php if($application->review_notes): ?><p class="mt-3 whitespace-pre-line text-sm text-text-secondary"><?php echo e($application->review_notes); ?></p><?php endif; ?></div>
        <?php if($program->execution_status==='running' && in_array($application->status,['submitted','under_review','waitlisted','accepted'],true)): ?><form class="mt-4" method="POST" action="<?php echo e(route('youth.programs.withdraw',$program)); ?>" data-confirm-title="Tarik pendaftaran Program?" data-confirm="Status menjadi Mengundurkan Diri. Riwayat pendaftaran tetap disimpan dan tidak dapat diajukan ulang."><?php echo csrf_field(); ?><button class="btn-outline w-full">Tarik Pendaftaran</button></form><?php endif; ?>
    <?php elseif(!$registrationReason): ?>
        <p class="mt-5 text-sm font-bold text-success">Pendaftaran Dibuka</p><form class="mt-4" method="POST" action="<?php echo e(route('youth.programs.apply',$program)); ?>"><?php echo csrf_field(); ?><button class="btn-accent w-full">Daftar Program</button></form>
    <?php else: ?><p class="mt-5 text-sm leading-6 text-text-secondary"><?php echo e($registrationReason); ?></p><?php if(auth()->guard()->guest()): ?><a class="btn-outline mt-4 w-full" href="<?php echo e(route('login')); ?>">Masuk</a><?php endif; ?> <?php if(auth()->guard()->check()): ?> <?php if(auth()->user()->hasRole('youth') && !$application): ?><a class="btn-outline mt-4 w-full" href="<?php echo e(route('youth.onboarding')); ?>">Periksa Kesiapan Akun</a><?php endif; ?> <?php endif; ?> <?php endif; ?>
    <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('registration')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('registration'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?><p class="mt-4 text-xs leading-6 text-text-muted">Diterima pada Program tidak otomatis mendaftarkan Anda ke Activity. Pendaftaran Activity tetap mengikuti aturan masing-masing kegiatan.</p>
</section>
<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/public/programs/_intake.blade.php ENDPATH**/ ?>