
<?php $__env->startSection('title', 'SIPORA — Perjalanan Pemuda Pemalang'); ?>
<?php $__env->startPush('head'); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/ecosystem-map.css', 'resources/js/ecosystem-map.js']); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<?php
    $primaryCta = auth()->check() ? route(\App\Support\Auth\HomeRoute::for(auth()->user())) : route('register');
    $displayCount = fn ($count) => $count === null ? '<5' : number_format($count, 0, ',', '.');
?>
<div class="public-home">
<section id="beranda" class="home-hero">
    <div class="home-container grid items-center gap-10 lg:grid-cols-2">
        <div>
            <p class="home-eyebrow">Ekosistem pemuda Kabupaten Pemalang</p>
            <h1>Satu Platform untuk Perjalanan <span>Pemuda Pemalang</span></h1>
            <p class="mt-6 max-w-xl text-lg leading-8 text-white/85">Temukan ruang untuk berkembang. SIPORA menghubungkan pemuda, Community, Activity, Opportunity, dan pemerintah dalam satu ekosistem.</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a class="landing-btn-primary" href="<?php echo e($primaryCta); ?>"><?php echo e(auth()->check() ? 'Ruang Saya' : 'Daftar Sekarang'); ?> <?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'chevron-right','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-right','class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></a>
                <a class="landing-btn-secondary !border-white/40 !bg-white/10 !text-white" href="<?php echo e(route('activities.index')); ?>">Jelajahi Activity</a>
            </div>
            <p class="mt-6 text-sm text-white/75">Belajar, berkarya, dan berkontribusi bersama pemuda Pemalang.</p>
        </div>
        <figure class="home-hero-photo">
            <img src="<?php echo e(asset('media/demo/08A_SOCIAL_FEED/feed-002-workshop-recap.jpg')); ?>" width="1200" height="800" alt="Foto demo pemuda berkolaborasi dalam workshop pengembangan web" fetchpriority="high">
            <figcaption><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'users','class' => 'size-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'users','class' => 'size-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?><div><strong>Perjalanan dimulai bersama</strong><span>Temukan komunitas dan pengalaman baru · Foto demo SIPORA</span></div></figcaption>
        </figure>
    </div>
</section>
<section id="tentang" class="home-section">
    <div class="home-container grid items-center gap-10 lg:grid-cols-2">
        <div class="home-app-preview" aria-label="Preview struktur fitur SIPORA, bukan profil pengguna">
            <div class="flex items-center justify-between border-b border-border pb-4"><strong>SIPORA <span class="text-text-muted">/ Ruang Pemuda</span></strong><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'shield-check','class' => 'size-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'shield-check','class' => 'size-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></div>
            <h3 class="mt-6 text-2xl font-bold">Satu tempat untuk perjalananmu</h3>
            <p class="mt-2 text-sm text-text-secondary">Preview fitur yang tersedia di SIPORA</p>
            <div class="mt-6 grid grid-cols-2 gap-3">
                <?php $__currentLoopData = [['user', 'Portfolio', 'Pengalaman & bukti karya'], ['activity', 'Activity', 'Partisipasi & kehadiran'], ['notebook', 'Passport', 'Riwayat perjalanan nyata'], ['badge-check', 'Certificate', 'Capaian dari Activity']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$icon, $title, $copy]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="rounded-xl bg-surface-soft p-4"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => $icon,'class' => 'size-6 text-interactive']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($icon),'class' => 'size-6 text-interactive']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?><strong class="mt-3 block"><?php echo e($title); ?></strong><p class="mt-1 text-xs leading-5 text-text-secondary"><?php echo e($copy); ?></p></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <p class="mt-5 text-xs text-text-muted">Ilustrasi antarmuka; bukan data atau testimoni pengguna.</p>
        </div>
        <div><?php if (isset($component)) { $__componentOriginalf78519ad210db5d56149fa0f4b185795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf78519ad210db5d56149fa0f4b185795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-heading','data' => ['eyebrow' => 'Kenali platform','title' => 'Apa itu SIPORA?']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Kenali platform','title' => 'Apa itu SIPORA?']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf78519ad210db5d56149fa0f4b185795)): ?>
