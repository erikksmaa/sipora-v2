@props(['domain', 'identity' => '', 'label' => 'Ilustrasi representatif', 'compact' => false])
<figure {{ $attributes->class(['media-representative', 'media-representative-compact' => $compact]) }}>
    <img data-media-fallback src="{{ \App\Support\Media\RepresentativeMedia::url($domain, (string) $identity) }}" alt="{{ $label }}" loading="lazy" decoding="async" width="1200" height="675">
    @unless($compact)<figcaption>{{ \App\Support\Demo\DemoAssetManifest::publicAsset($domain, $identity, $domain === 'opportunities' ? 'card' : 'cover') ? 'Dokumentasi demo SIPORA' : 'Ilustrasi representatif' }}</figcaption>@endunless
</figure>

