<?php $__env->startSection('title', $title.' · SIPORA'); ?>
<?php $__env->startSection($workspace === 'admin' ? 'content' : 'verifier-content'); ?>
<div class="space-y-6">
    <?php if (isset($component)) { $__componentOriginal91a231a9270579fa1ae9246bd51fb785 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91a231a9270579fa1ae9246bd51fb785 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.page-header','data' => ['eyebrow' => 'Data Ekosistem','title' => $title,'description' => 'Direktori data terdaftar SIPORA, terpisah dari antrean verifikasi.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Data Ekosistem','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($title),'description' => 'Direktori data terdaftar SIPORA, terpisah dari antrean verifikasi.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $attributes = $__attributesOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $component = $__componentOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__componentOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?>
    <p class="text-sm text-text-secondary"><?php echo e(number_format($records->total())); ?> record sesuai filter. Halaman ini hanya untuk membaca data. <?php if($kind === 'youth'): ?>Portfolio privat dan bagian yang disembunyikan tetap dibatasi; dokumen identitas tidak ditampilkan.<?php endif; ?></p>
    <?php if (isset($component)) { $__componentOriginalf27517909e1bf5f5d5b4857961084a12 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf27517909e1bf5f5d5b4857961084a12 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.filter-bar','data' => ['resetHref' => route($workspace.'.directory.'.$kind.'.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.filter-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['reset-href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route($workspace.'.directory.'.$kind.'.index'))]); ?>
        <label><span class="label">Cari nama <?php echo e($kind === 'youth' ? 'pemuda' : 'atau judul'); ?></span><input class="field" name="q" maxlength="120" value="<?php echo e($filters['q'] ?? ''); ?>" placeholder="Cari di direktori"></label>
        <?php if($kind === 'youth'): ?>
            <label><span class="label">Kecamatan</span><select class="field" name="district"><option value="">Semua kecamatan</option><?php $__currentLoopData = $districts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($district->code); ?>" <?php if(($filters['district'] ?? '') === $district->code): echo 'selected'; endif; ?>><?php echo e($district->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label>
            <label><span class="label">Visibilitas Portfolio</span><select class="field" name="profile"><option value="">Semua</option><option value="public" <?php if(($filters['profile'] ?? '') === 'public'): echo 'selected'; endif; ?>>Publik</option><option value="private" <?php if(($filters['profile'] ?? '') === 'private'): echo 'selected'; endif; ?>>Privat</option></select></label>
            <?php if($workspace === 'admin'): ?><label><span class="label">Status identitas</span><select class="field" name="identity"><option value="">Semua</option><?php $__currentLoopData = ['unverified'=>'Belum terverifikasi','pending'=>'Menunggu review','verified'=>'Terverifikasi','revision'=>'Perlu revisi','rejected'=>'Ditolak']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($value); ?>" <?php if(($filters['identity'] ?? '') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label><?php endif; ?>
        <?php else: ?>
            <label><span class="label"><?php echo e($kind === 'programs' ? 'Pelaksanaan' : 'Status verifikasi'); ?></span><select class="field" name="status"><option value="">Semua</option><?php $__currentLoopData = $kind === 'programs' ? ['planned'=>'Direncanakan','running'=>'Berjalan','completed'=>'Selesai','cancelled'=>'Dibatalkan'] : ['draft'=>'Draft','pending_review'=>'Menunggu review','revision'=>'Perlu revisi','rejected'=>'Ditolak','approved'=>'Disetujui']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($value); ?>" <?php if(($filters['status'] ?? '') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label>
            <?php if($kind === 'communities'): ?>
                <label><span class="label">Kategori</span><select class="field" name="category"><option value="">Semua kategori</option><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($category->slug); ?>" <?php if(($filters['category'] ?? '') === $category->slug): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label>
                <label><span class="label">Operasional</span><select class="field" name="operational"><option value="">Semua</option><option value="active" <?php if(($filters['operational'] ?? '') === 'active'): echo 'selected'; endif; ?>>Aktif</option><option value="inactive" <?php if(($filters['operational'] ?? '') === 'inactive'): echo 'selected'; endif; ?>>Tidak aktif</option></select></label>
            <?php elseif($kind === 'activities'): ?><label><span class="label">Publikasi</span><select class="field" name="publication"><option value="">Semua</option><?php $__currentLoopData = ['published'=>'Terbit','unpublished'=>'Belum terbit','archived'=>'Diarsipkan']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($value); ?>" <?php if(($filters['publication'] ?? '') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label><?php endif; ?>
        <?php endif; ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf27517909e1bf5f5d5b4857961084a12)): ?>
<?php $attributes = $__attributesOriginalf27517909e1bf5f5d5b4857961084a12; ?>
<?php unset($__attributesOriginalf27517909e1bf5f5d5b4857961084a12); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf27517909e1bf5f5d5b4857961084a12)): ?>
<?php $component = $__componentOriginalf27517909e1bf5f5d5b4857961084a12; ?>
<?php unset($__componentOriginalf27517909e1bf5f5d5b4857961084a12); ?>
<?php endif; ?>
    <section class="table-shell">
        <div class="overflow-x-auto" tabindex="0" role="region" aria-label="Tabel <?php echo e($title); ?>, geser horizontal jika diperlukan">
            <table class="w-full min-w-[960px] text-left"><caption class="sr-only"><?php echo e($title); ?></caption><thead><tr><th scope="col"><?php echo e($kind === 'youth' || $kind === 'communities' ? 'Nama' : 'Judul'); ?></th><?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><th scope="col"><?php echo e($column); ?></th><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><th scope="col">Detail</th></tr></thead><tbody>
            <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-t border-border hover:bg-surface-soft"><th scope="row" class="min-w-56 px-5 py-4 text-left font-semibold"><a href="<?php echo e($record['url']); ?>" class="flex items-center gap-3 text-text-primary hover:text-interactive"><?php if($record['photo']): ?><img data-media-fallback src="<?php echo e($record['photo']); ?>" alt="" class="size-11 shrink-0 rounded border border-border bg-white object-contain" loading="lazy"><?php endif; ?><span class="break-words"><?php echo e($record['name']); ?></span></a></th><?php $__currentLoopData = $record['cells']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cell): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><td class="min-w-28 max-w-xs break-words"><?php echo e($cell); ?></td><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><td><a class="btn-outline btn-sm whitespace-nowrap" href="<?php echo e($record['url']); ?>" aria-label="Detail <?php echo e($record['name']); ?>">Lihat detail</a></td></tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="<?php echo e(count($columns) + 2); ?>"><?php if (isset($component)) { $__componentOriginal3607a477fdef7402bc742abad5df9c51 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3607a477fdef7402bc742abad5df9c51 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.empty-state','data' => ['icon' => 'users','title' => 'Tidak ada data yang sesuai','description' => 'Belum ada record terdaftar atau tidak ada yang cocok dengan filter ini.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'users','title' => 'Tidak ada data yang sesuai','description' => 'Belum ada record terdaftar atau tidak ada yang cocok dengan filter ini.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $attributes = $__attributesOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__attributesOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $component = $__componentOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__componentOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?></td></tr><?php endif; ?>
            </tbody></table>
        </div>
    </section>
    <?php echo e($records->links()); ?>

    <?php if($kind === 'youth' && $workspace === 'verifier'): ?><p class="text-xs text-text-secondary">Pencarian nama dan kecamatan hanya mencocokkan Portfolio publik. Profil privat tetap tercatat dengan nama netral, tanpa minat, skill, atau detail partisipasi yang disembunyikan.</p><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($workspace === 'admin' ? 'layouts.admin' : 'layouts.verifier', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/government/directory/index.blade.php ENDPATH**/ ?>