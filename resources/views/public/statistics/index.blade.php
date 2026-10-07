@extends('layouts.public')
@section('title', 'Statistik Pemuda · SIPORA')
@section('meta_description', 'Statistik agregat pemuda yang terdaftar dan beraktivitas di SIPORA.')
@section('canonical', route('statistics.index'))
@push('head')
    @vite(['resources/css/statistics.css', 'resources/js/statistics.js'])
@endpush
@section('content')
<x-public.page-intro eyebrow="Data ekosistem" title="Statistik Pemuda"
    description="Berdasarkan pemuda yang terdaftar di SIPORA. Angka ini tidak mewakili seluruh populasi pemuda Kabupaten Pemalang."
    icon="users" variant="youth" />
<div class="stats-dashboard"><div class="stats-container">
    <form method="GET" action="{{ route('statistics.index') }}" class="stats-filters" aria-label="Filter statistik pemuda">
        <label>Tahun<select name="year"><option value="">Semua tahun</option>@foreach($options['years'] as $year)<option value="{{ $year }}" @selected(($filters['year'] ?? null) === $year)>{{ $year }}</option>@endforeach</select></label>
        <label>Kecamatan<select name="district"><option value="">Semua kecamatan</option>@foreach($options['districts'] as $district)<option value="{{ $district['code'] }}" @selected(($filters['district'] ?? null) === $district['code'])>{{ $district['name'] }}</option>@endforeach</select></label>
        <label>Rentang usia<select name="age"><option value="">Semua usia</option>@foreach($options['ages'] as $key => $label)<option value="{{ $key }}" @selected(($filters['age'] ?? null) === $key)>{{ $label }} tahun</option>@endforeach</select></label>
        <button type="submit" class="stats-filter-button">Terapkan filter</button>
        @if($filters)<a class="stats-reset" href="{{ route('statistics.index') }}">Atur ulang</a>@endif
    </form>
    <section aria-labelledby="stats-headline">
        <div class="stats-section-heading"><div><p class="stats-eyebrow">Ekosistem dalam angka</p><h2 id="stats-headline">Gambaran pemuda SIPORA</h2></div><p>Berdasarkan pemuda yang terdaftar di SIPORA</p></div>
        <div class="stats-headline-grid">
            @foreach([['registered','Pemuda Terdaftar','users'],['identity_verified','Identitas Terverifikasi','shield-check'],['activity_participated','Pernah Mengikuti Activity','activity'],['certificate','Memiliki Sertifikat SIPORA','award']] as [$key,$label,$icon])
                <x-public.stat-card :icon="$icon" :value="$statistics['totals'][$key] === null ? '<'.$threshold : number_format($statistics['totals'][$key])" :label="$label" tone="blue" />
            @endforeach
        </div>
    </section>
    <section class="stats-panel stats-panel-wide" aria-labelledby="stats-growth-title">
        <div class="stats-panel-heading"><div><p class="stats-eyebrow">Pertumbuhan</p><h2 id="stats-growth-title">Pendaftaran Youth</h2><p>Jumlah akun Youth baru pada periode yang dipilih.</p></div><div class="stats-segment" role="group" aria-label="Resolusi grafik pertumbuhan"><button type="button" data-growth-mode="monthly" aria-pressed="true">Per bulan</button><button type="button" data-growth-mode="yearly" aria-pressed="false">Per tahun</button></div></div>
        <div class="stats-canvas stats-canvas-growth"><canvas data-chart="growth" aria-hidden="true"></canvas></div>
        <details class="stats-data"><summary>Lihat data pertumbuhan</summary><div class="stats-data-columns">@foreach(['growth_monthly' => 'Bulanan','growth_yearly' => 'Tahunan'] as $key => $label)<div><h3>{{ $label }}</h3><dl>@foreach($statistics[$key] as $row)<div><dt>{{ $row['label'] }}</dt><dd>{{ $row['count'] === null ? '<'.$threshold : number_format($row['count']) }}</dd></div>@endforeach</dl></div>@endforeach</div></details>
    </section>
    <div class="stats-grid-two">
        @foreach([['age','Distribusi usia','Rentang usia dihitung dari tanggal lahir.'],['gender','Distribusi gender','Sesuai biodata yang diisi.']] as [$key,$title,$description])
            <section class="stats-panel" aria-labelledby="stats-{{ $key }}"><p class="stats-eyebrow">Demografi</p><h2 id="stats-{{ $key }}">{{ $title }}</h2><p>{{ $description }}</p><div class="stats-canvas"><canvas data-chart="{{ $key }}" aria-hidden="true"></canvas></div>@include('public.statistics.rows', ['rows' => $statistics[$key], 'threshold' => $threshold, 'kind' => $key])</section>
        @endforeach
    </div>
    <section class="stats-panel stats-panel-wide" aria-labelledby="stats-districts"><p class="stats-eyebrow">Persebaran</p><h2 id="stats-districts">Kecamatan domisili</h2><p>Berdasarkan alamat domisili utama yang diisi Youth.</p><div class="stats-canvas stats-canvas-tall"><canvas data-chart="districts" aria-hidden="true"></canvas></div>@include('public.statistics.rows', ['rows' => $statistics['districts'], 'threshold' => $threshold, 'kind' => 'districts'])</section>
    <div class="stats-grid-two">
        @foreach([['interests','Top Interest','Minat yang dipilih pada profil.'],['skills','Top Skill','Keahlian yang dipilih pada profil.']] as [$key,$title,$description])
            <section class="stats-panel" aria-labelledby="stats-{{ $key }}"><p class="stats-eyebrow">Minat & keahlian</p><h2 id="stats-{{ $key }}">{{ $title }}</h2><p>{{ $description }}</p><div class="stats-canvas stats-canvas-tall"><canvas data-chart="{{ $key }}" aria-hidden="true"></canvas></div>@include('public.statistics.rows', ['rows' => $statistics[$key], 'threshold' => $threshold, 'kind' => $key])</section>
        @endforeach
    </div>
    <section class="stats-panel stats-panel-wide" aria-labelledby="stats-journey"><p class="stats-eyebrow">Partisipasi</p><h2 id="stats-journey">Perjalanan Youth</h2><p>Setiap tahap menghitung Youth unik yang memenuhi tahap tersebut serta seluruh tahap sebelumnya. Profil lengkap mencakup biodata, domisili, bio, dan status pekerjaan; mengikuti Activity berarti pendaftaran diterima.</p><ol class="stats-journey">@php($journeyMax = max(1, $statistics['funnel'][0]['count'] ?? 1))@foreach($statistics['funnel'] as $step)<li><div class="stats-journey-top"><span class="stats-step">{{ $loop->iteration }}</span><span class="stats-journey-label">{{ $step['label'] }}</span><strong>{{ $step['count'] === null ? '<'.$threshold : number_format($step['count']) }}</strong></div><div class="stats-journey-track" aria-hidden="true"><span style="width: {{ $step['count'] === null ? 0 : round(100 * $step['count'] / $journeyMax) }}%"></span></div></li>@endforeach</ol></section>
    <section aria-labelledby="stats-ecosystem"><div class="stats-section-heading"><div><p class="stats-eyebrow">Aktivitas ekosistem</p><h2 id="stats-ecosystem">Activity, Community & Program</h2></div><p>Jumlah record berdasarkan status operasional. Bagian ini mencakup seluruh ekosistem dan tidak dipengaruhi filter Youth.</p></div><div class="stats-grid-three">@foreach(['activity' => 'Activity terbit', 'community' => 'Community disetujui', 'program' => 'Program'] as $key => $title)<div class="stats-panel"><h3>{{ $title }}</h3><div class="stats-canvas stats-canvas-small"><canvas data-chart="ecosystem-{{ $key }}" aria-hidden="true"></canvas></div>@include('public.statistics.rows', ['rows' => $statistics['ecosystem'][$key], 'threshold' => $threshold, 'kind' => 'status'])</div>@endforeach</div></section>
    <p class="stats-privacy">Angka kategori/filter di bawah {{ $threshold }} ditampilkan sebagai “&lt;{{ $threshold }}” untuk menjaga privasi. Statistik ini hanya menggambarkan akun Youth yang terdaftar di SIPORA.</p>
</div></div>
<script id="statistics-data" type="application/json">@json($statistics, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT)</script>
@endsection
