@extends('layouts.verifier')
@section('title', 'Dashboard Verifier · SIPORA')
@section('verifier-content')
<div class="space-y-7"><section><p class="text-xs font-black uppercase tracking-[.16em] text-orange-600">Ringkasan antrean</p><h1 class="mt-2 text-3xl font-black text-[#18245c]">Dashboard Verifier</h1><p class="mt-2 text-sm text-slate-600">Jumlah item yang sedang menunggu tindakan sesuai kewenangan Verifier.</p></section>
<section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
@foreach([
 ['Community', $queues['communities'], route('verifier.community-verifications.index')],
 ['Activity', $queues['activities'], route('verifier.activity-verifications.index')],
 ['Proposal Program', $queues['proposals'], route('verifier.program-proposals.index')],
 ['Logbook', $queues['logbooks'], route('verifier.program-monitoring.index')],
 ['E-LPJ', $queues['financial_reports'], route('verifier.financial-reports.index')],
 ['Evaluasi akhir siap', $queues['evaluations'], route('verifier.program-evaluations.index')],
] as [$label, $value, $href])<a href="{{ $href }}" class="sipora-card transition hover:-translate-y-0.5 hover:border-indigo-300"><p class="text-sm font-semibold text-slate-500">{{ $label }}</p><p class="mt-3 text-3xl font-black text-[#243378]">{{ number_format($value) }}</p><p class="mt-3 text-xs font-bold text-[#3346a8]">Buka antrean →</p></a>@endforeach
</section></div>
@endsection
