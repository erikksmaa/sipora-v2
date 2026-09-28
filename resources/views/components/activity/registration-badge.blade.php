@props(['status'])
@php
    $label = ['pending'=>'Menunggu review','accepted'=>'Diterima','rejected'=>'Ditolak','cancelled'=>'Dibatalkan'][$status] ?? $status;
    $class = match($status) { 'accepted' => 'bg-emerald-100 text-emerald-800', 'pending' => 'bg-orange-100 text-orange-800', 'rejected' => 'bg-red-100 text-red-800', default => 'bg-slate-100 text-slate-700' };
@endphp
<span {{ $attributes->class("inline-flex rounded-full px-2.5 py-1 text-xs font-bold $class") }}>{{ $label }}</span>
