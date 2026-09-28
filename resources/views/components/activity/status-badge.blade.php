@props(['status'])
@php
    $label = ['draft'=>'Draft','pending_review'=>'Menunggu review','revision'=>'Perlu revisi','rejected'=>'Ditolak','approved'=>'Disetujui','unpublished'=>'Belum terbit','published'=>'Terbit','archived'=>'Diarsipkan','scheduled'=>'Terjadwal','ongoing'=>'Berlangsung','completed'=>'Selesai','cancelled'=>'Dibatalkan'][$status] ?? $status;
    $class = match($status) { 'approved','published','completed' => 'bg-emerald-100 text-emerald-800', 'pending_review','scheduled' => 'bg-amber-100 text-amber-800', 'revision' => 'bg-orange-100 text-orange-800', 'rejected','cancelled' => 'bg-red-100 text-red-800', default => 'bg-slate-100 text-slate-700' };
@endphp
<span {{ $attributes->class("inline-flex rounded-full px-2.5 py-1 text-xs font-bold $class") }}>{{ $label }}</span>
