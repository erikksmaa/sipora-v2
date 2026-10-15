
<?php $__env->startSection('title', 'Kontak · SIPORA'); ?>
<?php $__env->startSection('meta_description', 'Informasi resmi pengelola platform SIPORA Kabupaten Pemalang.'); ?>
<?php $__env->startSection('canonical', route('contact')); ?>
<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginal780d11c2fd95c33fc53cd02cfb936d73 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal780d11c2fd95c33fc53cd02cfb936d73 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.page-intro','data' => ['eyebrow' => 'Informasi Resmi','title' => 'Kontak SIPORA','description' => 'Informasi pengelola platform dan kanal layanan resmi yang telah dikonfigurasi.','variant' => 'contact']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.page-intro'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Informasi Resmi','title' => 'Kontak SIPORA','description' => 'Informasi pengelola platform dan kanal layanan resmi yang telah dikonfigurasi.','variant' => 'contact']); ?>
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
<section class="bg-surface-soft px-5 py-16">
    <div class="mx-auto max-w-6xl">
        <div class="grid gap-8 lg:grid-cols-[.8fr_1.2fr]">
            <article class="rounded-2xl bg-primary-900 p-7 text-white shadow-md"><span
                    class="grid size-12 place-items-center rounded-xl bg-white/10 text-accent-300"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'building']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'building']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></span><?php if (isset($component)) { $__componentOriginal6936dc520652c52aa45e41e68a9b74a8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6936dc520652c52aa45e41e68a9b74a8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.media.representative','data' => ['class' => 'mt-5','domain' => 'contact','identity' => 'service','label' => 'Ilustrasi layanan informasi, bukan foto kantor atau peta lokasi']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('media.representative'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-5','domain' => 'contact','identity' => 'service','label' => 'Ilustrasi layanan informasi, bukan foto kantor atau peta lokasi']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6936dc520652c52aa45e41e68a9b74a8)): ?>
<?php $attributes = $__attributesOriginal6936dc520652c52aa45e41e68a9b74a8; ?>
<?php unset($__attributesOriginal6936dc520652c52aa45e41e68a9b74a8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6936dc520652c52aa45e41e68a9b74a8)): ?>
<?php $component = $__componentOriginal6936dc520652c52aa45e41e68a9b74a8; ?>
<?php unset($__componentOriginal6936dc520652c52aa45e41e68a9b74a8); ?>
<?php endif; ?>
                <p class="mt-6 text-xs font-bold uppercase tracking-wider text-accent-300">Pengelola Platform</p>
                <h2 class="font-heading text-white mt-2 text-3xl font-bold"><?php echo e($contact['organization']); ?></h2>
                <p class="mt-4 text-sm leading-7 text-white/75">SIPORA dikelola sebagai platform ekosistem olahraga dan
                    kepemudaan Kabupaten Pemalang.</p>
            </article>
            <div>
                <h2 class="font-heading text-3xl font-bold text-text-primary">Kanal informasi resmi</h2>
                <p class="mt-3 text-text-secondary">Gunakan kanal yang tersedia di bawah. Halaman ini tidak mengirim
                    tiket atau pesan otomatis.</p>
                <div class="mt-7 grid gap-4 sm:grid-cols-2">
                    <?php ($methods = [['map-pin', 'Alamat', $contact['address'], null], ['mail', 'Email', $contact['email'], $contact['email'] ? 'mailto:' . $contact['email'] : null], ['phone', 'Telepon', $contact['phone'], $contact['phone'] ? 'tel:' . preg_replace('/[^+0-9]/', '', $contact['phone']) : null], ['globe', 'Website', $contact['website'], $contact['website']]]); ?>
                    <?php $__currentLoopData = $methods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$icon, $label, $value, $href]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <article class="rounded-2xl border border-border bg-white p-5 shadow-xs"><span
                                class="grid size-10 place-items-center rounded-xl bg-primary-50 text-primary"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
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
<?php endif; ?></span>
                            <h3 class="mt-4 text-sm font-bold text-text-primary"><?php echo e($label); ?></h3><?php if($value): ?> <?php if($href): ?><a
                                    class="mt-1 block break-words text-sm text-primary hover:underline" href="<?php echo e($href); ?>"
                                <?php if($label === 'Website'): ?> rel="noopener" <?php endif; ?>><?php echo e($value); ?></a><?php else: ?><p
                            class="mt-1 text-sm leading-6 text-text-secondary"><?php echo e($value); ?></p><?php endif; ?> <?php else: ?><p
                                class="mt-1 text-sm text-text-muted">Belum dikonfigurasi.</p><?php endif; ?>
                    </article><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div><?php if($contact['service_hours']): ?>
                    <article class="mt-4 rounded-2xl border border-border bg-white p-5 shadow-xs">
                        <h3 class="font-bold text-text-primary">Jam layanan</h3>
                        <p class="mt-2 text-sm text-text-secondary"><?php echo e($contact['service_hours']); ?></p>
                </article><?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php if($contact['maps_embed_url'] ?? null): ?>
<section class="px-5 py-10" aria-labelledby="contact-location">
    <div class="sipora-card mx-auto max-w-6xl p-5 sm:p-7">
        <div class="mb-5 flex flex-wrap items-center justify-between gap-4">
            <div><h2 id="contact-location" class="text-2xl font-bold text-text-primary">Lokasi layanan</h2><p class="mt-2 text-sm text-text-secondary">Dinas Pendidikan dan Kebudayaan Kabupaten Pemalang.</p></div>
            <?php if($contact['maps_url'] ?? null): ?><a class="btn-outline" href="<?php echo e($contact['maps_url']); ?>" target="_blank" rel="noopener noreferrer">Lihat di Google Maps <?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'external-link','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'external-link','class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></a><?php endif; ?>
        </div>
        <iframe class="h-72 w-full border border-border sm:h-96" src="<?php echo e($contact['maps_embed_url']); ?>" title="Lokasi Dinas Pendidikan dan Kebudayaan Kabupaten Pemalang di Google Maps" allowfullscreen loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
    </div>
</section>
<?php endif; ?>
<section class="px-5 py-14">
    <div
        class="mx-auto flex max-w-6xl flex-col justify-between gap-5 rounded-2xl border border-border bg-white p-7 shadow-xs sm:flex-row sm:items-center">
        <div>
            <h2 class="font-heading text-2xl font-bold text-text-primary">Mencari informasi ekosistem?</h2>
            <p class="mt-2 text-sm text-text-secondary">Gunakan halaman Jelajahi untuk menemukan data publik yang sudah
                tersedia.</p>
        </div><a class="landing-btn-primary" href="<?php echo e(route('search.index')); ?>">Buka Pencarian</a>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/public/contact.blade.php ENDPATH**/ ?>