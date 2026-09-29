@props(['status'])
@php
$labels=['draft'=>'Draft','submitted'=>'Diajukan','under_review'=>'Ditinjau','revision'=>'Perlu revisi','rejected'=>'Ditolak','approved'=>'Disetujui'];
$styles=['draft'=>'bg-slate-100 text-slate-700','submitted'=>'bg-amber-100 text-amber-800','under_review'=>'bg-blue-100 text-blue-800','revision'=>'bg-orange-100 text-orange-800','rejected'=>'bg-rose-100 text-rose-800','approved'=>'bg-emerald-100 text-emerald-800'];
@endphp
<span {{ $attributes->class(['inline-flex rounded-full px-3 py-1 text-xs font-extrabold', $styles[$status] ?? 'bg-slate-100 text-slate-700']) }}>{{ $labels[$status] ?? $status }}</span>
