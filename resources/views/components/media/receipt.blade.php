@props(['url', 'image' => false])
<div class="media-evidence">
    @if($image)
        <a href="{{ $url }}" target="_blank" rel="noopener">
            <img data-media-fallback src="{{ $url }}" alt="Pratinjau bukti transaksi — akses terbatas" loading="lazy" decoding="async">
        </a>
    @else
        <span class="media-document" aria-hidden="true"><x-ui.icon name="notebook" /></span>
    @endif
    <a href="{{ $url }}" target="_blank" rel="noopener" class="public-link">Buka bukti transaksi</a>
    <small>Dokumen privat · hanya untuk pemeriksaan berizin</small>
</div>

