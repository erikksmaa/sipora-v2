<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="description"
        content="<?php echo $__env->yieldContent('meta_description', 'SIPORA menghubungkan pemuda Pemalang dengan Activity, Community, Opportunity, Program, dan Youth Portfolio.'); ?>">
    <?php if (! empty(trim($__env->yieldContent('canonical')))): ?>
    <link rel="canonical" href="<?php echo $__env->yieldContent('canonical'); ?>"><?php endif; ?>
    <title><?php echo $__env->yieldContent('title', 'SIPORA — Ruang Tumbuh Pemuda Pemalang'); ?></title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('logo.png')); ?>">
    <?php if (isset($component)) { $__componentOriginalff3018920f514a2a3356c625e214876e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalff3018920f514a2a3356c625e214876e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.theme-init','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('theme-init'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalff3018920f514a2a3356c625e214876e)): ?>
<?php $attributes = $__attributesOriginalff3018920f514a2a3356c625e214876e; ?>
<?php unset($__attributesOriginalff3018920f514a2a3356c625e214876e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalff3018920f514a2a3356c625e214876e)): ?>
<?php $component = $__componentOriginalff3018920f514a2a3356c625e214876e; ?>
<?php unset($__componentOriginalff3018920f514a2a3356c625e214876e); ?>
<?php endif; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('head'); ?>
</head>

<body class="min-h-screen bg-bg text-text-primary antialiased">
    <a href="#main"
        class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded-lg focus:bg-white focus:p-3">Lewati
        ke konten</a>
    <?php if (isset($component)) { $__componentOriginala5f0778b7952fba1b2aaf8a771fcd23b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala5f0778b7952fba1b2aaf8a771fcd23b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.navbar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.navbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala5f0778b7952fba1b2aaf8a771fcd23b)): ?>
<?php $attributes = $__attributesOriginala5f0778b7952fba1b2aaf8a771fcd23b; ?>
<?php unset($__attributesOriginala5f0778b7952fba1b2aaf8a771fcd23b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala5f0778b7952fba1b2aaf8a771fcd23b)): ?>
<?php $component = $__componentOriginala5f0778b7952fba1b2aaf8a771fcd23b; ?>
<?php unset($__componentOriginala5f0778b7952fba1b2aaf8a771fcd23b); ?>
<?php endif; ?>
    <main id="main" tabindex="-1"><?php echo $__env->yieldContent('content'); ?></main>
    <?php if (! empty(trim($__env->yieldContent('footer')))): ?><?php echo $__env->yieldContent('footer'); ?><?php else: ?><?php if (isset($component)) { $__componentOriginalbb84be681bbe94cc31d6257779433433 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbb84be681bbe94cc31d6257779433433 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.footer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbb84be681bbe94cc31d6257779433433)): ?>
<?php $attributes = $__attributesOriginalbb84be681bbe94cc31d6257779433433; ?>
<?php unset($__attributesOriginalbb84be681bbe94cc31d6257779433433); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbb84be681bbe94cc31d6257779433433)): ?>
<?php $component = $__componentOriginalbb84be681bbe94cc31d6257779433433; ?>
<?php unset($__componentOriginalbb84be681bbe94cc31d6257779433433); ?>
<?php endif; ?><?php endif; ?>
    <?php if (isset($component)) { $__componentOriginalc5711d836f933e61eafca8928e9a27a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc5711d836f933e61eafca8928e9a27a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.alerts','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('alerts'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc5711d836f933e61eafca8928e9a27a5)): ?>
<?php $attributes = $__attributesOriginalc5711d836f933e61eafca8928e9a27a5; ?>
<?php unset($__attributesOriginalc5711d836f933e61eafca8928e9a27a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc5711d836f933e61eafca8928e9a27a5)): ?>
<?php $component = $__componentOriginalc5711d836f933e61eafca8928e9a27a5; ?>
<?php unset($__componentOriginalc5711d836f933e61eafca8928e9a27a5); ?>
<?php endif; ?>

    <!-- Accessibility Widget -->
    <script>
        window.ACCESSIBILITY_WIDGET_CONFIG = {
            lang: {
                accessibilityMenu: 'Menu Aksesibilitas',
                closeAccessibilityMenu: 'Tutup Menu Aksesibilitas',
                accessibilityTools: 'Alat Aksesibilitas',
                resetAllSettings: 'Atur Ulang Semua Pengaturan',
                screenReader: 'Pembaca Layar',
                textSpacing: 'Jarak Teks',
                pauseAnimations: 'Hentikan Animasi',
                hideImages: 'Sembunyikan Gambar',
                dyslexiaFriendly: 'Ramah Disleksia',
                biggerCursor: 'Perbesar Kursor',
                lineHeight: 'Tinggi Baris',
                textAlign: 'Perataan Teks',
                textSize: 'Ukuran Teks',
                highContrast: 'Kontras Tinggi',
                selectLanguage: 'Pilih Bahasa',
                reset: 'Atur Ulang',
                close: 'Tutup'
            }
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/accessibility-widgets@latest/widget.js"></script>
</body>

</html>
<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/layouts/public.blade.php ENDPATH**/ ?>