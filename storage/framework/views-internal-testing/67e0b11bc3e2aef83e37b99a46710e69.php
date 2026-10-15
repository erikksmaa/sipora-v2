<header x-data="publicNavigation" @keydown.escape.window="open ? closeMobile(true) : closeExplore(true)"
    class="sticky top-0 z-50 border-b border-border bg-white/95 backdrop-blur-xl">
    <nav class="mx-auto flex h-[74px] max-w-7xl items-center justify-between gap-6 px-5" aria-label="Navigasi publik">
        <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-3" aria-label="SIPORA beranda">
            <?php if (isset($component)) { $__componentOriginal8741a05e11b0c77d19ec61b6b35b26b3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8741a05e11b0c77d19ec61b6b35b26b3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.brand-logo','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('brand-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8741a05e11b0c77d19ec61b6b35b26b3)): ?>
<?php $attributes = $__attributesOriginal8741a05e11b0c77d19ec61b6b35b26b3; ?>
<?php unset($__attributesOriginal8741a05e11b0c77d19ec61b6b35b26b3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8741a05e11b0c77d19ec61b6b35b26b3)): ?>
<?php $component = $__componentOriginal8741a05e11b0c77d19ec61b6b35b26b3; ?>
<?php unset($__componentOriginal8741a05e11b0c77d19ec61b6b35b26b3); ?>
<?php endif; ?>
            <span><strong class="block text-xl leading-none tracking-tight text-primary">SIPORA</strong><small
                    class="text-[10px] font-semibold uppercase tracking-[0.13em] text-text-muted">Pemuda
                    Pemalang</small></span>
        </a>
        <div class="hidden h-full items-center gap-6 xl:flex">
            <a href="<?php echo e(route('home')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['public-nav-link h-full', 'public-nav-active' => request()->routeIs('home')]); ?>" <?php if(request()->routeIs('home')): ?> aria-current="page" <?php endif; ?>>Beranda</a>
            <div class="relative flex h-full items-center" @click.outside="exploreOpen = false">
                <button x-ref="exploreTrigger" type="button" @click="exploreOpen = !exploreOpen"
                    @keydown.arrow-down.prevent="openExploreAndFocus" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['public-nav-link h-full gap-1', 'public-nav-active' => request()->routeIs('activities.*', 'communities.*', 'opportunities.*', 'programs.*', 'youth-directory.*', 'portfolio.show', 'ecosystem-map.*', 'social.*')]); ?>" :aria-expanded="exploreOpen"
                    aria-haspopup="menu" aria-controls="explore-menu">Jelajahi <?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'chevron-down','class' => 'size-4 transition','xBind:class' => 'exploreOpen && \'rotate-180\'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-down','class' => 'size-4 transition','x-bind:class' => 'exploreOpen && \'rotate-180\'']); ?>
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
                <div x-ref="exploreMenu" id="explore-menu" x-cloak x-show="exploreOpen" x-transition role="menu"
                    class="absolute left-1/2 top-[calc(100%-4px)] w-64 -translate-x-1/2 rounded-2xl border border-border bg-white p-2 shadow-lg">
                    <?php $__currentLoopData = [['Social Feed', 'Update terbaru ekosistem', 'users', 'social.index'], ['Activity', 'Temukan kegiatan pemuda', 'activity', 'activities.index'], ['Community', 'Bertumbuh bersama komunitas', 'building', 'communities.index'], ['Opportunity', 'Jelajahi peluang terkurasi', 'briefcase', 'opportunities.index'], ['Program', 'Lihat program kepemudaan', 'notebook', 'programs.index'], ['Pemuda', 'Lihat Portfolio yang dibagikan', 'users', 'youth-directory.index'], ['Peta Ekosistem', 'Cakupan per kecamatan', 'globe', 'ecosystem-map.index']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $description, $icon, $routeName]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><a
                        href="<?php echo e(route($routeName)); ?>" @click="exploreOpen = false" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['flex min-h-12 items-center gap-3 rounded-xl px-3 py-2.5 transition hover:bg-primary-50', 'bg-primary-50' => request()->routeIs($routeName, str($routeName)->before('.index') . '.show') || ($routeName === 'youth-directory.index' && request()->routeIs('portfolio.show'))]); ?>"><span
                            class="grid size-9 place-items-center rounded-lg bg-surface-soft text-primary shadow-xs"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => $icon,'class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($icon),'class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></span><span><strong
                                class="block text-sm text-text-primary"><?php echo e($label); ?></strong><small
                    class="text-xs text-text-muted"><?php echo e($description); ?></small></span></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <a href="<?php echo e(route('forum.index')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['public-nav-link h-full', 'public-nav-active' => request()->routeIs('forum.*')]); ?>" <?php if(request()->routeIs('forum.*')): ?> aria-current="page" <?php endif; ?>>Forum</a>
            <a href="<?php echo e(route('statistics.index')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['public-nav-link h-full', 'public-nav-active' => request()->routeIs('statistics.index')]); ?>" <?php if(request()->routeIs('statistics.index')): ?> aria-current="page" <?php endif; ?>>Statistik Pemuda</a>
            <a href="<?php echo e(route('about')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['public-nav-link h-full', 'public-nav-active' => request()->routeIs('about')]); ?>" <?php if(request()->routeIs('about')): ?> aria-current="page" <?php endif; ?>>Tentang</a>
            <a href="<?php echo e(route('contact')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['public-nav-link h-full', 'public-nav-active' => request()->routeIs('contact')]); ?>" <?php if(request()->routeIs('contact')): ?> aria-current="page" <?php endif; ?>>Kontak</a>
            <a href="<?php echo e(route('search.index')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['grid size-10 place-items-center rounded-[10px] text-text-secondary transition hover:bg-primary-50 hover:text-primary', 'bg-primary-50 text-primary' => request()->routeIs('search.index')]); ?>" aria-label="Pencarian" title="Pencarian"
                <?php if(request()->routeIs('search.index')): ?> aria-current="page" <?php endif; ?>><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'search','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'search','class' => 'size-4']); ?>
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
        </div>
        <div class="flex items-center gap-2 sm:gap-3">
            <?php if(auth()->guard()->check()): ?>
                <?php if (isset($component)) { $__componentOriginale5ab1a1e4d63e1839cb092c6e5714a22 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale5ab1a1e4d63e1839cb092c6e5714a22 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.notification-indicator','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('notification-indicator'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale5ab1a1e4d63e1839cb092c6e5714a22)): ?>