<?php $attributes = $__attributesOriginalf78519ad210db5d56149fa0f4b185795; ?>
<?php unset($__attributesOriginalf78519ad210db5d56149fa0f4b185795); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf78519ad210db5d56149fa0f4b185795)): ?>
<?php $component = $__componentOriginalf78519ad210db5d56149fa0f4b185795; ?>
<?php unset($__componentOriginalf78519ad210db5d56149fa0f4b185795); ?>
<?php endif; ?>
            <p class="mt-5 leading-7 text-text-secondary">SIPORA adalah platform ekosistem kepemudaan Kabupaten Pemalang yang menghubungkan pemuda, komunitas, aktivitas, peluang pengembangan diri, dan pemerintah dalam satu sistem digital terintegrasi.</p>
            <p class="mt-4 leading-7 text-text-secondary">Melalui SIPORA, pemuda dapat menemukan kegiatan, membangun portofolio, bergabung dengan komunitas, mengikuti peluang pengembangan diri, serta membangun rekam perjalanan melalui Passport dan Certificate.</p>
            <p class="mt-4 text-sm leading-7 text-text-secondary">Komunitas dan pemerintah dapat mengelola aktivitas, program dan partisipasi, serta memperoleh insight melalui Youth Intelligence dan Ecosystem Maps. Status publikasi dan kendali privasi tetap berlaku.</p>
            <div class="mt-6 flex flex-wrap gap-3"><a class="btn-accent" href="<?php echo e($primaryCta); ?>"><?php echo e(auth()->check() ? 'Ruang Saya' : 'Daftar Sekarang'); ?></a><a class="btn-outline" href="<?php echo e(route('search.index')); ?>">Jelajahi SIPORA</a></div>
        </div>
    </div>
</section>
<section id="angka" class="home-section !pt-0">
    <div class="home-container"><h2 class="text-2xl font-bold">Ekosistem SIPORA dalam Angka</h2><p class="mt-2 text-sm text-text-secondary">Berdasarkan pemuda yang terdaftar dan record publik di SIPORA; bukan seluruh populasi pemuda Kabupaten Pemalang.</p>
        <div class="mt-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
            <?php if (isset($component)) { $__componentOriginal7b56645fd65e0290724a89216e5d8eee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7b56645fd65e0290724a89216e5d8eee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.stat-card','data' => ['icon' => 'users','value' => $displayCount($youthStatistics['registered']),'label' => 'Pemuda Terdaftar','tone' => 'blue']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'users','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($displayCount($youthStatistics['registered'])),'label' => 'Pemuda Terdaftar','tone' => 'blue']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7b56645fd65e0290724a89216e5d8eee)): ?>
<?php $attributes = $__attributesOriginal7b56645fd65e0290724a89216e5d8eee; ?>
<?php unset($__attributesOriginal7b56645fd65e0290724a89216e5d8eee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7b56645fd65e0290724a89216e5d8eee)): ?>
<?php $component = $__componentOriginal7b56645fd65e0290724a89216e5d8eee; ?>
<?php unset($__componentOriginal7b56645fd65e0290724a89216e5d8eee); ?>
<?php endif; ?>
            <?php $__currentLoopData = array_slice($ecosystemStatistics, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $metric): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if (isset($component)) { $__componentOriginal7b56645fd65e0290724a89216e5d8eee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7b56645fd65e0290724a89216e5d8eee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.stat-card','data' => ['icon' => ['building', 'activity', 'bookmark'][$index],'value' => $displayCount($metric['value'] > 0 && $metric['value'] < 5 ? null : $metric['value']),'label' => $metric['label'],'tone' => 'blue']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['building', 'activity', 'bookmark'][$index]),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($displayCount($metric['value'] > 0 && $metric['value'] < 5 ? null : $metric['value'])),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($metric['label']),'tone' => 'blue']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7b56645fd65e0290724a89216e5d8eee)): ?>
<?php $attributes = $__attributesOriginal7b56645fd65e0290724a89216e5d8eee; ?>
<?php unset($__attributesOriginal7b56645fd65e0290724a89216e5d8eee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7b56645fd65e0290724a89216e5d8eee)): ?>
<?php $component = $__componentOriginal7b56645fd65e0290724a89216e5d8eee; ?>
<?php unset($__componentOriginal7b56645fd65e0290724a89216e5d8eee); ?>
<?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<section id="peta" class="home-section !pt-0">
    <div class="home-container"><div class="flex flex-wrap items-end justify-between gap-4"><?php if (isset($component)) { $__componentOriginalf78519ad210db5d56149fa0f4b185795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf78519ad210db5d56149fa0f4b185795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-heading','data' => ['eyebrow' => 'Cakupan kecamatan','title' => 'Peta Ekosistem Pemuda','description' => 'Jelajahi sebaran Community, Activity, dan Program dari data kecamatan yang tercatat.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Cakupan kecamatan','title' => 'Peta Ekosistem Pemuda','description' => 'Jelajahi sebaran Community, Activity, dan Program dari data kecamatan yang tercatat.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf78519ad210db5d56149fa0f4b185795)): ?>
