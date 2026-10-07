@extends($isAdmin ? 'layouts.admin' : 'layouts.public')
@section('title', 'Peta Ekosistem Pemuda · SIPORA')
@unless($isAdmin)
    @section('meta_description', 'Peta agregat Youth, Community, Activity, dan Program per kecamatan di SIPORA.')
    @section('canonical', route('ecosystem-map.index'))
@endunless
@push('head')
    @vite(['resources/css/ecosystem-map.css', 'resources/js/ecosystem-map.js'])
@endpush
@section('content')
@php($display = fn (?int $count): string => $count === null ? '<'.$threshold : number_format($count))
@if($isAdmin)
    <x-ui.page-header eyebrow="Geographic Insight" title="Peta Ekosistem Pemuda" description="Sebaran agregat berdasarkan kecamatan yang tercatat di SIPORA."><x-slot:actions><a class="btn-secondary" href="{{ route('admin.dashboard') }}">Kembali ke Dashboard</a></x-slot:actions></x-ui.page-header>
@else
    <x-public.page-intro eyebrow="Data ekosistem" title="Peta Ekosistem Pemuda" description="Berdasarkan pemuda yang terdaftar di SIPORA; bukan peta seluruh populasi pemuda Kabupaten Pemalang." icon="globe" variant="youth" />
@endif
<div class="eco-map-page {{ $isAdmin ? 'eco-map-page-admin' : '' }}">
    <div class="eco-map-container">
        <div class="eco-map-intro"><div><p class="eco-map-eyebrow">Cakupan kecamatan</p><h2>Jelajahi ekosistem SIPORA per wilayah</h2><p>Empat lapisan berasal dari data aktual: domisili Youth, lokasi Community aktif, lokasi Activity terbit, dan kecamatan Community penyelenggara Program publik.</p></div><a class="btn-secondary" href="{{ route('statistics.index') }}">Lihat Statistik Pemuda</a></div>
        <section class="eco-map-panel" aria-labelledby="eco-map-title">
            <div class="eco-map-toolbar"><fieldset class="eco-map-layers"><legend id="eco-map-title">Pilih lapisan peta</legend><div class="eco-map-layer-options">@foreach(['youth' => 'Youth', 'community' => 'Community', 'activity' => 'Activity', 'program' => 'Program'] as $key => $label)<label><input type="radio" name="map-layer" value="{{ $key }}" @checked($key === 'youth')><span>{{ $label }}</span></label>@endforeach</div></fieldset><label class="eco-map-district-picker">Kecamatan<select id="eco-map-district"><option value="">Semua kecamatan</option>@foreach($coverage as $row)<option value="{{ $row['code'] }}">{{ $row['district'] }}</option>@endforeach</select></label></div>
            <div class="eco-map-layout"><div class="eco-map-canvas-wrap"><div id="eco-map" class="eco-map-canvas" data-geometry-url="{{ asset('data/pemalang-kecamatan.geojson') }}" role="region" aria-label="Peta interaktif 14 kecamatan Kabupaten Pemalang"></div><p id="eco-map-error" class="eco-map-error" role="status" hidden>Peta belum dapat dimuat. Tabel kecamatan di bawah tetap tersedia.</p><div class="eco-map-legend"><span><i class="eco-map-swatch eco-map-swatch-zero"></i>0</span>@unless($isAdmin)<span><i class="eco-map-swatch eco-map-swatch-hidden"></i>&lt;{{ $threshold }}</span>@endunless<span><i class="eco-map-swatch eco-map-swatch-low"></i>Lebih sedikit</span><span><i class="eco-map-swatch eco-map-swatch-high"></i>Lebih banyak</span></div></div><aside class="eco-map-detail" aria-live="polite"><p class="eco-map-eyebrow">Rincian wilayah</p><h3 id="eco-map-selected-title">Semua kecamatan</h3><p id="eco-map-selected-copy">Pilih wilayah di peta atau daftar untuk melihat angka agregat.</p><dl id="eco-map-selected-values" class="eco-map-selected-values"></dl><button type="button" id="eco-map-reset" class="btn-secondary" hidden>Tampilkan semua</button></aside></div>
        </section>
        <section class="eco-map-panel" aria-labelledby="eco-map-table-title">
            <div class="eco-map-table-heading"><div><p class="eco-map-eyebrow">Data pembanding</p><h2 id="eco-map-table-title">Cakupan menurut kecamatan</h2></div><p>Angka Youth merupakan akun unik. Community, Activity, dan Program merupakan jumlah record sesuai status publiknya.</p></div>
            <div class="eco-map-table-wrap"><table class="eco-map-table">
                <thead><tr><th scope="col">Kecamatan</th><th scope="col">Youth</th>
                    @if($isAdmin)
                        <th scope="col">Youth ikut Activity</th>
                    @endif
                    <th scope="col">Community</th><th scope="col">Activity</th><th scope="col">Program</th></tr></thead>
                <tbody>
                    @foreach($coverage as $row)
                        <tr data-map-row="{{ $row['code'] }}"><th scope="row"><button type="button" data-map-select="{{ $row['code'] }}">{{ $row['district'] }}</button></th><td>{{ $display($row['youth']) }}</td>
                            @if($isAdmin)
                                <td>{{ $display($row['participated']) }}</td>
                            @endif
                            @foreach(['community', 'activity', 'program'] as $key)
                                <td>{{ $display($row[$key]) }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table></div>
        </section>
        <p class="eco-map-note">@unless($isAdmin)Jumlah 1–{{ $threshold - 1 }} ditampilkan sebagai “&lt;{{ $threshold }}” dan tidak dikirim sebagai angka pasti ke peta. @endunless Peta menampilkan agregat per kecamatan, tanpa titik lokasi atau identitas individu. Record tanpa kecamatan yang valid tidak ditempatkan di peta. Batas kecamatan digeneralisasi untuk tampilan web dari <a href="https://geoservices.big.go.id/rbi/rest/services/BATASWILAYAH/BATAS_KECAMATAN_AR/MapServer/0" target="_blank" rel="noopener noreferrer">data BIG edisi 2022</a>.</p>
    </div>
</div>
@php($mapData = ['districts' => $coverage, 'admin' => $isAdmin, 'threshold' => $threshold])
<script id="eco-map-data" type="application/json">@json($mapData, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT)</script>
@endsection
