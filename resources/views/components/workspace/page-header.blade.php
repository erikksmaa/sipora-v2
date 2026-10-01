@props(['eyebrow' => null, 'title', 'description' => null])
<header {{ $attributes->class(['flex flex-col justify-between gap-4 border-b border-indigo-100 pb-5 sm:flex-row sm:items-end']) }}>
    <div class="min-w-0">
        @if($eyebrow)<p class="text-xs font-extrabold uppercase tracking-[.16em] text-interactive">{{ $eyebrow }}</p>@endif
        <h1 class="font-heading mt-1 text-3xl font-bold tracking-tight text-text-primary">{{ $title }}</h1>
        @if($description)<p class="mt-2 max-w-3xl text-sm leading-6 text-text-secondary">{{ $description }}</p>@endif
    </div>
    @if(trim((string) $slot))<div class="flex shrink-0 flex-wrap gap-2">{{ $slot }}</div>@endif
</header>