<?php $attributes = $__attributesOriginalf78519ad210db5d56149fa0f4b185795; ?>
<?php unset($__attributesOriginalf78519ad210db5d56149fa0f4b185795); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf78519ad210db5d56149fa0f4b185795)): ?>
<?php $component = $__componentOriginalf78519ad210db5d56149fa0f4b185795; ?>
<?php unset($__componentOriginalf78519ad210db5d56149fa0f4b185795); ?>
<?php endif; ?><a class="btn-outline" href="<?php echo e(route('ecosystem-map.index')); ?>">Lihat Peta Ekosistem</a></div>
        <div class="eco-map-page home-map-preview mt-6"><?php if (isset($component)) { $__componentOriginalad298a9b750220090305d3cfe1fc5d9a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad298a9b750220090305d3cfe1fc5d9a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.ecosystem-map-panel','data' => ['coverage' => $coverage]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.ecosystem-map-panel'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['coverage' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($coverage)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalad298a9b750220090305d3cfe1fc5d9a)): ?>
<?php $attributes = $__attributesOriginalad298a9b750220090305d3cfe1fc5d9a; ?>
<?php unset($__attributesOriginalad298a9b750220090305d3cfe1fc5d9a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalad298a9b750220090305d3cfe1fc5d9a)): ?>
<?php $component = $__componentOriginalad298a9b750220090305d3cfe1fc5d9a; ?>
<?php unset($__componentOriginalad298a9b750220090305d3cfe1fc5d9a); ?>
<?php endif; ?></div>
        <p class="mt-3 text-xs leading-6 text-text-secondary">Data agregat tanpa lokasi individu. Jumlah 1–4 ditampilkan sebagai &lt;5. Batas kecamatan bersumber dari data BIG edisi 2022.</p>
    </div>
</section>
<section id="alur" class="home-section bg-surface-soft">
    <div class="home-container"><?php if (isset($component)) { $__componentOriginalf78519ad210db5d56149fa0f4b185795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf78519ad210db5d56149fa0f4b185795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-heading','data' => ['eyebrow' => 'Mulai perjalananmu','title' => 'Alur Pendaftaran SIPORA','description' => 'Lima langkah untuk menemukan ruang belajar, berkarya, dan berkontribusi.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Mulai perjalananmu','title' => 'Alur Pendaftaran SIPORA','description' => 'Lima langkah untuk menemukan ruang belajar, berkarya, dan berkontribusi.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf78519ad210db5d56149fa0f4b185795)): ?>
<?php $attributes = $__attributesOriginalf78519ad210db5d56149fa0f4b185795; ?>
<?php unset($__attributesOriginalf78519ad210db5d56149fa0f4b185795); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf78519ad210db5d56149fa0f4b185795)): ?>
<?php $component = $__componentOriginalf78519ad210db5d56149fa0f4b185795; ?>
<?php unset($__componentOriginalf78519ad210db5d56149fa0f4b185795); ?>
<?php endif; ?>
        <ol class="home-steps mt-8">
            <?php $__currentLoopData = [['user', 'Daftar Akun', 'Buat akun SIPORA.'], ['mail', 'Verifikasi Email', 'Konfirmasi email untuk mengaktifkan akun.'], ['shield-check', 'Lengkapi Profil', 'Isi data dasar, minat dan skill. Ajukan verifikasi identitas sesuai tahapan akun.'], ['notebook', 'Bangun Portofolio', 'Tambahkan pengalaman, prestasi dan evidence jika ada.'], ['search', 'Jelajahi Ekosistem', 'Temukan Activity, Community dan Opportunity.']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => [$icon, $title, $copy]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><span class="home-step-number"><?php echo e($index + 1); ?></span><div><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => $icon,'class' => 'mb-3 size-5 text-interactive']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($icon),'class' => 'mb-3 size-5 text-interactive']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?><h3 class="font-bold"><?php echo e($title); ?></h3><p class="mt-2 text-sm leading-6 text-text-secondary"><?php echo e($copy); ?></p></div></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ol>
        <p class="mt-6 text-xs leading-6 text-text-secondary">Pendaftaran akun dan verifikasi identitas adalah proses berbeda. Kelengkapan profil dan persetujuan verifikasi mengikuti aturan akses SIPORA; pengalaman dan prestasi tambahan bersifat opsional.</p>
    </div>
