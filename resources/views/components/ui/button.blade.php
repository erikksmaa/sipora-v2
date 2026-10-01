@props(['href' => null, 'variant' => 'primary', 'type' => 'button', 'icon' => null])
@php($classes = match ($variant) { 'secondary' => 'btn-secondary', 'outline' => 'btn-outline', 'ghost' => 'btn-ghost', 'danger' => 'btn-danger', default => 'btn-primary' })
@if($href)
    <a href="{{ $href }}" {{ $attributes->class([$classes]) }}>@if($icon)<x-ui.icon :name="$icon" />@endif<span>{{ $slot }}</span></a>
@else
    <button type="{{ $type }}" {{ $attributes->class([$classes]) }}>@if($icon)<x-ui.icon :name="$icon" />@endif<span>{{ $slot }}</span></button>
@endif
