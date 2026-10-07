@props(['size' => 'size-12'])
<span {{ $attributes->class(['brand-logo', 'relative block shrink-0', $size]) }}>
    <img src="{{ asset('logo.png') }}" alt="Lambang Kabupaten Pemalang" class="absolute inset-0 block h-full w-full object-contain" loading="eager">
</span>
