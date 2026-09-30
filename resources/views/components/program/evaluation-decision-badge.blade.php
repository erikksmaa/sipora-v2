@props(['decision'])
@php
$labels=['approved'=>'Disetujui','revision'=>'Tindak lanjut','rejected'=>'Ditolak'];
$styles=['approved'=>'bg-emerald-100 text-emerald-800','revision'=>'bg-orange-100 text-orange-800','rejected'=>'bg-rose-100 text-rose-800'];
@endphp
<span {{ $attributes->class(['inline-flex rounded-full px-3 py-1 text-xs font-extrabold', $styles[$decision] ?? 'bg-slate-100 text-slate-700']) }}>{{ $labels[$decision] ?? $decision }}</span>
