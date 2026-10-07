@props(['domain', 'identity' => '', 'label' => 'Ilustrasi representatif', 'compact' => false])
<figure {{ $attributes->class(['media-representative', 'media-representative-compact' => $compact]) }}>
    <img data-media-fallback src="{{ \App\Support\Media\RepresentativeMedia::url($domain, (string) $identity) }}" alt="{{ $label }}" loading="lazy" decoding="async" width="1200" height="675">
    @unless($compact)<figcaption>Ilustrasi representatif</figcaption>@endunless
</figure>

