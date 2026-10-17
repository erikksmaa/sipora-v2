@props(['size' => 'size-12'])
<span {{ $attributes->class(['brand-logo', 'relative block shrink-0', $size]) }}>
    <img src="{{ asset('sipora-growth-mark-v1.png') }}" alt="Logo SIPORA" class="absolute inset-0 block h-full w-full object-contain" loading="eager">
</span>
