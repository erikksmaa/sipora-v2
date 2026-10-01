@props(['tone' => 'neutral', 'icon' => null])
@php($classes = match ($tone) { 'success', 'green' => 'border-emerald-200 bg-emerald-50 text-emerald-800', 'warning', 'orange' => 'border-orange-200 bg-orange-50 text-orange-800', 'danger' => 'border-red-200 bg-red-50 text-red-800', 'info', 'blue' => 'border-blue-200 bg-blue-50 text-blue-800', default => 'border-slate-200 bg-slate-100 text-slate-700' })
<span {{ $attributes->class(['status-badge', $classes]) }}>@if($icon)<x-ui.icon :name="$icon" class="size-3.5" />@endif<span>{{ $slot }}</span></span>
