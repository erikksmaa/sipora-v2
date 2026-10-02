@props(['status'])
@php
    $label = ['pending'=>'Menunggu review','accepted'=>'Diterima','rejected'=>'Ditolak','cancelled'=>'Dibatalkan'][$status] ?? $status;
    $class = match($status) {
        'accepted' => 'bg-[#E9F7F0] text-[#16865C] border border-emerald-200',
        'pending' => 'bg-[#FFF6DF] text-[#C2410C] border border-orange-200',
        'rejected' => 'bg-[#FDECEC] text-[#D64545] border border-red-200',
        default => 'bg-surface-soft text-text-secondary border border-border'
    };
@endphp
<span {{ $attributes->class("inline-flex rounded-full px-2.5 py-1 text-xs font-bold $class") }}>{{ $label }}</span>
