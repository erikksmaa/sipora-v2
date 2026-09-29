@props(['status'])
@php
    [$label, $classes] = match($status) {
        'draft' => ['Draft', 'bg-slate-100 text-slate-700'],
        'submitted' => ['Diajukan', 'bg-amber-100 text-amber-800'],
        'under_review' => ['Sedang ditinjau', 'bg-blue-100 text-blue-800'],
        'revision' => ['Perlu revisi', 'bg-orange-100 text-orange-800'],
        'rejected' => ['Ditolak', 'bg-rose-100 text-rose-800'],
        'approved' => ['Disetujui', 'bg-emerald-100 text-emerald-800'],
        default => [str($status)->headline(), 'bg-slate-100 text-slate-700'],
    };
@endphp
<span {{ $attributes->class("inline-flex rounded-full px-3 py-1 text-xs font-extrabold $classes") }}>{{ $label }}</span>
