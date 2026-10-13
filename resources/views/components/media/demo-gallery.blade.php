@props(['domain', 'identity'])
@php($photos = \App\Support\Demo\DemoAssetManifest::gallery($domain, $identity))
@if($photos)
<section class="public-panel mt-8">
    <h2 class="text-xl font-bold text-text-primary">Galeri dokumentasi</h2>
    <p class="mt-2 text-sm text-text-secondary">Dokumentasi fiktif untuk demo SIPORA.</p>
    <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($photos as $photo)
            <a href="{{ $photo }}" target="_blank" rel="noopener"><img src="{{ $photo }}" alt="Dokumentasi {{ $identity }}, foto {{ $loop->iteration }}" class="aspect-[4/3] w-full rounded-xl object-cover" loading="lazy"></a>
        @endforeach
    </div>
</section>
@endif
