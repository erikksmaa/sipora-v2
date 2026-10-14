@props(['tone' => 'neutral', 'icon' => null])
@php($classes = match ($tone) {
    'success', 'green' => 'border-success/30 bg-success-soft text-success',
    'warning', 'orange' => 'border-warning/30 bg-warning-soft text-warning',
    'danger', 'red' => 'border-danger/30 bg-danger-soft text-danger',
    'info', 'blue' => 'border-info/30 bg-info-soft text-info',
    'primary', 'indigo', 'navy' => 'border-border-strong bg-surface-soft text-text-primary',
    default => 'border-border bg-surface-soft text-text-secondary'
})
<span {{ $attributes->class(['status-badge', $classes]) }}>@if($icon)<x-ui.icon :name="$icon" class="size-3.5" />@endif<span>{{ $slot }}</span></span>
