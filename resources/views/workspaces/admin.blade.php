@extends('layouts.admin')
@section('title', 'Dashboard Admin · SIPORA')
@section('content')
<div class="space-y-7">
    <section><p class="text-xs font-black uppercase tracking-[.16em] text-orange-600">Ringkasan faktual</p><h1 class="mt-2 text-3xl font-black text-[#18245c]">Dashboard SIPORA</h1><p class="mt-2 text-sm text-slate-600">Agregat operasional dari data SIPORA saat ini. Angka ini tidak memberi peringkat atau rekomendasi keputusan.</p></section>
    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach([
            ['Youth terdaftar', $analytics['youth']['registered']],
            ['Profil Youth', $analytics['youth']['with_profile']],
            ['Community aktif', $analytics['communities']['active']],
            ['Community menunggu', $analytics['communities']['pending']],
            ['Activity publik', $analytics['activities']['published']],
            ['Activity mendatang', $analytics['activities']['upcoming']],
            ['Opportunity publik', $analytics['opportunities']['published']],
            ['Opportunity terbuka', $analytics['opportunities']['open_deadlines']],
        ] as [$label, $value])
        <article class="sipora-card"><p class="text-sm font-semibold text-slate-500">{{ $label }}</p><p class="mt-3 text-3xl font-black text-[#243378]">{{ number_format($value) }}</p></article>
        @endforeach
    </section>
    <div class="grid gap-6 xl:grid-cols-2">
        <section class="sipora-card"><h2 class="text-xl font-black text-[#18245c]">Status Program</h2><div class="mt-5 grid gap-3 sm:grid-cols-2">@forelse($analytics['programs'] as $status => $total)<div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3"><span class="text-sm font-semibold">{{ str($status)->replace('_', ' ')->title() }}</span><strong>{{ number_format($total) }}</strong></div>@empty<p class="text-sm text-slate-500">Belum ada Program.</p>@endforelse</div></section>
        <section class="sipora-card"><h2 class="text-xl font-black text-[#18245c]">Operasional platform</h2><dl class="mt-5 space-y-3 text-sm"><div class="flex justify-between"><dt>Registrasi Activity</dt><dd class="font-black">{{ number_format($analytics['activities']['registrations']) }}</dd></div><div class="flex justify-between"><dt>Partisipasi selesai</dt><dd class="font-black">{{ number_format($analytics['activities']['completed_participations']) }}</dd></div><div class="flex justify-between"><dt>Keanggotaan Community aktif</dt><dd class="font-black">{{ number_format($analytics['communities']['active_memberships']) }}</dd></div></dl></section>
    </div>
    <section class="flex flex-wrap gap-3"><a class="btn-primary" href="{{ route('admin.identity-verifications.index') }}">Tinjau identitas</a><a class="btn-secondary" href="{{ route('admin.opportunities.index') }}">Kelola Opportunity</a></section>
</div>
@endsection
