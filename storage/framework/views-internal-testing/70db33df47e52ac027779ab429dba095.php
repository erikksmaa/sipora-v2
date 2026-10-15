
<?php $__env->startSection('title', 'Statistik Pemuda · SIPORA'); ?>
<?php $__env->startSection('meta_description', 'Statistik agregat pemuda yang terdaftar dan beraktivitas di SIPORA.'); ?>
<?php $__env->startSection('canonical', route('statistics.index')); ?>
<?php $__env->startPush('head'); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/statistics.css', 'resources/js/statistics.js']); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginal780d11c2fd95c33fc53cd02cfb936d73 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal780d11c2fd95c33fc53cd02cfb936d73 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.page-intro','data' => ['eyebrow' => 'Data ekosistem','title' => 'Statistik Pemuda','description' => 'Berdasarkan pemuda yang terdaftar di SIPORA. Angka ini tidak mewakili seluruh populasi pemuda Kabupaten Pemalang.','icon' => 'users','variant' => 'youth']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.page-intro'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Data ekosistem','title' => 'Statistik Pemuda','description' => 'Berdasarkan pemuda yang terdaftar di SIPORA. Angka ini tidak mewakili seluruh populasi pemuda Kabupaten Pemalang.','icon' => 'users','variant' => 'youth']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal780d11c2fd95c33fc53cd02cfb936d73)): ?>
<?php $attributes = $__attributesOriginal780d11c2fd95c33fc53cd02cfb936d73; ?>
<?php unset($__attributesOriginal780d11c2fd95c33fc53cd02cfb936d73); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal780d11c2fd95c33fc53cd02cfb936d73)): ?>
<?php $component = $__componentOriginal780d11c2fd95c33fc53cd02cfb936d73; ?>
<?php unset($__componentOriginal780d11c2fd95c33fc53cd02cfb936d73); ?>
<?php endif; ?>
<div class="stats-dashboard"><div class="stats-container">
    <div class="mb-5 flex justify-end"><a class="btn-secondary" href="<?php echo e(route('ecosystem-map.index')); ?>">Jelajahi Peta Ekosistem</a></div>
    <section x-data="{ filtersOpen: false }" aria-label="Pilihan filter statistik">
        <button class="btn-outline w-full justify-between md:hidden" type="button" @click="filtersOpen = !filtersOpen" :aria-expanded="filtersOpen" aria-controls="statistics-filter-panel">Filter statistik <?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'filter','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'filter','class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></button>
        <div id="statistics-filter-panel" class="md:block" :class="{ hidden: !filtersOpen }">
    <form method="GET" action="<?php echo e(route('statistics.index')); ?>" class="stats-filters" aria-label="Filter statistik pemuda">
        <label>Tahun<select name="year"><option value="">Semua tahun</option><?php $__currentLoopData = $options['years']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($year); ?>" <?php if(($filters['year'] ?? null) === $year): echo 'selected'; endif; ?>><?php echo e($year); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label>
        <label>Kecamatan<select name="district"><option value="">Semua kecamatan</option><?php $__currentLoopData = $options['districts']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($district['code']); ?>" <?php if(($filters['district'] ?? null) === $district['code']): echo 'selected'; endif; ?>><?php echo e($district['name']); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label>
        <label>Rentang usia<select name="age"><option value="">Semua usia</option><?php $__currentLoopData = $options['ages']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($key); ?>" <?php if(($filters['age'] ?? null) === $key): echo 'selected'; endif; ?>><?php echo e($label); ?> tahun</option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label>
        <button type="submit" class="stats-filter-button">Terapkan filter</button>
        <?php if($filters): ?><a class="stats-reset" href="<?php echo e(route('statistics.index')); ?>">Atur ulang</a><?php endif; ?>
    </form>
        </div>
    </section>
    <section aria-labelledby="stats-headline">
        <div class="stats-section-heading"><div><p class="stats-eyebrow">Ekosistem dalam angka</p><h2 id="stats-headline">Gambaran pemuda SIPORA</h2></div><p>Berdasarkan pemuda yang terdaftar di SIPORA</p></div>
        <div class="stats-headline-grid">
            <?php $__currentLoopData = [['registered','Pemuda Terdaftar','users'],['identity_verified','Identitas Terverifikasi','shield-check'],['activity_participated','Pernah Mengikuti Activity','activity'],['certificate','Memiliki Sertifikat SIPORA','award']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$key,$label,$icon]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if (isset($component)) { $__componentOriginal7b56645fd65e0290724a89216e5d8eee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7b56645fd65e0290724a89216e5d8eee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.stat-card','data' => ['icon' => $icon,'value' => $statistics['totals'][$key] === null ? '<'.$threshold : number_format($statistics['totals'][$key]),'label' => $label,'tone' => 'blue']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($icon),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($statistics['totals'][$key] === null ? '<'.$threshold : number_format($statistics['totals'][$key])),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($label),'tone' => 'blue']); ?>
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
    </section>
    <section class="stats-panel stats-panel-wide" aria-labelledby="stats-growth-title">
        <div class="stats-panel-heading"><div><p class="stats-eyebrow">Pertumbuhan</p><h2 id="stats-growth-title">Pendaftaran Youth</h2><p>Jumlah akun Youth baru pada periode yang dipilih.</p></div><div class="stats-segment" role="group" aria-label="Resolusi grafik pertumbuhan"><button type="button" data-growth-mode="monthly" aria-pressed="true">Per bulan</button><button type="button" data-growth-mode="yearly" aria-pressed="false">Per tahun</button></div></div>
        <div class="stats-canvas stats-canvas-growth"><canvas data-chart="growth" aria-hidden="true"></canvas></div>
        <details class="stats-data"><summary>Lihat data pertumbuhan</summary><div class="stats-data-columns"><?php $__currentLoopData = ['growth_monthly' => 'Bulanan','growth_yearly' => 'Tahunan']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div><h3><?php echo e($label); ?></h3><dl><?php $__currentLoopData = $statistics[$key]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div><dt><?php echo e($row['label']); ?></dt><dd><?php echo e($row['count'] === null ? '<'.$threshold : number_format($row['count'])); ?></dd></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></dl></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div></details>
    </section>
    <div class="stats-grid-two">
        <?php $__currentLoopData = [['age','Distribusi usia','Rentang usia dihitung dari tanggal lahir.'],['gender','Distribusi gender','Sesuai biodata yang diisi.']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$key,$title,$description]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <section class="stats-panel" aria-labelledby="stats-<?php echo e($key); ?>"><p class="stats-eyebrow">Demografi</p><h2 id="stats-<?php echo e($key); ?>"><?php echo e($title); ?></h2><p><?php echo e($description); ?></p><div class="stats-canvas"><canvas data-chart="<?php echo e($key); ?>" aria-hidden="true"></canvas></div><?php echo $__env->make('public.statistics.rows', ['rows' => $statistics[$key], 'threshold' => $threshold, 'kind' => $key], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></section>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <section class="stats-panel stats-panel-wide" aria-labelledby="stats-districts"><p class="stats-eyebrow">Persebaran</p><h2 id="stats-districts">Kecamatan domisili</h2><p>Berdasarkan alamat domisili utama yang diisi Youth.</p><div class="stats-canvas stats-canvas-tall"><canvas data-chart="districts" aria-hidden="true"></canvas></div><?php echo $__env->make('public.statistics.rows', ['rows' => $statistics['districts'], 'threshold' => $threshold, 'kind' => 'districts'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></section>
    <div class="stats-grid-two">
        <?php $__currentLoopData = [['interests','Top Interest','Minat yang dipilih pada profil.'],['skills','Top Skill','Keahlian yang dipilih pada profil.']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$key,$title,$description]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <section class="stats-panel" aria-labelledby="stats-<?php echo e($key); ?>"><p class="stats-eyebrow">Minat & keahlian</p><h2 id="stats-<?php echo e($key); ?>"><?php echo e($title); ?></h2><p><?php echo e($description); ?></p><div class="stats-canvas stats-canvas-tall"><canvas data-chart="<?php echo e($key); ?>" aria-hidden="true"></canvas></div><?php echo $__env->make('public.statistics.rows', ['rows' => $statistics[$key], 'threshold' => $threshold, 'kind' => $key], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></section>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <section class="stats-panel stats-panel-wide" aria-labelledby="stats-journey"><p class="stats-eyebrow">Partisipasi</p><h2 id="stats-journey">Perjalanan Youth</h2><p>Setiap tahap menghitung Youth unik yang memenuhi tahap tersebut serta seluruh tahap sebelumnya. Profil lengkap mencakup biodata, domisili, bio, dan status pekerjaan; mengikuti Activity berarti pendaftaran diterima.</p><ol class="stats-journey"><?php ($journeyMax = max(1, $statistics['funnel'][0]['count'] ?? 1)); ?><?php $__currentLoopData = $statistics['funnel']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><div class="stats-journey-top"><span class="stats-step"><?php echo e($loop->iteration); ?></span><span class="stats-journey-label"><?php echo e($step['label']); ?></span><strong><?php echo e($step['count'] === null ? '<'.$threshold : number_format($step['count'])); ?></strong></div><div class="stats-journey-track" aria-hidden="true"><span style="width: <?php echo e($step['count'] === null ? 0 : round(100 * $step['count'] / $journeyMax)); ?>%"></span></div></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ol></section>
    <section class="stats-panel stats-panel-wide" aria-labelledby="stats-participation"><p class="stats-eyebrow">Keterlibatan nyata</p><h2 id="stats-participation">Partisipasi di SIPORA</h2><p>Setiap angka menghitung Youth unik, bukan jumlah pendaftaran atau sesi. Tahap ini berdiri sendiri dan tidak membentuk funnel.</p><div class="stats-participation-grid"><?php $__currentLoopData = $statistics['participation']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="stats-participation-item"><span><?php echo e($row['label']); ?></span><strong><?php echo e($row['count'] === null ? '<'.$threshold : number_format($row['count'])); ?></strong></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div></section>
    <section class="stats-panel stats-panel-wide" aria-labelledby="stats-coverage"><p class="stats-eyebrow">Cakupan wilayah</p><h2 id="stats-coverage">Youth dan kegiatan per kecamatan</h2><p>Domisili Youth dibandingkan dengan lokasi Activity terbit, Community aktif, dan Program publik. Filter hanya berlaku pada kolom Youth dan pernah mengikuti Activity. Record tanpa kecamatan tidak masuk tabel wilayah. Jumlah ini tidak merepresentasikan seluruh penduduk.</p><div class="stats-table-wrap"><table class="stats-coverage-table"><thead><tr><th scope="col">Kecamatan</th><th scope="col">Youth</th><th scope="col">Ikut Activity</th><th scope="col">Community</th><th scope="col">Activity</th><th scope="col">Program</th></tr></thead><tbody><?php $__empty_1 = true; $__currentLoopData = $statistics['coverage']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr><th scope="row"><?php echo e($row['district']); ?></th><?php $__currentLoopData = ['youth','participated','community','activity','program']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><td><?php echo e($row[$key] === null ? '<'.$threshold : number_format($row[$key])); ?></td><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="6">Belum ada data wilayah.</td></tr><?php endif; ?></tbody></table></div></section>
    <section aria-labelledby="stats-ecosystem"><div class="stats-section-heading"><div><p class="stats-eyebrow">Aktivitas ekosistem</p><h2 id="stats-ecosystem">Activity, Community & Program</h2></div><p>Jumlah record berdasarkan status operasional. Bagian ini mencakup seluruh ekosistem dan tidak dipengaruhi filter Youth.</p></div><div class="stats-grid-three"><?php $__currentLoopData = ['activity' => 'Activity terbit', 'community' => 'Community disetujui', 'program' => 'Program']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $title): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="stats-panel"><h3><?php echo e($title); ?></h3><div class="stats-canvas stats-canvas-small"><canvas data-chart="ecosystem-<?php echo e($key); ?>" aria-hidden="true"></canvas></div><?php echo $__env->make('public.statistics.rows', ['rows' => $statistics['ecosystem'][$key], 'threshold' => $threshold, 'kind' => 'status'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div></section>
    <p class="stats-privacy">Angka kategori/filter di bawah <?php echo e($threshold); ?> ditampilkan sebagai “&lt;<?php echo e($threshold); ?>” untuk menjaga privasi. Statistik ini hanya menggambarkan akun Youth yang terdaftar di SIPORA.</p>
</div></div>
<script id="statistics-data" type="application/json"><?php echo json_encode($statistics, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT, 512) ?></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/public/statistics/index.blade.php ENDPATH**/ ?>