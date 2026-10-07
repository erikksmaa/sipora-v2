@props(['community', 'context' => 'public'])
@php
    $public = $community->review_status === 'approved' && $community->operational_status === 'active';
    $url = $community->logo_path && ($public || $context === 'manager')
        ? route($context === 'manager' ? 'youth.communities.logo' : 'communities.logo', $community) : null;
@endphp
<span {{ $attributes->class(['media-community']) }}>
    @if($url)<img data-media-fallback data-media-initials="{{ mb_strtoupper(mb_substr($community->name, 0, 1)) }}" src="{{ $url }}" alt="Logo {{ $community->name }}" loading="lazy" decoding="async">
    @else<span aria-label="Logo belum tersedia">{{ mb_strtoupper(mb_substr($community->name, 0, 1)) }}</span>@endif
</span>