</section>
<section class="home-section !pb-0"><div class="home-container"><?php if (isset($component)) { $__componentOriginalf78519ad210db5d56149fa0f4b185795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf78519ad210db5d56149fa0f4b185795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-heading','data' => ['eyebrow' => 'Temukan kesempatan','title' => 'Jelajahi Ekosistem SIPORA']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Temukan kesempatan','title' => 'Jelajahi Ekosistem SIPORA']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf78519ad210db5d56149fa0f4b185795)): ?>
<?php $attributes = $__attributesOriginalf78519ad210db5d56149fa0f4b185795; ?>
<?php unset($__attributesOriginalf78519ad210db5d56149fa0f4b185795); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf78519ad210db5d56149fa0f4b185795)): ?>
<?php $component = $__componentOriginalf78519ad210db5d56149fa0f4b185795; ?>
<?php unset($__componentOriginalf78519ad210db5d56149fa0f4b185795); ?>
<?php endif; ?>
    <form method="GET" action="<?php echo e(route('search.index')); ?>" class="mt-6 flex flex-col gap-3 sm:flex-row"><label class="flex-1"><span class="sr-only">Cari Activity, Community, Opportunity atau Program</span><input class="field w-full" type="search" name="q" maxlength="120" placeholder="Cari Activity, Community, Opportunity atau Program..."></label><button class="btn-accent" type="submit">Cari Sekarang</button></form>
    <div class="mt-4 flex flex-wrap gap-2"><?php $__currentLoopData = $interests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $interest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><a class="status-badge" href="<?php echo e(route('search.index', ['q' => $interest['name']])); ?>"><?php echo e($interest['name']); ?></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div>
</div></section>
<section id="kegiatan" class="landing-section-scene px-section-white px-zig-right px-5 py-10">
    <div class="mx-auto max-w-7xl">
        <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-end"><?php if (isset($component)) { $__componentOriginalf78519ad210db5d56149fa0f4b185795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf78519ad210db5d56149fa0f4b185795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-heading','data' => ['eyebrow' => 'Terjadwal Berikutnya','title' => 'Kegiatan yang Bisa Kamu Ikuti','description' => 'Activity publik diurutkan berdasarkan jadwal terdekat.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Terjadwal Berikutnya','title' => 'Kegiatan yang Bisa Kamu Ikuti','description' => 'Activity publik diurutkan berdasarkan jadwal terdekat.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf78519ad210db5d56149fa0f4b185795)): ?>
<?php $attributes = $__attributesOriginalf78519ad210db5d56149fa0f4b185795; ?>
<?php unset($__attributesOriginalf78519ad210db5d56149fa0f4b185795); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf78519ad210db5d56149fa0f4b185795)): ?>
<?php $component = $__componentOriginalf78519ad210db5d56149fa0f4b185795; ?>
<?php unset($__componentOriginalf78519ad210db5d56149fa0f4b185795); ?>
<?php endif; ?><a
                href="<?php echo e(route('activities.index')); ?>" class="btn-outline">Lihat semua Activity <?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'chevron-right','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-right','class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></a></div>
        <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3"><?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if (isset($component)) { $__componentOriginal446408be3aed06541d4520d25c2b2ddb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal446408be3aed06541d4520d25c2b2ddb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.discovery.activity-card','data' => ['activity' => $activity]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('discovery.activity-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['activity' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activity)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal446408be3aed06541d4520d25c2b2ddb)): ?>
<?php $attributes = $__attributesOriginal446408be3aed06541d4520d25c2b2ddb; ?>
<?php unset($__attributesOriginal446408be3aed06541d4520d25c2b2ddb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal446408be3aed06541d4520d25c2b2ddb)): ?>
<?php $component = $__componentOriginal446408be3aed06541d4520d25c2b2ddb; ?>
<?php unset($__componentOriginal446408be3aed06541d4520d25c2b2ddb); ?>
<?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p
                    class="col-span-full rounded-2xl border-2 border-dashed border-border p-10 text-center text-text-secondary">
                Belum ada Activity publik yang akan datang.</p><?php endif; ?></div>
    </div>
</section>
<section id="komunitas" class="landing-section-scene px-section-paper px-zig-left px-5 py-10">
    <div class="mx-auto max-w-7xl"><?php if (isset($component)) { $__componentOriginalf78519ad210db5d56149fa0f4b185795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf78519ad210db5d56149fa0f4b185795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-heading','data' => ['eyebrow' => 'Tumbuh Bersama','title' => 'Temukan Komunitas','description' => 'Terhubung dengan orang-orang yang berbagi minat dan tujuan yang sama.','centered' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Tumbuh Bersama','title' => 'Temukan Komunitas','description' => 'Terhubung dengan orang-orang yang berbagi minat dan tujuan yang sama.','centered' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf78519ad210db5d56149fa0f4b185795)): ?>
