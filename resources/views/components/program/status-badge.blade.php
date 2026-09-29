@props(['status'])
@php
    $styles = ['planned' => 'bg-indigo-50 text-indigo-700', 'running' => 'bg-emerald-50 text-emerald-700', 'completed' => 'bg-slate-100 text-slate-700', 'cancelled' => 'bg-red-50 text-red-700'];
    $labels = ['planned' => 'Rencana', 'running' => 'Berjalan', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'];
@endphp
<span {{ $attributes->class(['inline-flex rounded-full px-3 py-1 text-xs font-extrabold', $styles[$status] ?? 'bg-slate-100 text-slate-700']) }}>{{ $labels[$status] ?? ucfirst($status) }}</span>
