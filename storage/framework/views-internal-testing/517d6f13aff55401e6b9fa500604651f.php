
<?php $__env->startSection('title', 'Daftar Akun · SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<div class="registration-experience">
    <aside class="registration-guide">
        <a class="inline-flex min-h-11 items-center gap-2 text-sm text-white" href="<?php echo e(route('home')); ?>"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'chevron-left','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-left','class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?> Kembali ke Beranda</a>
        <h1 class="mt-6 text-3xl font-extrabold">Mulai perjalananmu bersama SIPORA</h1>
        <p class="mt-4 leading-7 text-white/80">Buat akun, lengkapi persiapanmu, lalu temukan ruang bertumbuh di ekosistem pemuda Pemalang.</p>
        <ol class="registration-steps">
            <?php $__currentLoopData = [['Daftar Akun', 'Nama, email dan password'], ['Verifikasi Email', 'Konfirmasi akses email'], ['Profil & Identitas', 'Biodata, dokumen dan peninjauan'], ['Minat, Skill & Portfolio', 'Arah pengembangan dan bukti pengalaman'], ['Jelajahi Ekosistem', 'Community, Activity dan Opportunity']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => [$title, $copy]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li <?php if($index === 0): ?> aria-current="step" <?php endif; ?>><span><?php echo e($index + 1); ?></span><div><strong><?php echo e($title); ?></strong><p><?php echo e($copy); ?></p></div></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ol>
        <img class="registration-photo" src="<?php echo e(asset('media/demo/08A_SOCIAL_FEED/feed-002-workshop-recap.jpg')); ?>" alt="Foto demo pemuda berkolaborasi dalam workshop" width="1200" height="800" loading="lazy">
    </aside>
    <section class="registration-form-panel" aria-labelledby="registration-title">
        <p class="registration-progress">Langkah 1 dari 5 · Daftar Akun</p>
        <h2 id="registration-title" class="mt-4 text-3xl font-bold">Buat akun SIPORA</h2>
        <p class="mb-6 mt-3 text-sm leading-6 text-text-secondary">Cukup isi data akun terlebih dahulu. Biodata dan dokumen identitas dilengkapi setelah email terverifikasi.</p>
        <?php if(config('services.google.client_id') && config('services.google.client_secret')): ?>
            <a class="btn-outline mb-3 flex w-full justify-center" href="<?php echo e(route('google.redirect')); ?>">Daftar dengan Google</a>
            <p class="mb-6 text-xs leading-5 text-text-secondary">Google memudahkan akses akun; verifikasi identitas SIPORA tetap dilakukan melalui peninjauan dokumen.</p>
            <p class="mb-6 text-center text-sm text-text-muted">Atau gunakan email</p>
        <?php endif; ?>
        <?php if (isset($component)) { $__componentOriginalf356daf5bb8ad2b3823a397252417dee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf356daf5bb8ad2b3823a397252417dee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-form','data' => ['action' => route('register.store'),'recaptcha' => 'register']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('register.store')),'recaptcha' => 'register']); ?>
            <?php if (isset($component)) { $__componentOriginalc0e029268c67200fb6fd23d87d552d20 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc0e029268c67200fb6fd23d87d552d20 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-input','data' => ['name' => 'name','label' => 'Nama','autocomplete' => 'name']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'name','label' => 'Nama','autocomplete' => 'name']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc0e029268c67200fb6fd23d87d552d20)): ?>
<?php $attributes = $__attributesOriginalc0e029268c67200fb6fd23d87d552d20; ?>
<?php unset($__attributesOriginalc0e029268c67200fb6fd23d87d552d20); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc0e029268c67200fb6fd23d87d552d20)): ?>
<?php $component = $__componentOriginalc0e029268c67200fb6fd23d87d552d20; ?>
<?php unset($__componentOriginalc0e029268c67200fb6fd23d87d552d20); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginalc0e029268c67200fb6fd23d87d552d20 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc0e029268c67200fb6fd23d87d552d20 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-input','data' => ['name' => 'email','label' => 'Email','type' => 'email','autocomplete' => 'email']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'email','label' => 'Email','type' => 'email','autocomplete' => 'email']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc0e029268c67200fb6fd23d87d552d20)): ?>
<?php $attributes = $__attributesOriginalc0e029268c67200fb6fd23d87d552d20; ?>
<?php unset($__attributesOriginalc0e029268c67200fb6fd23d87d552d20); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc0e029268c67200fb6fd23d87d552d20)): ?>
<?php $component = $__componentOriginalc0e029268c67200fb6fd23d87d552d20; ?>
<?php unset($__componentOriginalc0e029268c67200fb6fd23d87d552d20); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginalc0e029268c67200fb6fd23d87d552d20 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc0e029268c67200fb6fd23d87d552d20 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-input','data' => ['name' => 'password','label' => 'Password (minimal 8 karakter)','type' => 'password','autocomplete' => 'new-password']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'password','label' => 'Password (minimal 8 karakter)','type' => 'password','autocomplete' => 'new-password']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc0e029268c67200fb6fd23d87d552d20)): ?>
<?php $attributes = $__attributesOriginalc0e029268c67200fb6fd23d87d552d20; ?>
<?php unset($__attributesOriginalc0e029268c67200fb6fd23d87d552d20); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc0e029268c67200fb6fd23d87d552d20)): ?>
<?php $component = $__componentOriginalc0e029268c67200fb6fd23d87d552d20; ?>
<?php unset($__componentOriginalc0e029268c67200fb6fd23d87d552d20); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginalc0e029268c67200fb6fd23d87d552d20 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc0e029268c67200fb6fd23d87d552d20 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-input','data' => ['name' => 'password_confirmation','label' => 'Konfirmasi password','type' => 'password','autocomplete' => 'new-password']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'password_confirmation','label' => 'Konfirmasi password','type' => 'password','autocomplete' => 'new-password']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc0e029268c67200fb6fd23d87d552d20)): ?>
<?php $attributes = $__attributesOriginalc0e029268c67200fb6fd23d87d552d20; ?>
<?php unset($__attributesOriginalc0e029268c67200fb6fd23d87d552d20); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc0e029268c67200fb6fd23d87d552d20)): ?>
<?php $component = $__componentOriginalc0e029268c67200fb6fd23d87d552d20; ?>
<?php unset($__componentOriginalc0e029268c67200fb6fd23d87d552d20); ?>
<?php endif; ?>
            <p class="text-xs leading-6 text-text-secondary">Setelah akun dibuat, Anda akan diarahkan untuk verifikasi email. Pendaftaran akun tidak otomatis memverifikasi identitas.</p>
             <?php $__env->slot('button', null, []); ?> Buat Akun & Lanjutkan <?php $__env->endSlot(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf356daf5bb8ad2b3823a397252417dee)): ?>
<?php $attributes = $__attributesOriginalf356daf5bb8ad2b3823a397252417dee; ?>
<?php unset($__attributesOriginalf356daf5bb8ad2b3823a397252417dee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf356daf5bb8ad2b3823a397252417dee)): ?>
<?php $component = $__componentOriginalf356daf5bb8ad2b3823a397252417dee; ?>
<?php unset($__componentOriginalf356daf5bb8ad2b3823a397252417dee); ?>
<?php endif; ?>
        <p class="mt-6 text-sm text-text-secondary">Sudah memiliki akun? <a class="font-bold text-interactive underline underline-offset-4" href="<?php echo e(route('login')); ?>">Masuk</a></p>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/auth/register.blade.php ENDPATH**/ ?>