<?php $attributes = $__attributesOriginalf78519ad210db5d56149fa0f4b185795; ?>
<?php unset($__attributesOriginalf78519ad210db5d56149fa0f4b185795); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf78519ad210db5d56149fa0f4b185795)): ?>
<?php $component = $__componentOriginalf78519ad210db5d56149fa0f4b185795; ?>
<?php unset($__componentOriginalf78519ad210db5d56149fa0f4b185795); ?>
<?php endif; ?>
        <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3"><?php $__empty_1 = true; $__currentLoopData = $communities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $community): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if (isset($component)) { $__componentOriginale1f00f9f06c1955a4a1e19b72a6c138b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale1f00f9f06c1955a4a1e19b72a6c138b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.discovery.community-card','data' => ['community' => $community]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('discovery.community-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['community' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($community)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale1f00f9f06c1955a4a1e19b72a6c138b)): ?>
<?php $attributes = $__attributesOriginale1f00f9f06c1955a4a1e19b72a6c138b; ?>
<?php unset($__attributesOriginale1f00f9f06c1955a4a1e19b72a6c138b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale1f00f9f06c1955a4a1e19b72a6c138b)): ?>
<?php $component = $__componentOriginale1f00f9f06c1955a4a1e19b72a6c138b; ?>
<?php unset($__componentOriginale1f00f9f06c1955a4a1e19b72a6c138b); ?>
<?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p
                    class="col-span-full rounded-2xl border-2 border-dashed border-border p-10 text-center text-text-secondary">
                Belum ada Community aktif yang dapat ditampilkan.</p><?php endif; ?></div>
        <div class="mt-8 text-center"><a href="<?php echo e(route('communities.index')); ?>" class="btn-outline">Lihat semua
                Community <?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'chevron-right','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-right','class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></a></div>
    </div>
</section>
<section id="peluang" class="landing-section-scene px-section-white px-zig-right px-5 py-10">
    <div class="mx-auto max-w-7xl">
        <div class="flex flex-col justify-between gap-6 md:flex-row md:items-end"><?php if (isset($component)) { $__componentOriginalf78519ad210db5d56149fa0f4b185795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf78519ad210db5d56149fa0f4b185795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-heading','data' => ['eyebrow' => 'Buka Jalanmu','title' => 'Opportunity untuk Pemuda','description' => 'Akses beasiswa, kerelawanan, magang, dan program pengembangan yang dikurasi Admin SIPORA.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Buka Jalanmu','title' => 'Opportunity untuk Pemuda','description' => 'Akses beasiswa, kerelawanan, magang, dan program pengembangan yang dikurasi Admin SIPORA.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf78519ad210db5d56149fa0f4b185795)): ?>
<?php $attributes = $__attributesOriginalf78519ad210db5d56149fa0f4b185795; ?>
<?php unset($__attributesOriginalf78519ad210db5d56149fa0f4b185795); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf78519ad210db5d56149fa0f4b185795)): ?>
<?php $component = $__componentOriginalf78519ad210db5d56149fa0f4b185795; ?>
<?php unset($__componentOriginalf78519ad210db5d56149fa0f4b185795); ?>
<?php endif; ?><a
                class="btn-outline" href="<?php echo e(route('opportunities.index')); ?>">Lihat semua Opportunity <?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'chevron-right','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-right','class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></a></div>
        <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            <?php $__empty_1 = true; $__currentLoopData = $opportunities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opportunity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if (isset($component)) { $__componentOriginal31ba4aa0af51a52f1a7404d47e1cf707 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal31ba4aa0af51a52f1a7404d47e1cf707 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.discovery.opportunity-card','data' => ['opportunity' => $opportunity]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('discovery.opportunity-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['opportunity' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($opportunity)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal31ba4aa0af51a52f1a7404d47e1cf707)): ?>
<?php $attributes = $__attributesOriginal31ba4aa0af51a52f1a7404d47e1cf707; ?>
<?php unset($__attributesOriginal31ba4aa0af51a52f1a7404d47e1cf707); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal31ba4aa0af51a52f1a7404d47e1cf707)): ?>
<?php $component = $__componentOriginal31ba4aa0af51a52f1a7404d47e1cf707; ?>
<?php unset($__componentOriginal31ba4aa0af51a52f1a7404d47e1cf707); ?>
<?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div
                    class="col-span-full rounded-2xl border-2 border-dashed border-border p-10 text-center text-sm text-text-secondary">
            Belum ada Opportunity yang dipublikasikan.</div><?php endif; ?></div>
    </div>
