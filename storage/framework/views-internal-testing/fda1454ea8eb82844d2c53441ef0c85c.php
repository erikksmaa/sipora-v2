
<?php $__env->startSection('title', $portfolio['identity']['name'].' · Youth Portfolio SIPORA'); ?>
<?php if(!$portfolio['is_owner']): ?>
<?php $__env->startSection('meta_description', 'Youth Portfolio SIPORA milik '.$portfolio['identity']['name'].'.'); ?>
<?php $__env->startSection('canonical', $portfolio['public_url']); ?>
<?php endif; ?>
<?php $__env->startSection('content'); ?>
<?php
    $initials = collect(explode(' ', $portfolio['identity']['name']))->filter()->take(2)->map(fn($word) => mb_substr($word, 0, 1))->implode('');
    $hidden = fn(string $section) => $portfolio['is_owner'] && !($portfolio['visibility'][$section] ?? false);
?>
<div class="<?php echo e($portfolio['is_owner'] ? '' : 'mx-auto max-w-7xl px-5 py-10'); ?> space-y-6">
    <?php if(!$portfolio['is_owner']): ?><a class="inline-flex items-center gap-2 text-sm font-bold text-primary hover:text-accent" href="<?php echo e(route('youth-directory.index')); ?>"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
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
<?php endif; ?>Direktori Pemuda</a><?php endif; ?>
    <?php if(!$portfolio['is_owner'] && ($canFollow ?? false)): ?><?php echo $__env->make('social.follow', ['targetType'=>'youth', 'targetId'=>$portfolio['public_slug'], 'followed'=>$socialFollowed], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php endif; ?>
    <?php if($portfolio['is_owner']): ?>
        <section class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-indigo-100 bg-indigo-50 px-5 py-4 text-sm text-primary">
            <div><strong>Pratinjau Portfolio pemilik.</strong> Bagian bertanda privat tetap terlihat di sini, tetapi tidak tampil kepada publik.</div>
            <div class="flex flex-wrap gap-2">
                <a class="btn-secondary !min-h-10 !px-4" href="<?php echo e(route('youth.profile.edit')); ?>">Atur profil</a>
                <a class="btn-secondary !min-h-10 !px-4" href="<?php echo e(route('youth.profile.privacy')); ?>">Privasi</a>
                <?php if($portfolio['is_public'] && $portfolio['public_url']): ?><a class="btn !min-h-10 !px-4" href="<?php echo e($portfolio['public_url']); ?>" target="_blank" rel="noopener">Lihat tampilan publik</a><?php else: ?><span class="rounded-xl bg-white px-4 py-2 font-semibold text-slate-500">Publikasi nonaktif</span><?php endif; ?>
            </div>
        </section>
        <nav class="sipora-card flex flex-wrap gap-2" aria-label="Kelola bagian Portfolio">
            <a class="btn-secondary !min-h-9" href="<?php echo e(route('youth.skills.edit')); ?>">Keahlian</a>
            <a class="btn-secondary !min-h-9" href="<?php echo e(route('youth.interests.edit')); ?>">Minat</a>
            <a class="btn-secondary !min-h-9" href="<?php echo e(route('youth.portfolio.sections.show', 'education')); ?>">Pendidikan</a>
            <a class="btn-secondary !min-h-9" href="<?php echo e(route('youth.portfolio.sections.show', 'experience')); ?>">Pengalaman</a>
            <a class="btn-secondary !min-h-9" href="<?php echo e(route('youth.portfolio.sections.show', 'achievements')); ?>">Prestasi</a>
            <a class="btn-secondary !min-h-9" href="<?php echo e(route('youth.portfolio.sections.show', 'training')); ?>">Pelatihan &amp; Sertifikasi</a>
        </nav>
    <?php endif; ?>

    <section class="portfolio-header">
        <div class="min-w-0">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
                <div class="grid size-24 shrink-0 place-items-center overflow-hidden rounded-2xl border-4 border-white bg-orange-100 text-3xl font-black text-orange-700 shadow-md sm:size-28">
                    <?php if($portfolio['identity']['photo_url']): ?><img data-media-fallback class="h-full w-full object-cover" src="<?php echo e($portfolio['identity']['photo_url']); ?>" alt="Foto <?php echo e($portfolio['identity']['name']); ?>"><?php else: ?><?php echo e($initials); ?><?php endif; ?>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2"><h1 class="text-2xl font-black text-text-primary sm:text-3xl"><?php echo e($portfolio['identity']['name']); ?></h1><span class="rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-bold text-primary">Terdata di SIPORA</span></div>
                    <?php if($portfolio['identity']['headline']): ?><p class="mt-1 font-semibold text-slate-700"><?php echo e($portfolio['identity']['headline']); ?></p><?php endif; ?>
                    <?php if($portfolio['identity']['domicile']): ?><p class="mt-1 text-sm text-slate-500">Domisili <?php echo e($portfolio['identity']['domicile']); ?>, Kabupaten Pemalang</p><?php endif; ?>
                </div>
                <?php if($portfolio['is_owner']): ?><a class="btn-secondary" href="<?php echo e(route('youth.profile.edit')); ?>">Edit profil</a><?php endif; ?>
            </div>
            <?php if($portfolio['identity']['bio']): ?><p class="mt-5 max-w-4xl whitespace-pre-line text-sm leading-7 text-slate-600"><?php echo e($portfolio['identity']['bio']); ?></p><?php elseif($portfolio['is_owner']): ?><p class="mt-5 text-sm text-slate-500">Bio belum diisi. <a class="font-bold text-primary" href="<?php echo e(route('youth.profile.edit')); ?>">Lengkapi profil</a></p><?php endif; ?>
            <?php if($portfolio['interests']): ?><div class="mt-5 flex flex-wrap gap-2"><?php $__currentLoopData = $portfolio['interests']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $interest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><span class="interest-chip"><?php echo e($interest); ?></span><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div><?php endif; ?>
            <?php if($portfolio['social_links']): ?><div class="mt-5 flex flex-wrap gap-2" aria-label="Media sosial">
                <?php $__currentLoopData = $portfolio['social_links']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $social): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><a class="btn-secondary !min-h-9 !px-3" href="<?php echo e($social['url']); ?>" target="_blank" rel="noopener noreferrer"><?php echo e($social['label']); ?> ↗</a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div><?php endif; ?>
        </div>
    </section>

    <section class="grid grid-cols-2 gap-3 sm:grid-cols-4" aria-label="Ringkasan Portfolio">
        <?php $__currentLoopData = ['activities'=>['Activity selesai','activity'],'communities'=>['Community aktif','building'],'certificates'=>['Sertifikat','award'],'achievements'=>['Prestasi','trophy']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>[$label,$icon]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="rounded-2xl border border-indigo-100 bg-white p-4 shadow-card"><div class="flex items-center gap-3"><span class="grid size-10 place-items-center rounded-xl bg-indigo-50 text-primary"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => $icon,'class' => 'size-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($icon),'class' => 'size-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></span><div><p class="font-heading text-3xl font-bold leading-none text-primary"><?php echo e($portfolio['summary'][$key]); ?></p><p class="mt-1 text-xs font-semibold text-slate-500"><?php echo e($label); ?></p></div></div></div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </section>

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.45fr)_minmax(300px,.55fr)]">
        <div class="min-w-0 space-y-6">
            <?php if($portfolio['activities'] || $portfolio['is_owner']): ?>
            <section class="sipora-card">
                <div class="flex flex-wrap items-center justify-between gap-3"><div><p class="text-xs font-extrabold uppercase tracking-wider text-orange-600">Rekam jejak resmi</p><h2 class="mt-1 text-2xl font-black text-text-primary">Pengalaman Terverifikasi SIPORA</h2></div><?php if (isset($component)) { $__componentOriginal77427818d7088fcafb4732ee4ea7535a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal77427818d7088fcafb4732ee4ea7535a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portfolio.provenance-badge','data' => ['verified' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portfolio.provenance-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['verified' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal77427818d7088fcafb4732ee4ea7535a)): ?>
<?php $attributes = $__attributesOriginal77427818d7088fcafb4732ee4ea7535a; ?>
<?php unset($__attributesOriginal77427818d7088fcafb4732ee4ea7535a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal77427818d7088fcafb4732ee4ea7535a)): ?>
<?php $component = $__componentOriginal77427818d7088fcafb4732ee4ea7535a; ?>
<?php unset($__componentOriginal77427818d7088fcafb4732ee4ea7535a); ?>
<?php endif; ?><?php if($hidden('activity_passport')): ?><span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">Privat</span><?php endif; ?></div>
                <div class="mt-5 space-y-4"><?php $__empty_1 = true; $__currentLoopData = $portfolio['activities']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <article class="rounded-2xl border border-indigo-100 bg-surface-muted p-5"><div class="flex flex-wrap items-start justify-between gap-3"><div><p class="text-xs font-bold uppercase tracking-wider text-interactive"><?php echo e($activity['category'] ?: 'Activity'); ?></p><h3 class="mt-1 text-lg font-black text-text-primary"><?php if($activity['url']): ?><a class="hover:underline" href="<?php echo e($activity['url']); ?>"><?php echo e($activity['title']); ?></a><?php else: ?><?php echo e($activity['title']); ?><?php endif; ?></h3><p class="mt-1 text-sm text-slate-600"><?php echo e($activity['organizer']); ?></p></div><span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">Selesai</span></div><dl class="mt-4 grid gap-2 text-sm sm:grid-cols-2"><div><dt class="text-slate-500">Peran</dt><dd class="font-bold capitalize"><?php echo e($activity['role']); ?></dd></div><div><dt class="text-slate-500">Periode</dt><dd class="font-bold"><?php echo e($activity['period']); ?></dd></div></dl><?php if($activity['certificate']): ?><div class="mt-4 flex flex-wrap items-center justify-between gap-3 rounded-xl bg-white p-3 text-sm"><span class="font-semibold text-slate-600">Sertifikat <?php echo e($activity['certificate']['number']); ?></span><a class="font-bold text-primary" href="<?php echo e($activity['certificate']['verify_url']); ?>">Verifikasi sertifikat →</a></div><?php endif; ?></article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p class="rounded-xl border-2 border-dashed border-slate-200 p-8 text-center text-sm text-slate-500">Belum ada Activity yang diselesaikan. <?php if($portfolio['is_owner']): ?>Activity akan muncul setelah partisipasi dinyatakan selesai oleh pengelola.<?php endif; ?></p><?php endif; ?></div>
            </section>
            <?php endif; ?>

            <?php if($portfolio['communities'] || $portfolio['is_owner']): ?>
            <section class="sipora-card"><div class="flex flex-wrap items-center justify-between gap-3"><div><p class="text-xs font-extrabold uppercase tracking-wider text-orange-600">Keterlibatan kontekstual</p><h2 class="mt-1 text-2xl font-black text-text-primary">Community SIPORA</h2></div><?php if (isset($component)) { $__componentOriginal77427818d7088fcafb4732ee4ea7535a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal77427818d7088fcafb4732ee4ea7535a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portfolio.provenance-badge','data' => ['verified' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portfolio.provenance-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['verified' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal77427818d7088fcafb4732ee4ea7535a)): ?>
<?php $attributes = $__attributesOriginal77427818d7088fcafb4732ee4ea7535a; ?>
<?php unset($__attributesOriginal77427818d7088fcafb4732ee4ea7535a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal77427818d7088fcafb4732ee4ea7535a)): ?>
<?php $component = $__componentOriginal77427818d7088fcafb4732ee4ea7535a; ?>
<?php unset($__componentOriginal77427818d7088fcafb4732ee4ea7535a); ?>
<?php endif; ?><?php if($hidden('community_membership')): ?><span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">Privat</span><?php endif; ?></div>
                <div class="mt-5 grid gap-3 sm:grid-cols-2"><?php $__empty_1 = true; $__currentLoopData = $portfolio['communities']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $community): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><a class="rounded-2xl border border-slate-200 p-4 transition hover:border-indigo-300 hover:bg-indigo-50" href="<?php echo e($community['url']); ?>"><h3 class="font-black text-text-primary"><?php echo e($community['name']); ?></h3><p class="mt-1 text-sm text-slate-600"><?php echo e($community['position'] ?: ucfirst($community['role'])); ?></p><p class="mt-2 text-xs font-bold text-emerald-700">Keanggotaan aktif<?php echo e($community['since'] ? ' sejak '.$community['since'] : ''); ?></p></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p class="text-sm text-slate-500">Belum ada keanggotaan Community aktif.</p><?php endif; ?></div>
            </section>
            <?php endif; ?>

            <?php if($portfolio['certificates'] || $portfolio['is_owner']): ?>
            <section class="sipora-card"><div class="flex flex-wrap items-center justify-between gap-3"><div><p class="text-xs font-extrabold uppercase tracking-wider text-orange-600">Kredensial resmi</p><h2 class="mt-1 text-2xl font-black text-text-primary">Sertifikat SIPORA</h2></div><?php if($hidden('certificates')): ?><span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">Privat</span><?php endif; ?></div>
                <div class="mt-5 grid gap-4 sm:grid-cols-2"><?php $__empty_1 = true; $__currentLoopData = $portfolio['certificates']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $certificate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><article class="rounded-2xl border border-indigo-100 bg-indigo-50 p-5"><?php if (isset($component)) { $__componentOriginal77427818d7088fcafb4732ee4ea7535a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal77427818d7088fcafb4732ee4ea7535a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portfolio.provenance-badge','data' => ['verified' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portfolio.provenance-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['verified' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal77427818d7088fcafb4732ee4ea7535a)): ?>
<?php $attributes = $__attributesOriginal77427818d7088fcafb4732ee4ea7535a; ?>
<?php unset($__attributesOriginal77427818d7088fcafb4732ee4ea7535a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal77427818d7088fcafb4732ee4ea7535a)): ?>
<?php $component = $__componentOriginal77427818d7088fcafb4732ee4ea7535a; ?>
<?php unset($__componentOriginal77427818d7088fcafb4732ee4ea7535a); ?>
<?php endif; ?><h3 class="mt-3 font-black text-text-primary"><?php echo e($certificate['name']); ?></h3><p class="mt-1 text-sm text-slate-600"><?php echo e($certificate['issuer']); ?></p><p class="mt-3 break-all font-mono text-xs text-slate-500"><?php echo e($certificate['number']); ?></p><p class="mt-1 text-xs text-slate-500">Terbit <?php echo e($certificate['issued_at']); ?></p><div class="mt-4 flex flex-wrap gap-3"><a class="font-bold text-primary" href="<?php echo e($certificate['verify_url']); ?>">Verifikasi publik →</a><?php if($certificate['owner_url']): ?><a class="font-bold text-orange-600" href="<?php echo e($certificate['owner_url']); ?>">Buka sertifikat</a><?php endif; ?></div></article><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p class="text-sm text-slate-500">Belum ada sertifikat SIPORA.</p><?php endif; ?></div>
            </section>
            <?php endif; ?>

            <?php if($portfolio['organization_experiences'] || $portfolio['is_owner']): ?><section class="sipora-card"><div class="flex flex-wrap items-center justify-between gap-2"><h2 class="text-xl font-black text-text-primary">Pengalaman Organisasi Mandiri</h2><?php if (isset($component)) { $__componentOriginal77427818d7088fcafb4732ee4ea7535a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal77427818d7088fcafb4732ee4ea7535a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portfolio.provenance-badge','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portfolio.provenance-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal77427818d7088fcafb4732ee4ea7535a)): ?>
