@props(['eyebrow', 'title', 'description', 'icon' => 'sparkles', 'backHref' => null, 'backLabel' => null])
<section class="public-page-intro">
    <div class="relative mx-auto max-w-7xl">
        @if($backHref)<nav class="mb-5 flex items-center gap-2 text-sm text-indigo-200" aria-label="Breadcrumb"><a class="inline-flex min-h-11 items-center gap-1 font-bold hover:text-white" href="{{ $backHref }}"><x-ui.icon name="chevron-left" class="size-4" />{{ $backLabel }}</a><span aria-hidden="true">/</span><span aria-current="page" class="max-w-72 truncate text-white">{{ $title }}</span></nav>@endif
        <div class="flex items-start gap-4"><span class="hidden size-12 shrink-0 place-items-center rounded-2xl border border-white/15 bg-white/10 text-orange-300 sm:grid"><x-ui.icon :name="$icon" /></span><div><p class="text-xs font-extrabold uppercase tracking-[.18em] text-orange-300">{{ $eyebrow }}</p><h1 class="font-heading mt-2 max-w-4xl text-4xl font-bold tracking-tight sm:text-5xl">{{ $title }}</h1><p class="mt-3 max-w-3xl text-base leading-7 text-indigo-100 sm:text-lg">{{ $description }}</p></div></div>
    </div>
</section>