</section>
<section id="program" class="landing-section-scene px-section-paper px-zig-left px-5 py-10">
    <div class="mx-auto max-w-7xl"><?php if (isset($component)) { $__componentOriginalf78519ad210db5d56149fa0f4b185795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf78519ad210db5d56149fa0f4b185795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-heading','data' => ['eyebrow' => 'Program Pemuda','title' => 'Program yang Sedang Berjalan dan Selesai','description' => 'Program publik berasal dari Community aktif dan memiliki Proposal yang telah disetujui.','centered' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Program Pemuda','title' => 'Program yang Sedang Berjalan dan Selesai','description' => 'Program publik berasal dari Community aktif dan memiliki Proposal yang telah disetujui.','centered' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf78519ad210db5d56149fa0f4b185795)): ?>
<?php $attributes = $__attributesOriginalf78519ad210db5d56149fa0f4b185795; ?>
<?php unset($__attributesOriginalf78519ad210db5d56149fa0f4b185795); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf78519ad210db5d56149fa0f4b185795)): ?>
<?php $component = $__componentOriginalf78519ad210db5d56149fa0f4b185795; ?>
<?php unset($__componentOriginalf78519ad210db5d56149fa0f4b185795); ?>
<?php endif; ?>
        <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3"><?php $__empty_1 = true; $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if (isset($component)) { $__componentOriginale07892df06fff4909c13fe4117821ea6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale07892df06fff4909c13fe4117821ea6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.discovery.program-card','data' => ['program' => $program]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('discovery.program-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['program' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($program)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale07892df06fff4909c13fe4117821ea6)): ?>
<?php $attributes = $__attributesOriginale07892df06fff4909c13fe4117821ea6; ?>
<?php unset($__attributesOriginale07892df06fff4909c13fe4117821ea6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale07892df06fff4909c13fe4117821ea6)): ?>
<?php $component = $__componentOriginale07892df06fff4909c13fe4117821ea6; ?>
<?php unset($__componentOriginale07892df06fff4909c13fe4117821ea6); ?>
<?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><div
                    class="col-span-full rounded-2xl border-2 border-dashed border-border p-10 text-center text-sm text-text-secondary">
                Belum ada Program yang memenuhi kriteria publik.</div><?php endif; ?></div>
        <div class="mt-8 text-center"><a class="btn-outline" href="<?php echo e(route('programs.index')); ?>">Lihat semua Program
                <?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'chevron-right','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-right','class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></a></div>
    </div>
</section>
<section id="intelligence" class="home-section bg-surface-soft">
    <div class="home-container"><div class="flex flex-wrap items-end justify-between gap-4"><?php if (isset($component)) { $__componentOriginalf78519ad210db5d56149fa0f4b185795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf78519ad210db5d56149fa0f4b185795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-heading','data' => ['eyebrow' => 'Youth Intelligence','title' => 'Kenali Potensi Pemuda SIPORA','description' => 'Berdasarkan pemuda yang terdaftar di SIPORA, bukan seluruh populasi Kabupaten Pemalang.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Youth Intelligence','title' => 'Kenali Potensi Pemuda SIPORA','description' => 'Berdasarkan pemuda yang terdaftar di SIPORA, bukan seluruh populasi Kabupaten Pemalang.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf78519ad210db5d56149fa0f4b185795)): ?>
<?php $attributes = $__attributesOriginalf78519ad210db5d56149fa0f4b185795; ?>
<?php unset($__attributesOriginalf78519ad210db5d56149fa0f4b185795); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf78519ad210db5d56149fa0f4b185795)): ?>
<?php $component = $__componentOriginalf78519ad210db5d56149fa0f4b185795; ?>
<?php unset($__componentOriginalf78519ad210db5d56149fa0f4b185795); ?>
<?php endif; ?><a class="btn-outline" href="<?php echo e(route('statistics.index')); ?>">Lihat Statistik Pemuda</a></div>
        <div class="mt-6 grid gap-5 md:grid-cols-2">
            <?php $__currentLoopData = ['interests' => 'Minat Pemuda', 'skills' => 'Keahlian Pemuda']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $title): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php ($rows = array_slice($analytics[$key], 0, 5)); ?>
                <?php ($maximum = max([1, ...array_map(fn ($row) => $row['count'] ?? 0, $rows)])); ?>
                <section class="home-analytics-card"><h3 class="text-lg font-bold"><?php echo e($title); ?></h3><p class="mt-1 text-xs text-text-muted">Jumlah pemuda dengan pilihan yang tercatat</p>
                    <ul class="mt-5 space-y-4"><?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><li><div class="mb-2 flex justify-between gap-3 text-sm"><span><?php echo e($row['label']); ?></span><strong><?php echo e($displayCount($row['count'])); ?></strong></div><div class="home-bar-track" aria-hidden="true"><span <?php if($row['count'] !== null): ?> style="width:<?php echo e(round(100 * $row['count'] / $maximum)); ?>%" <?php else: ?> class="home-bar-suppressed" <?php endif; ?>></span></div></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><li class="text-sm text-text-secondary">Belum ada pilihan yang tercatat.</li><?php endif; ?></ul>
                </section>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <p class="mt-4 text-xs text-text-secondary">Bucket berjumlah kurang dari 5 tidak ditampilkan sebagai angka pasti atau panjang bar. Satu pemuda dapat memiliki beberapa minat dan skill.</p>
    </div>