<?php $attributes = $__attributesOriginal77427818d7088fcafb4732ee4ea7535a; ?>
<?php unset($__attributesOriginal77427818d7088fcafb4732ee4ea7535a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal77427818d7088fcafb4732ee4ea7535a)): ?>
<?php $component = $__componentOriginal77427818d7088fcafb4732ee4ea7535a; ?>
<?php unset($__componentOriginal77427818d7088fcafb4732ee4ea7535a); ?>
<?php endif; ?><?php if($hidden('organization_experience')): ?><span class="text-xs font-bold text-amber-700">Privat</span><?php endif; ?></div><div class="mt-4 grid gap-4 sm:grid-cols-2"><?php $__empty_1 = true; $__currentLoopData = $portfolio['organization_experiences']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $experience): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><article class="rounded-sm border border-border bg-surface-soft p-4"><h3 class="font-bold"><?php echo e($experience['organization']); ?></h3><?php if($experience['role']): ?><p class="text-sm text-text-secondary"><?php echo e($experience['role']); ?></p><?php endif; ?><p class="mt-1 text-xs text-text-muted"><?php echo e($experience['period']); ?></p><?php if($experience['evidence_url']): ?><a class="mt-3 inline-flex text-sm font-bold text-interactive hover:underline" href="<?php echo e($experience['evidence_url']); ?>" target="_blank" rel="noopener noreferrer">Buka sertifikat / bukti ↗</a><?php endif; ?></article><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p class="text-sm text-text-muted">Belum ada pengalaman organisasi mandiri.</p><?php endif; ?></div></section><?php endif; ?>

            <?php if($portfolio['achievements'] || $portfolio['is_owner']): ?>
            <section class="sipora-card">
                <div class="flex flex-wrap items-center justify-between gap-2"><h2 class="text-xl font-black text-text-primary">Prestasi</h2><?php if($hidden('achievements')): ?><span class="text-xs font-bold text-amber-700">Privat</span><?php endif; ?></div>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <?php $__empty_1 = true; $__currentLoopData = $portfolio['achievements']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $achievement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <article class="rounded-sm border border-border bg-surface-soft p-4">
                        <?php if (isset($component)) { $__componentOriginal77427818d7088fcafb4732ee4ea7535a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal77427818d7088fcafb4732ee4ea7535a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portfolio.provenance-badge','data' => ['verified' => $achievement['provenance']==='verified']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portfolio.provenance-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['verified' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($achievement['provenance']==='verified')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal77427818d7088fcafb4732ee4ea7535a)): ?>
<?php $attributes = $__attributesOriginal77427818d7088fcafb4732ee4ea7535a; ?>
<?php unset($__attributesOriginal77427818d7088fcafb4732ee4ea7535a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal77427818d7088fcafb4732ee4ea7535a)): ?>
<?php $component = $__componentOriginal77427818d7088fcafb4732ee4ea7535a; ?>
<?php unset($__componentOriginal77427818d7088fcafb4732ee4ea7535a); ?>
<?php endif; ?>
                        <h3 class="mt-2 font-bold"><?php echo e($achievement['title']); ?></h3>
                        <?php if($achievement['issuer']): ?><p class="text-sm text-text-secondary"><?php echo e($achievement['issuer']); ?></p><?php endif; ?>
                        <?php if($achievement['date']): ?><p class="mt-1 text-xs text-text-muted"><?php echo e($achievement['date']); ?></p><?php endif; ?>
                        <?php if($achievement['evidence_url']): ?><a class="mt-3 inline-flex text-sm font-bold text-interactive hover:underline" href="<?php echo e($achievement['evidence_url']); ?>" target="_blank" rel="noopener noreferrer">Buka sertifikat / bukti ↗</a><?php endif; ?>
                    </article>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p class="text-sm text-text-muted">Belum ada prestasi.</p><?php endif; ?>
                </div>
            </section>
            <?php endif; ?>

            <?php if($portfolio['is_owner']): ?><section class="sipora-card"><div class="flex items-center justify-between gap-3"><h2 class="text-xl font-black text-text-primary">Pelatihan &amp; Sertifikasi Eksternal</h2><a class="btn-secondary !min-h-9" href="<?php echo e(route('youth.portfolio.sections.show', 'training')); ?>">Kelola</a></div><div class="mt-4 space-y-3"><?php $__empty_1 = true; $__currentLoopData = $portfolio['external_certificates']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $training): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><article><?php if (isset($component)) { $__componentOriginal77427818d7088fcafb4732ee4ea7535a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal77427818d7088fcafb4732ee4ea7535a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portfolio.provenance-badge','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portfolio.provenance-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal77427818d7088fcafb4732ee4ea7535a)): ?>
