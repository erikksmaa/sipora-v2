@props(['title', 'value', 'icon' => 'layout-dashboard', 'tone' => 'primary', 'href' => null, 'action' => null])
@php($tones = match ($tone) { 'accent' => ['bg-orange-50 text-orange-700', 'border-orange-100'], 'success' => ['bg-emerald-50 text-emerald-700', 'border-emerald-100'], 'info' => ['bg-blue-50 text-blue-700', 'border-blue-100'], default => ['bg-indigo-50 text-primary', 'border-indigo-100'] })
@php($tag = $href ? 'a' : 'article')
<{{ $tag }} @if($href) href="{{ $href }}" @endif {{ $attributes->class(['group surface-card p-5', 'surface-card-hover' => $href, $tones[1]]) }}>
    <div class="flex items-start justify-between gap-4"><div><p class="text-xs font-extrabold uppercase tracking-[0.08em] text-slate-500">{{ $title }}</p><p class="font-heading mt-3 text-4xl font-bold tracking-tight text-primary-dark">{{ $value }}</p></div><span class="grid size-11 place-items-center rounded-xl {{ $tones[0] }}"><x-ui.icon :name="$icon" /></span></div>
    @if($action)<p class="mt-4 flex items-center gap-1 text-xs font-bold text-interactive">{{ $action }} <x-ui.icon name="chevron-right" class="size-3.5" /></p>@endif
</{{ $tag }}>