</section>
<section id="interaksi" class="home-section">
    <div class="home-container"><?php if (isset($component)) { $__componentOriginalf78519ad210db5d56149fa0f4b185795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf78519ad210db5d56149fa0f4b185795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-heading','data' => ['eyebrow' => 'Percakapan ekosistem','title' => 'Diskusi dan Kabar Pemuda','description' => 'Diskusi Forum dan pembaruan publik dari Social Feed.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Percakapan ekosistem','title' => 'Diskusi dan Kabar Pemuda','description' => 'Diskusi Forum dan pembaruan publik dari Social Feed.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf78519ad210db5d56149fa0f4b185795)): ?>
<?php $attributes = $__attributesOriginalf78519ad210db5d56149fa0f4b185795; ?>
<?php unset($__attributesOriginalf78519ad210db5d56149fa0f4b185795); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf78519ad210db5d56149fa0f4b185795)): ?>
<?php $component = $__componentOriginalf78519ad210db5d56149fa0f4b185795; ?>
<?php unset($__componentOriginalf78519ad210db5d56149fa0f4b185795); ?>
<?php endif; ?>
        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div><div class="mb-4 flex items-center justify-between gap-3"><h3 class="text-lg font-bold">Forum terbaru</h3><a class="btn-outline" href="<?php echo e(route('forum.index')); ?>">Buka Forum</a></div><div class="space-y-4">
                <?php $__empty_1 = true; $__currentLoopData = $threads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thread): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><article class="home-highlight card-interactive"><div class="flex flex-wrap items-center gap-2"><?php if (isset($component)) { $__componentOriginale7f12d76f1f9891524de96ca143dbf2b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale7f12d76f1f9891524de96ca143dbf2b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forum.category-badge','data' => ['category' => $thread->category]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forum.category-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['category' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($thread->category)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale7f12d76f1f9891524de96ca143dbf2b)): ?>
<?php $attributes = $__attributesOriginale7f12d76f1f9891524de96ca143dbf2b; ?>
<?php unset($__attributesOriginale7f12d76f1f9891524de96ca143dbf2b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale7f12d76f1f9891524de96ca143dbf2b)): ?>
<?php $component = $__componentOriginale7f12d76f1f9891524de96ca143dbf2b; ?>
<?php unset($__componentOriginale7f12d76f1f9891524de96ca143dbf2b); ?>
<?php endif; ?><?php if (isset($component)) { $__componentOriginal525f984c6bf98c494ca20fdba7c09630 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal525f984c6bf98c494ca20fdba7c09630 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.author-identity','data' => ['author' => $thread->author]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.author-identity'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['author' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($thread->author)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal525f984c6bf98c494ca20fdba7c09630)): ?>
<?php $attributes = $__attributesOriginal525f984c6bf98c494ca20fdba7c09630; ?>
<?php unset($__attributesOriginal525f984c6bf98c494ca20fdba7c09630); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal525f984c6bf98c494ca20fdba7c09630)): ?>
<?php $component = $__componentOriginal525f984c6bf98c494ca20fdba7c09630; ?>
<?php unset($__componentOriginal525f984c6bf98c494ca20fdba7c09630); ?>
<?php endif; ?></div><h4 class="mt-3 font-bold"><a class="card-link" href="<?php echo e(route('forum.show', $thread)); ?>"><?php echo e($thread->title); ?></a></h4><p class="mt-2 line-clamp-2 text-sm leading-6 text-text-secondary"><?php echo e($thread->body); ?></p></article><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p class="home-highlight text-text-secondary">Belum ada diskusi publik.</p><?php endif; ?>
            </div></div>
            <div><div class="mb-4 flex items-center justify-between gap-3"><h3 class="text-lg font-bold">Social Feed terbaru</h3><a class="btn-outline" href="<?php echo e(route('social.index')); ?>">Buka Feed</a></div><div class="space-y-4">
                <?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><article class="home-highlight card-interactive"><?php if($post->organization): ?><a class="card-independent font-bold" href="<?php echo e(route('communities.show', $post->organization)); ?>"><?php echo e($post->organization->name); ?></a><?php else: ?><?php if (isset($component)) { $__componentOriginal525f984c6bf98c494ca20fdba7c09630 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal525f984c6bf98c494ca20fdba7c09630 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.author-identity','data' => ['author' => $post->author]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.author-identity'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['author' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post->author)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal525f984c6bf98c494ca20fdba7c09630)): ?>