<?php $attributes = $__attributesOriginal77427818d7088fcafb4732ee4ea7535a; ?>
<?php unset($__attributesOriginal77427818d7088fcafb4732ee4ea7535a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal77427818d7088fcafb4732ee4ea7535a)): ?>
<?php $component = $__componentOriginal77427818d7088fcafb4732ee4ea7535a; ?>
<?php unset($__componentOriginal77427818d7088fcafb4732ee4ea7535a); ?>
<?php endif; ?><h3 class="font-bold"><?php echo e($training['name']); ?></h3><p class="text-sm text-text-secondary"><?php echo e($training['issuer']); ?> · <?php echo e($training['issued_at']); ?></p></article><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p class="text-sm text-text-muted">Belum ada catatan pelatihan.</p><?php endif; ?></div></section><?php endif; ?>
        </div>

        <aside class="min-w-0 space-y-6">
            <?php if($portfolio['skills'] || $portfolio['is_owner']): ?><section class="sipora-card"><div class="flex items-center justify-between gap-3"><h2 class="text-xl font-black text-text-primary">Keahlian</h2><?php if (isset($component)) { $__componentOriginal77427818d7088fcafb4732ee4ea7535a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal77427818d7088fcafb4732ee4ea7535a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portfolio.provenance-badge','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portfolio.provenance-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal77427818d7088fcafb4732ee4ea7535a)): ?>
