
<?php $__env->startSection('title', 'Development Pathway · SIPORA'); ?>
<?php $__env->startSection('content'); ?>
<div class="mx-auto max-w-7xl space-y-7">
    <?php if (isset($component)) { $__componentOriginal8e0a96c106aaf9b655be7405167c4255 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8e0a96c106aaf9b655be7405167c4255 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workspace.page-header','data' => ['eyebrow' => 'Ruang bertumbuh','title' => 'Development Pathway','description' => 'Lihat arah minatmu, pengalaman yang sudah tercatat, dan langkah berikutnya dari ekosistem SIPORA.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workspace.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Ruang bertumbuh','title' => 'Development Pathway','description' => 'Lihat arah minatmu, pengalaman yang sudah tercatat, dan langkah berikutnya dari ekosistem SIPORA.']); ?>
        <a class="btn-secondary" href="<?php echo e(route('youth.portfolio.show')); ?>">Buka Portfolio</a>
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

    <section class="sipora-card border-l-4 border-l-accent-500 p-5 sm:p-7" aria-labelledby="pathway-direction">
        <p class="text-xs font-extrabold uppercase tracking-[.15em] text-interactive">01 · Di mana kamu sekarang</p>
        <h2 id="pathway-direction" class="mt-2 font-heading text-2xl font-bold text-text-primary">Arah Pengembanganmu</h2>
        <p class="mt-2 text-sm leading-6 text-text-secondary">Berdasarkan minat yang kamu pilih sendiri. Ini bukan penilaian kemampuan atau prediksi karier.</p>
        <?php if($pathway['interests']): ?>
            <div class="mt-5 flex flex-wrap gap-2"><?php $__currentLoopData = $pathway['interests']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $interest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><span class="border border-border bg-surface-soft px-3 py-2 text-sm font-bold text-text-primary"><?php echo e($interest); ?></span><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div>
        <?php else: ?>
            <p class="mt-5 border border-dashed border-border bg-surface-soft p-4 text-sm text-text-secondary">Belum ada minat yang dipilih.</p>
        <?php endif; ?>
    </section>

    <section class="sipora-card p-5 sm:p-7" aria-labelledby="pathway-assets">
        <p class="text-xs font-extrabold uppercase tracking-[.15em] text-interactive">02 · Yang sudah kamu miliki</p>
        <h2 id="pathway-assets" class="mt-2 font-heading text-2xl font-bold text-text-primary">Jejak pengembanganmu</h2>
        <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="border border-border bg-surface-soft p-4"><p class="text-sm text-text-secondary">Minat</p><strong class="mt-2 block text-2xl text-text-primary"><?php echo e(count($pathway['interests'])); ?></strong><a class="mt-3 inline-block text-sm font-bold text-interactive underline" href="<?php echo e(route('youth.interests.edit')); ?>">Kelola minat</a></div>
            <div class="border border-border bg-surface-soft p-4"><p class="text-sm text-text-secondary">Keahlian</p><strong class="mt-2 block text-2xl text-text-primary"><?php echo e(count($pathway['skills'])); ?></strong><a class="mt-3 inline-block text-sm font-bold text-interactive underline" href="<?php echo e(route('youth.skills.edit')); ?>">Kelola keahlian</a></div>
            <div class="border border-border bg-surface-soft p-4"><p class="text-sm text-text-secondary">Activity selesai</p><strong class="mt-2 block text-2xl text-text-primary"><?php echo e($pathway['completedCount']); ?></strong><a class="mt-3 inline-block text-sm font-bold text-interactive underline" href="<?php echo e(route('youth.passport.index')); ?>">Lihat Passport</a></div>
            <div class="border border-border bg-surface-soft p-4"><p class="text-sm text-text-secondary">Sertifikat SIPORA</p><strong class="mt-2 block text-2xl text-text-primary"><?php echo e($pathway['certificateCount']); ?></strong><a class="mt-3 inline-block text-sm font-bold text-interactive underline" href="<?php echo e(route('youth.certificates.index')); ?>">Lihat sertifikat</a></div>
        </div>
        <?php if($pathway['skills']): ?><div class="mt-5 flex flex-wrap gap-2" aria-label="Keahlian yang tercatat"><?php $__currentLoopData = $pathway['skills']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><span class="border border-border bg-surface px-3 py-1.5 text-xs font-semibold text-text-primary"><?php echo e($skill); ?></span><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div><?php endif; ?>
    </section>

    <section aria-labelledby="pathway-next">
        <div class="mb-4"><p class="text-xs font-extrabold uppercase tracking-[.15em] text-interactive">03 · Apa yang bisa kamu lakukan</p><h2 id="pathway-next" class="mt-2 font-heading text-2xl font-bold text-text-primary">Langkah Berikutnya</h2><p class="mt-2 text-sm text-text-secondary">Pilihan di bawah didasarkan pada kategori dan istilah yang terkait dengan minat, keahlian, atau Activity yang pernah kamu selesaikan.</p></div>
        <?php if (! ($pathway['ready'])): ?>
            <div class="sipora-card p-6 sm:p-8"><h3 class="font-heading text-xl font-bold text-text-primary">Lengkapi minat dan keahlianmu</h3><p class="mt-2 max-w-2xl text-sm leading-6 text-text-secondary">Tambahkan minat dan keahlian agar SIPORA dapat memberikan rekomendasi yang lebih relevan. Kami belum menampilkan pilihan acak untukmu.</p><div class="mt-5 flex flex-wrap gap-3"><a class="btn-primary" href="<?php echo e(route('youth.interests.edit')); ?>">Kelola Minat</a><a class="btn-secondary" href="<?php echo e(route('youth.skills.edit')); ?>">Kelola Keahlian</a></div></div>
        <?php else: ?>
            <div class="grid gap-5 xl:grid-cols-3">
                <?php $__currentLoopData = ['activity' => ['Activity', 'Kegiatan yang masih menerima pendaftaran', 'activities.show', 'activities.index'], 'community' => ['Community', 'Komunitas aktif untuk bertumbuh bersama', 'communities.show', 'communities.index'], 'opportunity' => ['Opportunity', 'Peluang publik yang masih tersedia', 'opportunities.show', 'opportunities.index']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => [$label, $description, $showRoute, $indexRoute]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <section class="sipora-card min-w-0 p-5" aria-labelledby="pathway-<?php echo e($type); ?>">
                        <div class="flex items-start justify-between gap-3"><div><p class="text-xs font-extrabold uppercase tracking-widest text-interactive"><?php echo e($label); ?></p><h3 id="pathway-<?php echo e($type); ?>" class="mt-1 font-heading text-xl font-bold text-text-primary"><?php echo e($description); ?></h3></div><span class="border border-border bg-surface-soft px-2 py-1 text-xs font-bold text-text-primary"><?php echo e(count($pathway['recommendations'][$type])); ?></span></div>
                        <div class="mt-5 space-y-3">
                            <?php $__empty_1 = true; $__currentLoopData = $pathway['recommendations'][$type]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recommendation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php ($item = $recommendation['item']); ?>
                                <article class="card-interactive border border-border bg-surface-soft p-4">
                                    <p class="text-xs font-bold uppercase tracking-wider text-interactive"><?php echo e($item->category?->name ?? $label); ?></p>
                                    <h4 class="mt-1 text-base font-extrabold text-text-primary"><?php echo e($type === 'community' ? $item->name : $item->title); ?></h4>
                                    <p class="mt-1 text-xs text-text-secondary"><?php if($type === 'activity'): ?> Pendaftaran dibuka · <?php echo e($item->start_at->translatedFormat('d M Y')); ?> <?php elseif($type === 'community'): ?> Community aktif <?php else: ?> Tersedia<?php echo e($item->deadline_at ? ' · Batas '.$item->deadline_at->translatedFormat('d M Y') : ''); ?> <?php endif; ?></p>
                                    <ul class="mt-3 space-y-1 border-t border-border pt-3 text-xs leading-5 text-text-secondary"><?php $__currentLoopData = $recommendation['reasons']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reason): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li>✓ <?php echo e($reason); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
                                    <a class="card-link btn-secondary mt-4 !min-h-9 !px-3 text-xs" href="<?php echo e(route($showRoute, $item)); ?>">Lihat <?php echo e($label); ?></a>
                                </article>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p class="border border-dashed border-border p-4 text-sm leading-6 text-text-secondary">Belum ada <?php echo e($label); ?> tersedia yang terkait dengan data profilmu saat ini.</p>
                            <?php endif; ?>
                        </div>
                        <a class="mt-5 inline-block text-sm font-bold text-interactive underline" href="<?php echo e(route($indexRoute)); ?>">Jelajahi semua <?php echo e($label); ?></a>
                    </section>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </section>

    <section class="sipora-card p-5 sm:p-7" aria-labelledby="pathway-history">
        <p class="text-xs font-extrabold uppercase tracking-[.15em] text-interactive">04 · Riwayat nyata</p>
        <h2 id="pathway-history" class="mt-2 font-heading text-2xl font-bold text-text-primary">Riwayat Pengembangan</h2>
        <p class="mt-2 text-sm text-text-secondary">Diambil dari Activity Passport yang sudah selesai. Sertifikat ditampilkan hanya jika benar-benar diterbitkan.</p>
        <?php if($pathway['activeParticipations']->isNotEmpty()): ?>
            <h3 class="mt-5 font-bold text-text-primary">Sedang diikuti</h3>
            <div class="mt-3 grid gap-3 sm:grid-cols-2"><?php $__currentLoopData = $pathway['activeParticipations']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="border border-border bg-surface-soft p-4"><p class="text-xs font-bold uppercase tracking-wider text-interactive">Partisipasi diterima</p><p class="mt-1 font-bold text-text-primary"><?php echo e($entry->activity->title); ?></p><p class="mt-1 text-xs text-text-secondary"><?php echo e($entry->activity->organization?->name); ?></p></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div>
        <?php endif; ?>
        <?php if($pathway['joinedCommunities']->isNotEmpty()): ?>
            <h3 class="mt-5 font-bold text-text-primary">Community yang diikuti</h3>
            <div class="mt-3 flex flex-wrap gap-2"><?php $__currentLoopData = $pathway['joinedCommunities']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $membership): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><span class="border border-border bg-surface-soft px-3 py-2 text-sm font-semibold text-text-primary"><?php echo e($membership->organization?->name); ?></span><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div>
        <?php endif; ?>
        <div class="mt-5 space-y-3">
            <?php $__empty_1 = true; $__currentLoopData = $pathway['history']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <article class="flex flex-wrap items-center justify-between gap-4 border border-border bg-surface-soft p-4"><div><p class="text-xs font-bold uppercase tracking-wider text-interactive"><?php echo e($entry->activity->category?->name ?? 'Activity'); ?> · <?php echo e($entry->completed_at?->translatedFormat('d M Y')); ?></p><h3 class="mt-1 font-bold text-text-primary"><?php echo e($entry->activity->title); ?></h3><p class="mt-1 text-sm text-text-secondary"><?php echo e($entry->activity->organization?->name); ?> · <?php echo e($entry->present_count); ?> dari <?php echo e($entry->activity->sessions_count); ?> sesi hadir</p></div><div class="flex flex-wrap gap-2"><a class="btn-secondary !min-h-9 !px-3 text-xs" href="<?php echo e(route('youth.passport.show', $entry)); ?>">Passport</a><?php if($entry->certificate): ?><a class="btn-secondary !min-h-9 !px-3 text-xs" href="<?php echo e(route('youth.certificates.show', $entry->certificate)); ?>">Sertifikat</a><?php endif; ?></div></article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="border border-dashed border-border p-4 text-sm text-text-secondary">Belum ada Activity selesai di Passport-mu.</p>
            <?php endif; ?>
        </div>
        <?php if($pathway['completedCount'] > count($pathway['history'])): ?><a class="mt-5 inline-block text-sm font-bold text-interactive underline" href="<?php echo e(route('youth.passport.index')); ?>">Lihat seluruh riwayat Passport</a><?php endif; ?>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.youth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/youth/development-pathway/index.blade.php ENDPATH**/ ?>