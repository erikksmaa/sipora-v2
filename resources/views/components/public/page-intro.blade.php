@props(['eyebrow', 'title', 'description', 'icon' => 'sparkles', 'variant' => 'default', 'backHref' => null, 'backLabel' => null])
<section @class(['public-page-intro', 'public-page-intro--'.$variant])>
    <div class="relative mx-auto max-w-7xl">
        @if($backHref)
            <nav class="mb-5 flex items-center gap-2 text-sm text-white/85" aria-label="Breadcrumb"><a
                    class="inline-flex min-h-11 items-center gap-1 font-bold hover:text-white"
                    href="{{ $backHref }}"><x-ui.icon name="chevron-left" class="size-4" />{{ $backLabel }}</a><span
                    aria-hidden="true">/</span><span aria-current="page"
        class="max-w-72 truncate text-white/65">{{ $title }}</span></nav>@endif
        <div class="flex items-start gap-4"><span
                class="hidden size-12 shrink-0 place-items-center rounded-2xl border border-white/20 bg-white/10 text-accent-300 shadow-xs sm:grid"><x-ui.icon
                    :name="$icon" class="size-6" /></span>
            <div>
                <p class="font-display-accent text-sm font-bold uppercase tracking-[.08em] text-accent-300">{{ $eyebrow }}</p>
                <h1 class="font-heading mt-2 max-w-4xl text-4xl font-bold tracking-tight sm:text-5xl">{{ $title }}</h1>
                <p class="mt-3 max-w-3xl text-base leading-7 text-white/85 sm:text-lg">{{ $description }}</p>
            </div>
        </div>
    </div>
</section>