<?php $attributes = $__attributesOriginal77427818d7088fcafb4732ee4ea7535a; ?>
<?php unset($__attributesOriginal77427818d7088fcafb4732ee4ea7535a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal77427818d7088fcafb4732ee4ea7535a)): ?>
<?php $component = $__componentOriginal77427818d7088fcafb4732ee4ea7535a; ?>
<?php unset($__componentOriginal77427818d7088fcafb4732ee4ea7535a); ?>
<?php endif; ?><?php if($hidden('skills')): ?><span class="text-xs font-bold text-amber-700">Privat</span><?php endif; ?> <?php if($portfolio['is_owner']): ?><a class="btn-secondary !min-h-9" href="<?php echo e(route('youth.skills.edit')); ?>">Kelola</a><?php endif; ?></div><div class="mt-4 flex flex-wrap gap-2"><?php $__empty_1 = true; $__currentLoopData = $portfolio['skills']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><span class="interest-chip"><?php echo e($skill); ?></span><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p class="text-sm text-slate-500">Belum ada keahlian.</p><?php endif; ?></div></section><?php endif; ?>

            <?php if($portfolio['is_owner']): ?><section class="sipora-card"><div class="flex items-center justify-between gap-3"><h2 class="text-xl font-black text-text-primary">Minat</h2><a class="btn-secondary !min-h-9" href="<?php echo e(route('youth.interests.edit')); ?>">Kelola</a></div><div class="mt-4 flex flex-wrap gap-2"><?php $__empty_1 = true; $__currentLoopData = $portfolio['interests']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $interest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><span class="interest-chip"><?php echo e($interest); ?></span><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p class="text-sm text-text-muted">Belum ada minat.</p><?php endif; ?></div></section><?php endif; ?>

            <?php if($portfolio['educations'] || $portfolio['is_owner']): ?><section class="sipora-card"><div class="flex flex-wrap items-center justify-between gap-2"><h2 class="text-xl font-black text-text-primary">Pendidikan</h2><?php if (isset($component)) { $__componentOriginal77427818d7088fcafb4732ee4ea7535a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal77427818d7088fcafb4732ee4ea7535a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portfolio.provenance-badge','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portfolio.provenance-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal77427818d7088fcafb4732ee4ea7535a)): ?>