<?php $attributes = $__attributesOriginal525f984c6bf98c494ca20fdba7c09630; ?>
<?php unset($__attributesOriginal525f984c6bf98c494ca20fdba7c09630); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal525f984c6bf98c494ca20fdba7c09630)): ?>
<?php $component = $__componentOriginal525f984c6bf98c494ca20fdba7c09630; ?>
<?php unset($__componentOriginal525f984c6bf98c494ca20fdba7c09630); ?>
<?php endif; ?><?php endif; ?><p class="mt-3 line-clamp-2 text-sm leading-6 text-text-secondary"><?php echo e($post->body); ?></p><a class="card-link mt-3 inline-flex text-sm font-bold" href="<?php echo e(route('social.show', $post)); ?>">Baca pembaruan <span class="sr-only"><?php echo e($loop->iteration); ?></span></a></article><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p class="home-highlight text-text-secondary">Belum ada pembaruan publik.</p><?php endif; ?>
            </div></div>
        </div>
    </div>
</section>
<section id="kontak" class="home-section bg-surface-soft">
    <div class="home-container grid gap-8 lg:grid-cols-[.8fr_1.2fr]">
        <div><?php if (isset($component)) { $__componentOriginalf78519ad210db5d56149fa0f4b185795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf78519ad210db5d56149fa0f4b185795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.section-heading','data' => ['eyebrow' => 'Layanan dan informasi','title' => 'Terhubung dengan Dindikpora']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.section-heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Layanan dan informasi','title' => 'Terhubung dengan Dindikpora']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf78519ad210db5d56149fa0f4b185795)): ?>
<?php $attributes = $__attributesOriginalf78519ad210db5d56149fa0f4b185795; ?>
<?php unset($__attributesOriginalf78519ad210db5d56149fa0f4b185795); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf78519ad210db5d56149fa0f4b185795)): ?>
<?php $component = $__componentOriginalf78519ad210db5d56149fa0f4b185795; ?>
<?php unset($__componentOriginalf78519ad210db5d56149fa0f4b185795); ?>
<?php endif; ?><p class="mt-4 leading-7 text-text-secondary">Informasi layanan SIPORA dan lokasi yang telah dikonfirmasi oleh pengelola proyek.</p>
            <?php $__currentLoopData = ['address' => 'Alamat', 'email' => 'Email', 'phone' => 'Telepon', 'service_hours' => 'Jam layanan']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(config('sipora.contact.'.$key)): ?><p class="mt-4 text-sm"><strong class="block"><?php echo e($label); ?></strong><?php echo e(config('sipora.contact.'.$key)); ?></p><?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <div class="mt-6 flex flex-wrap gap-3"><a class="btn-outline" href="<?php echo e(route('contact')); ?>">Informasi Kontak</a><?php if(config('sipora.contact.maps_url')): ?><a class="btn-accent" href="<?php echo e(config('sipora.contact.maps_url')); ?>" target="_blank" rel="noopener noreferrer">Lihat di Google Maps</a><?php endif; ?></div>
            <p class="mt-4 text-xs leading-6 text-text-muted">Lokasi pada embed: Dinas Pendidikan dan Kebudayaan Kabupaten Pemalang. Alamat jalan dan kontak ditampilkan ketika dikonfigurasi.</p>
        </div>
        <?php if(config('sipora.contact.maps_embed_url')): ?><iframe class="home-contact-map" src="<?php echo e(config('sipora.contact.maps_embed_url')); ?>" title="Lokasi layanan di Kabupaten Pemalang" loading="lazy" allowfullscreen referrerpolicy="strict-origin-when-cross-origin"></iframe><?php endif; ?>
    </div>
</section>
</div>
<script id="eco-map-data" type="application/json"><?php echo json_encode(['districts' => $coverage, 'admin' => false, 'threshold' => 5]) ?></script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('footer'); ?><?php if (isset($component)) { $__componentOriginalbb84be681bbe94cc31d6257779433433 = $component; } ?>
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
<?php endif; ?><?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/home.blade.php ENDPATH**/ ?>