@props(['tone' => 'neutral', 'icon' => null])
@php($classes = match ($tone) {
    'success', 'green' => 'border-emerald-200 bg-[#E9F7F0] text-[#16865C]',
    'warning', 'orange' => 'border-orange-200 bg-[#FFF6DF] text-[#C2410C]',
    'danger', 'red' => 'border-red-200 bg-[#FDECEC] text-[#D64545]',
    'info', 'blue' => 'border-sky-200 bg-[#EAF3FB] text-[#3B82C4]',
    'primary', 'indigo', 'navy' => 'border-primary-200 bg-[#F3F5FC] text-[#33427F]',
    default => 'border-border bg-surface-soft text-text-secondary'
})
<span {{ $attributes->class(['status-badge', $classes]) }}>@if($icon)<x-ui.icon :name="$icon" class="size-3.5" />@endif<span>{{ $slot }}</span></span>