<?php $attributes = $__attributesOriginal77427818d7088fcafb4732ee4ea7535a; ?>
<?php unset($__attributesOriginal77427818d7088fcafb4732ee4ea7535a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal77427818d7088fcafb4732ee4ea7535a)): ?>
<?php $component = $__componentOriginal77427818d7088fcafb4732ee4ea7535a; ?>
<?php unset($__componentOriginal77427818d7088fcafb4732ee4ea7535a); ?>
<?php endif; ?><?php if($hidden('education')): ?><span class="text-xs font-bold text-amber-700">Privat</span><?php endif; ?></div><div class="mt-4 space-y-4"><?php $__empty_1 = true; $__currentLoopData = $portfolio['educations']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $education): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><article class="border-l-2 border-indigo-200 pl-4"><h3 class="font-bold"><?php echo e($education['institution']); ?></h3><p class="text-sm text-slate-600"><?php echo e($education['level']); ?><?php echo e($education['field'] ? ' · '.$education['field'] : ''); ?></p><p class="mt-1 text-xs text-slate-500"><?php echo e($education['period']); ?></p></article><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p class="text-sm text-slate-500">Belum ada riwayat pendidikan.</p><?php endif; ?></div></section><?php endif; ?>

            <section class="rounded-2xl bg-primary p-5 text-white"><h2 class="font-black">Tentang validasi Portfolio</h2><p class="mt-2 text-sm leading-6 text-indigo-100">Label terverifikasi berasal dari catatan Community, Activity, dan sertifikat resmi SIPORA. Keahlian, pendidikan, pengalaman organisasi mandiri, dan sebagian prestasi diisi sendiri oleh pemilik.</p></section>
        </aside>
    </div>