<?php $attributes = $__attributesOriginale5ab1a1e4d63e1839cb092c6e5714a22; ?>
<?php unset($__attributesOriginale5ab1a1e4d63e1839cb092c6e5714a22); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale5ab1a1e4d63e1839cb092c6e5714a22)): ?>
<?php $component = $__componentOriginale5ab1a1e4d63e1839cb092c6e5714a22; ?>
<?php unset($__componentOriginale5ab1a1e4d63e1839cb092c6e5714a22); ?>
<?php endif; ?>
                <a href="<?php echo e(route(\App\Support\Auth\HomeRoute::for(auth()->user()))); ?>"
                    class="btn-secondary hidden sm:inline-flex">Ruang Saya</a>
                <a href="<?php echo e(auth()->user()->hasRole('youth') ? route('youth.account.show') : route(\App\Support\Auth\HomeRoute::for(auth()->user()))); ?>" class="hidden size-10 place-items-center rounded-sm border border-border bg-surface text-text-primary sm:grid" aria-label="Akun saya"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'user','class' => 'size-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'user','class' => 'size-5']); ?>
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
                    <?php if (isset($component)) { $__componentOriginal2090438866f3dcdb76cd8b070bcc302d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2090438866f3dcdb76cd8b070bcc302d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.theme-toggle','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('theme-toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2090438866f3dcdb76cd8b070bcc302d)): ?>
<?php $attributes = $__attributesOriginal2090438866f3dcdb76cd8b070bcc302d; ?>
<?php unset($__attributesOriginal2090438866f3dcdb76cd8b070bcc302d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2090438866f3dcdb76cd8b070bcc302d)): ?>
<?php $component = $__componentOriginal2090438866f3dcdb76cd8b070bcc302d; ?>
<?php unset($__componentOriginal2090438866f3dcdb76cd8b070bcc302d); ?>
<?php endif; ?><button x-ref="mobileTrigger"
                type="button"
                class="grid size-11 place-items-center rounded-[10px] border border-border bg-white text-primary shadow-xs xl:hidden"
                @click="open ? closeMobile(true) : openMobile()" :aria-expanded="open" aria-controls="public-menu"
                aria-label="Buka menu"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'menu']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'menu']); ?>
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
            <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="btn-outline hidden !min-h-10 !px-5 sm:inline-flex">Masuk</a><a
                    href="<?php echo e(route('register')); ?>" class="landing-btn-primary hidden !min-h-10 !px-5 sm:inline-flex">Daftar</a>
                    <?php if (isset($component)) { $__componentOriginal2090438866f3dcdb76cd8b070bcc302d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2090438866f3dcdb76cd8b070bcc302d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.theme-toggle','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('theme-toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2090438866f3dcdb76cd8b070bcc302d)): ?>
<?php $attributes = $__attributesOriginal2090438866f3dcdb76cd8b070bcc302d; ?>
<?php unset($__attributesOriginal2090438866f3dcdb76cd8b070bcc302d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2090438866f3dcdb76cd8b070bcc302d)): ?>
<?php $component = $__componentOriginal2090438866f3dcdb76cd8b070bcc302d; ?>
<?php unset($__componentOriginal2090438866f3dcdb76cd8b070bcc302d); ?>
<?php endif; ?><button x-ref="mobileTrigger"
                type="button"
                class="grid size-11 place-items-center rounded-[10px] border border-border bg-white text-primary shadow-xs xl:hidden"
                @click="open ? closeMobile(true) : openMobile()" :aria-expanded="open" aria-controls="public-menu"
                aria-label="Buka menu"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'menu']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'menu']); ?>
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
            <?php endif; ?>
        </div>

    </nav>
    <div x-ref="mobileMenu" id="public-menu" x-cloak x-show="open" x-transition @keydown.tab="trapMobileFocus($event)"
        class="max-h-[calc(100dvh-74px)] overflow-y-auto border-t border-border bg-white px-5 py-5 xl:hidden">
        <div class="mx-auto grid max-w-7xl gap-1 text-sm font-semibold">
            <div class="mb-2 flex items-center justify-between px-3"><span
                    class="text-xs font-bold uppercase tracking-wider text-text-muted">Navigasi</span><button
                    x-ref="mobileClose" type="button"
                    class="grid size-10 place-items-center rounded-lg text-text-secondary hover:bg-primary-50 hover:text-primary"
                    @click="closeMobile(true)" aria-label="Tutup menu"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'x']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'x']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?></button></div>
            <div class="mb-3 flex gap-3 border-b border-border pb-4"><?php if(auth()->guard()->check()): ?><a
                href="<?php echo e(route(\App\Support\Auth\HomeRoute::for(auth()->user()))); ?>"
            class="landing-btn-primary flex-1">Ruang Saya</a><?php else: ?><a href="<?php echo e(route('login')); ?>"
                        class="btn-secondary flex-1">Masuk</a><a href="<?php echo e(route('register')); ?>"
                    class="landing-btn-primary flex-1">Daftar</a><?php endif; ?></div>
            <a href="<?php echo e(route('home')); ?>" @click="open = false" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['flex min-h-11 items-center rounded-lg px-3 py-2.5 hover:bg-primary-50 hover:text-primary', 'bg-primary-50 text-primary' => request()->routeIs('home')]); ?>">Beranda</a>
            <p class="px-3 pt-3 text-[10px] font-extrabold uppercase tracking-[.16em] text-text-muted">Jelajahi</p>
            <?php $__currentLoopData = ['Social Feed' => route('social.index'), 'Activity' => route('activities.index'), 'Community' => route('communities.index'), 'Opportunity' => route('opportunities.index'), 'Program' => route('programs.index'), 'Pemuda' => route('youth-directory.index'), 'Peta Ekosistem' => route('ecosystem-map.index')]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $href): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><a
                href="<?php echo e($href); ?>" @click="open = false"
            class="flex min-h-11 items-center rounded-lg px-3 py-2.5 hover:bg-primary-50 hover:text-primary"><?php echo e($label); ?></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('statistics.index')); ?>" @click="open = false" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['flex min-h-11 items-center rounded-lg px-3 py-2.5 hover:bg-primary-50 hover:text-primary', 'bg-primary-50 text-primary' => request()->routeIs('statistics.index')]); ?>">Statistik Pemuda</a>
            <a href="<?php echo e(route('forum.index')); ?>" @click="open = false" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['flex min-h-11 items-center rounded-lg px-3 py-2.5 hover:bg-primary-50 hover:text-primary', 'bg-primary-50 text-primary' => request()->routeIs('forum.*')]); ?>">Forum</a>
            <a href="<?php echo e(route('about')); ?>" @click="open = false" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['flex min-h-11 items-center rounded-lg px-3 py-2.5 hover:bg-primary-50 hover:text-primary', 'bg-primary-50 text-primary' => request()->routeIs('about')]); ?>">Tentang SIPORA</a>
            <a href="<?php echo e(route('contact')); ?>" @click="open = false" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['flex min-h-11 items-center rounded-lg px-3 py-2.5 hover:bg-primary-50 hover:text-primary', 'bg-primary-50 text-primary' => request()->routeIs('contact')]); ?>">Kontak</a>
            <a href="<?php echo e(route('search.index')); ?>" @click="open = false"
                class="flex min-h-11 items-center gap-2 rounded-lg px-3 py-2.5 hover:bg-primary-50 hover:text-primary"><?php if (isset($component)) { $__componentOriginal56804098dcf376a0e2227cb77b6cd00a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.icon','data' => ['name' => 'search','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'search','class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $attributes = $__attributesOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__attributesOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a)): ?>
<?php $component = $__componentOriginal56804098dcf376a0e2227cb77b6cd00a; ?>
<?php unset($__componentOriginal56804098dcf376a0e2227cb77b6cd00a); ?>
<?php endif; ?>Pencarian</a>
            <?php if(auth()->guard()->check()): ?><div class="flex flex-wrap gap-3 border-t border-border pt-4"><?php if (isset($component)) { $__componentOriginale5ab1a1e4d63e1839cb092c6e5714a22 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale5ab1a1e4d63e1839cb092c6e5714a22 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.notification-indicator','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('notification-indicator'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale5ab1a1e4d63e1839cb092c6e5714a22)): ?>
<?php $attributes = $__attributesOriginale5ab1a1e4d63e1839cb092c6e5714a22; ?>
<?php unset($__attributesOriginale5ab1a1e4d63e1839cb092c6e5714a22); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale5ab1a1e4d63e1839cb092c6e5714a22)): ?>
<?php $component = $__componentOriginale5ab1a1e4d63e1839cb092c6e5714a22; ?>
<?php unset($__componentOriginale5ab1a1e4d63e1839cb092c6e5714a22); ?>
<?php endif; ?><a href="<?php echo e(auth()->user()->hasRole('youth') ? route('youth.account.show') : route(\App\Support\Auth\HomeRoute::for(auth()->user()))); ?>" class="btn-secondary flex-1">Akun saya</a></div><?php endif; ?>

        </div>
    </div>
</header>
<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/components/public/navbar.blade.php ENDPATH**/ ?>