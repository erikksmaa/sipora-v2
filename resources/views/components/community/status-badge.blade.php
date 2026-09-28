@props(['status'])
@php
    $styles = match ($status) {
        'pending_review' => 'bg-amber-100 text-amber-800',
        'revision' => 'bg-orange-100 text-orange-800',
        'rejected' => 'bg-red-100 text-red-800',
        'approved' => 'bg-emerald-100 text-emerald-800',
        default => 'bg-slate-100 text-slate-700',
    };
    $labels = [
        'draft' => 'Draft',
        'pending_review' => 'Menunggu verifikasi',
        'revision' => 'Perlu revisi',
        'rejected' => 'Ditolak',
        'approved' => 'Disetujui',
    ];
@endphp
<span {{ $attributes->class(['inline-flex rounded-full px-3 py-1 text-xs font-bold', $styles]) }}>{{ $labels[$status] ?? ucfirst($status) }}</span>
