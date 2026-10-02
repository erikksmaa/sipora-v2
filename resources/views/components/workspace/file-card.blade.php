@props(['title', 'description' => null, 'href', 'type' => null, 'private' => true, 'icon' => 'file-text'])
<article {{ $attributes->class(['flex flex-col gap-4 rounded-2xl border border-border bg-surface-soft p-5 sm:flex-row sm:items-center']) }}>
    <span class="grid size-12 shrink-0 place-items-center rounded-xl bg-white text-primary shadow-xs"><x-ui.icon :name="$icon" class="size-6" /></span>
    <div class="min-w-0 flex-1"><div class="flex flex-wrap items-center gap-2"><h3 class="font-bold text-text-primary">{{ $title }}</h3>@if($private)<x-ui.badge tone="warning" icon="shield-check">Privat</x-ui.badge>@endif</div>@if($description)<p class="mt-1 text-sm text-text-secondary">{{ $description }}</p>@endif @if($type)<p class="mt-1 text-xs font-bold uppercase text-text-muted">{{ $type }}</p>@endif</div>
    <a class="btn-secondary shrink-0" href="{{ $href }}" target="_blank" rel="noopener">Buka dokumen</a>
</article>
