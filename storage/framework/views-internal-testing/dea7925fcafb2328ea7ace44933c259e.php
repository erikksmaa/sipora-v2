<?php $__env->startSection('title', 'Informasi Akun · SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<div class="mx-auto max-w-3xl space-y-5">
    <?php if (isset($component)) { $__componentOriginal8e0a96c106aaf9b655be7405167c4255 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8e0a96c106aaf9b655be7405167c4255 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.page-header','data' => ['eyebrow' => 'Akun','title' => 'Informasi Akun','description' => 'Kelola akses masuk dan periksa status akun. Email akun tidak muncul pada Portfolio publik.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Akun','title' => 'Informasi Akun','description' => 'Kelola akses masuk dan periksa status akun. Email akun tidak muncul pada Portfolio publik.']); ?>
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
        <h2 class="text-lg font-extrabold">Detail akun</h2>
        <dl class="mt-5 divide-y divide-border text-sm">
            <div class="flex flex-wrap justify-between gap-2 py-3"><dt class="font-semibold text-text-secondary">Email</dt><dd class="break-all font-bold"><?php echo e($user->email); ?></dd></div>
            <div class="flex justify-between gap-2 py-3"><dt class="font-semibold text-text-secondary">Status email</dt><dd class="font-bold"><?php echo e($user->hasVerifiedEmail() ? 'Terverifikasi' : 'Belum terverifikasi'); ?></dd></div>
            <div class="flex justify-between gap-2 py-3"><dt class="font-semibold text-text-secondary">Terdaftar sejak</dt><dd class="font-bold"><?php echo e($user->created_at?->translatedFormat('d M Y')); ?></dd></div>
            <div class="flex justify-between gap-2 py-3"><dt class="font-semibold text-text-secondary">Peran akun</dt><dd class="font-bold">Youth</dd></div>
        </dl>
    </section>
    <section class="sipora-card">
        <h2 class="text-lg font-extrabold">Metode masuk</h2>
        <div class="mt-4 grid gap-3 sm:grid-cols-2">
            <div class="rounded-xl border border-border bg-surface-soft p-4"><strong>Email / Password</strong><p class="mt-1 text-sm text-text-secondary"><?php echo e(filled($user->password) ? 'Tersedia' : 'Belum dikonfigurasi'); ?></p></div>
            <div class="rounded-xl border border-border bg-surface-soft p-4"><strong>Google</strong><p class="mt-1 text-sm text-text-secondary"><?php echo e($providers->contains('google') ? 'Terhubung' : 'Tidak terhubung'); ?></p></div>
        </div>
        <p class="mt-4 text-sm text-text-secondary">Masuk melalui Google tidak sama dengan verifikasi identitas SIPORA.</p>
        <a class="btn-secondary mt-5" href="<?php echo e(route('youth.account.security')); ?>">Kelola keamanan</a>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.youth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/youth/account/show.blade.php ENDPATH**/ ?>