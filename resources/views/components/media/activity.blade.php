@props(['activity', 'context' => 'public'])
@php
    $public = $activity->review_status === 'approved' && $activity->publication_status === 'published'
        && $activity->organization?->review_status === 'approved' && $activity->organization?->operational_status === 'active';
    $url = null;
    if ($activity->poster_path && ($public || in_array($context, ['manager', 'verifier'], true))) {
        $url = match ($context) {
            'manager' => route('manager.activities.poster', [$activity->organization, $activity]),
            'verifier' => route('verifier.activity-verifications.poster', $activity),
            default => route('activities.poster', $activity),
        };
    }
@endphp
<div {{ $attributes->class(['media-activity']) }}>
    @if($url)<img data-media-fallback src="{{ $url }}" alt="Poster {{ $activity->title }}" loading="lazy" decoding="async" width="1200" height="675">
    @else<x-media.representative domain="activities" :identity="$activity->slug" compact label="Ilustrasi kegiatan kepemudaan" />@endif
</div>

