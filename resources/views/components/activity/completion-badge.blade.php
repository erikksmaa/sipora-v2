@props(['status'])
@php
$styles = ['pending' => 'bg-amber-100 text-amber-800', 'completed' => 'bg-emerald-100 text-emerald-800', 'no_show' => 'bg-slate-200 text-slate-700'];
$labels = ['pending' => 'Menunggu penyelesaian', 'completed' => 'Selesai terverifikasi', 'no_show' => 'Tidak hadir'];
@endphp
<span {{ $attributes->class(['inline-flex rounded-full px-2.5 py-1 text-xs font-bold', $styles[$status] ?? 'bg-slate-100 text-slate-700']) }}>{{ $labels[$status] ?? ucfirst($status) }}</span>
