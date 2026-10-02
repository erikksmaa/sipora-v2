@props(['title', 'value', 'icon' => 'layout-dashboard', 'tone' => 'primary', 'href' => null, 'action' => null])
@php($tones = match ($tone) {
    'accent' => ['bg-accent-50 text-accent-700', 'border-orange-100'],
    'success' => ['bg-success-soft text-success', 'border-emerald-100'],
    'info' => ['bg-info-soft text-info', 'border-sky-100'],
    default => ['bg-primary-50 text-primary', 'border-primary-100']
})
@php($tag = $href ? 'a' : 'article')
<{{ $tag }} @if($href) href="{{ $href }}" @endif {{ $attributes->class(['group surface-card p-5', 'surface-card-hover' => $href, $tones[1]]) }}>
    <div class="flex items-start justify-between gap-4"><div><p class="text-xs font-bold uppercase tracking-[0.08em] text-text-muted">{{ $title }}</p><p class="font-heading mt-3 text-4xl font-bold tracking-tight text-text-primary">{{ $value }}</p></div><span class="grid size-11 place-items-center rounded-xl {{ $tones[0] }}"><x-ui.icon :name="$icon" class="size-5" /></span></div>
    @if($action)<p class="mt-4 flex items-center gap-1 text-xs font-bold text-primary transition-colors group-hover:text-accent">{{ $action }} <x-ui.icon name="chevron-right" class="size-3.5" /></p>@endif
</{{ $tag }}>
