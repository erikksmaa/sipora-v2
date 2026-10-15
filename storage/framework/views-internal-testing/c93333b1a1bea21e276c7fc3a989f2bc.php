
<?php $__env->startSection('title', 'Peta Ekosistem Pemuda · SIPORA'); ?>
<?php if (! ($isAdmin)): ?>
    <?php $__env->startSection('meta_description', 'Peta agregat Youth, Community, Activity, dan Program per kecamatan di SIPORA.'); ?>
    <?php $__env->startSection('canonical', route('ecosystem-map.index')); ?>
<?php endif; ?>
<?php $__env->startPush('head'); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/ecosystem-map.css', 'resources/js/ecosystem-map.js']); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<?php ($display = fn (?int $count): string => $count === null ? '<'.$threshold : number_format($count)); ?>
<?php if($isAdmin): ?>
    <?php if (isset($component)) { $__componentOriginal91a231a9270579fa1ae9246bd51fb785 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91a231a9270579fa1ae9246bd51fb785 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.page-header','data' => ['eyebrow' => 'Geographic Insight','title' => 'Peta Ekosistem Pemuda','description' => 'Sebaran agregat berdasarkan kecamatan yang tercatat di SIPORA.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Geographic Insight','title' => 'Peta Ekosistem Pemuda','description' => 'Sebaran agregat berdasarkan kecamatan yang tercatat di SIPORA.']); ?> <?php $__env->slot('actions', null, []); ?> <a class="btn-secondary" href="<?php echo e(route('admin.dashboard')); ?>">Kembali ke Dashboard</a> <?php $__env->endSlot(); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $attributes = $__attributesOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $component = $__componentOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__componentOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?>
<?php else: ?>
    <?php if (isset($component)) { $__componentOriginal780d11c2fd95c33fc53cd02cfb936d73 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal780d11c2fd95c33fc53cd02cfb936d73 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.page-intro','data' => ['eyebrow' => 'Data ekosistem','title' => 'Peta Ekosistem Pemuda','description' => 'Berdasarkan pemuda yang terdaftar di SIPORA; bukan peta seluruh populasi pemuda Kabupaten Pemalang.','icon' => 'globe','variant' => 'youth']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.page-intro'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Data ekosistem','title' => 'Peta Ekosistem Pemuda','description' => 'Berdasarkan pemuda yang terdaftar di SIPORA; bukan peta seluruh populasi pemuda Kabupaten Pemalang.','icon' => 'globe','variant' => 'youth']); ?>
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
<?php endif; ?>
<div class="eco-map-page <?php echo e($isAdmin ? 'eco-map-page-admin' : ''); ?>">
    <div class="eco-map-container">
        <div class="eco-map-intro"><div><p class="eco-map-eyebrow">Cakupan kecamatan</p><h2>Jelajahi ekosistem SIPORA per wilayah</h2><p>Empat lapisan berasal dari data aktual: domisili Youth, lokasi Community aktif, lokasi Activity terbit, dan kecamatan Community penyelenggara Program publik.</p></div><a class="btn-secondary" href="<?php echo e(route('statistics.index')); ?>">Lihat Statistik Pemuda</a></div>
        <?php if (isset($component)) { $__componentOriginalad298a9b750220090305d3cfe1fc5d9a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad298a9b750220090305d3cfe1fc5d9a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.ecosystem-map-panel','data' => ['coverage' => $coverage,'isAdmin' => $isAdmin,'threshold' => $threshold]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.ecosystem-map-panel'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['coverage' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($coverage),'is-admin' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($isAdmin),'threshold' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($threshold)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalad298a9b750220090305d3cfe1fc5d9a)): ?>
<?php $attributes = $__attributesOriginalad298a9b750220090305d3cfe1fc5d9a; ?>
<?php unset($__attributesOriginalad298a9b750220090305d3cfe1fc5d9a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalad298a9b750220090305d3cfe1fc5d9a)): ?>
<?php $component = $__componentOriginalad298a9b750220090305d3cfe1fc5d9a; ?>
<?php unset($__componentOriginalad298a9b750220090305d3cfe1fc5d9a); ?>
<?php endif; ?>
        <section class="eco-map-panel" aria-labelledby="eco-map-table-title">
            <div class="eco-map-table-heading"><div><p class="eco-map-eyebrow">Data pembanding</p><h2 id="eco-map-table-title">Cakupan menurut kecamatan</h2></div><p>Angka Youth merupakan akun unik. Community, Activity, dan Program merupakan jumlah record sesuai status publiknya.</p></div>
            <div class="eco-map-table-wrap"><table class="eco-map-table">
                <thead><tr><th scope="col">Kecamatan</th><th scope="col">Youth</th>
                    <?php if($isAdmin): ?>
                        <th scope="col">Youth ikut Activity</th>
                    <?php endif; ?>
                    <th scope="col">Community</th><th scope="col">Activity</th><th scope="col">Program</th></tr></thead>
                <tbody>
                    <?php $__currentLoopData = $coverage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr data-map-row="<?php echo e($row['code']); ?>"><th scope="row"><button type="button" data-map-select="<?php echo e($row['code']); ?>"><?php echo e($row['district']); ?></button></th><td><?php echo e($display($row['youth'])); ?></td>
                            <?php if($isAdmin): ?>
                                <td><?php echo e($display($row['participated'])); ?></td>
                            <?php endif; ?>
                            <?php $__currentLoopData = ['community', 'activity', 'program']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td><?php echo e($display($row[$key])); ?></td>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table></div>
        </section>
        <p class="eco-map-note"><?php if (! ($isAdmin)): ?>Jumlah 1–<?php echo e($threshold - 1); ?> ditampilkan sebagai “&lt;<?php echo e($threshold); ?>” dan tidak dikirim sebagai angka pasti ke peta. <?php endif; ?> Peta menampilkan agregat per kecamatan, tanpa titik lokasi atau identitas individu. Record tanpa kecamatan yang valid tidak ditempatkan di peta. Batas kecamatan digeneralisasi untuk tampilan web dari <a href="https://geoservices.big.go.id/rbi/rest/services/BATASWILAYAH/BATAS_KECAMATAN_AR/MapServer/0" target="_blank" rel="noopener noreferrer">data BIG edisi 2022</a>.</p>
    </div>
</div>
<?php ($mapData = ['districts' => $coverage, 'admin' => $isAdmin, 'threshold' => $threshold]); ?>
<script id="eco-map-data" type="application/json"><?php echo json_encode($mapData, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT, 512) ?></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($isAdmin ? 'layouts.admin' : 'layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/ecosystem-map/index.blade.php ENDPATH**/ ?>