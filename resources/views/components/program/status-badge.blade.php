@props(['status'])
@php
    $styles = [
        'planned' => 'bg-primary-50 text-primary border border-primary-200',
        'running' => 'bg-success-soft text-success border border-emerald-200',
        'completed' => 'bg-surface-soft text-text-secondary border border-border',
        'cancelled' => 'bg-danger-soft text-danger border border-red-200'
    ];
    $labels = ['planned' => 'Rencana', 'running' => 'Berjalan', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'];
@endphp
<span {{ $attributes->class(['inline-flex rounded-full px-3 py-1 text-xs font-bold', $styles[$status] ?? 'bg-surface-soft text-text-secondary border border-border']) }}>{{ $labels[$status] ?? ucfirst($status) }}</span>