</div>
<?php if($portfolio['is_owner']): ?>
    <?php ($demoPortfolioMedia = \App\Support\Demo\DemoAssetManifest::portfolio(auth()->user())); ?>
    <?php if($demoPortfolioMedia): ?>
        <section class="sipora-card mx-auto mt-8 max-w-7xl">
            <h2 class="text-xl font-bold text-text-primary">Media pendukung Portfolio</h2>
            <p class="mt-2 text-sm text-text-secondary">Data demo milik akun ini, belum diverifikasi. Media ini tidak membuat SIPORA Verified Record dan tidak ditampilkan pada Portfolio publik.</p>
            <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <?php $__currentLoopData = $demoPortfolioMedia; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <article class="rounded-xl border border-border p-3">
                        <a href="<?php echo e($media['url']); ?>" target="_blank" rel="noopener"><img src="<?php echo e($media['url']); ?>" alt="<?php echo e($media['title']); ?>" class="aspect-[4/3] w-full rounded-xl object-contain" loading="lazy"></a>
                        <h3 class="mt-3 font-bold"><?php echo e($media['title']); ?></h3>
                        <?php if($media['note']): ?><p class="mt-2 text-xs text-text-secondary"><?php echo e($media['note']); ?></p><?php endif; ?>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($portfolio['is_owner'] ? 'layouts.youth' : 'layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/portfolio/show.blade.php ENDPATH**/ ?>