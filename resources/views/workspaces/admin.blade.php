@extends('layouts.admin')
@section('title', 'Dashboard Admin · SIPORA')
@section('content')
<div class="space-y-7">
    <x-ui.page-header eyebrow="Ringkasan faktual" title="Dashboard Tata Kelola SIPORA" description="Agregat operasional dari data SIPORA saat ini. Angka ini tidak memberi peringkat atau rekomendasi keputusan.">
        <x-slot:actions><x-ui.button :href="route('admin.identity-verifications.index')" icon="shield-check">Tinjau identitas</x-ui.button><x-ui.button :href="route('admin.opportunities.index')" variant="secondary" icon="sparkles">Kelola Opportunity</x-ui.button></x-slot:actions>
    </x-ui.page-header>

    <section aria-labelledby="admin-metrics"><h2 id="admin-metrics" class="sr-only">Metrik operasional</h2><div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-ui.metric-card title="Youth terdaftar" :value="number_format($analytics['youth']['registered'])" icon="users" />
        <x-ui.metric-card title="Profil Youth" :value="number_format($analytics['youth']['with_profile'])" icon="badge-check" tone="success" />
        <x-ui.metric-card title="Community aktif" :value="number_format($analytics['communities']['active'])" icon="building" tone="info" />
        <x-ui.metric-card title="Community menunggu" :value="number_format($analytics['communities']['pending'])" icon="calendar" tone="accent" />
        <x-ui.metric-card title="Activity publik" :value="number_format($analytics['activities']['published'])" icon="clipboard-check" tone="info" />
        <x-ui.metric-card title="Activity mendatang" :value="number_format($analytics['activities']['upcoming'])" icon="calendar" />
        <x-ui.metric-card title="Opportunity publik" :value="number_format($analytics['opportunities']['published'])" icon="sparkles" tone="accent" />
        <x-ui.metric-card title="Opportunity terbuka" :value="number_format($analytics['opportunities']['open_deadlines'])" icon="briefcase" tone="success" />
    </div></section>

    <div class="grid gap-6 xl:grid-cols-[1.15fr_.85fr]">
        <x-ui.card><div class="flex items-center gap-3"><span class="grid size-10 place-items-center rounded-xl bg-indigo-50 text-primary"><x-ui.icon name="layout-dashboard" /></span><div><h2 class="text-lg font-extrabold text-primary-dark">Status Program</h2><p class="text-xs text-slate-500">Distribusi berdasarkan status eksekusi</p></div></div><div class="mt-5 grid gap-3 sm:grid-cols-2">@forelse($analytics['programs'] as $status => $total)<div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-4 py-3"><span class="text-sm font-semibold">{{ str($status)->replace('_', ' ')->title() }}</span><strong class="text-primary">{{ number_format($total) }}</strong></div>@empty<x-ui.empty-state class="sm:col-span-2" title="Belum ada Program" description="Status Program akan tampil setelah data tersedia." />@endforelse</div></x-ui.card>
        <x-ui.card><div class="flex items-center gap-3"><span class="grid size-10 place-items-center rounded-xl bg-orange-50 text-orange-700"><x-ui.icon name="activity" /></span><div><h2 class="text-lg font-extrabold text-primary-dark">Operasional platform</h2><p class="text-xs text-slate-500">Ringkasan aktivitas pengguna</p></div></div><dl class="mt-5 space-y-3 text-sm"><div class="flex justify-between rounded-xl bg-slate-50 px-4 py-3"><dt>Registrasi Activity</dt><dd class="font-extrabold text-primary">{{ number_format($analytics['activities']['registrations']) }}</dd></div><div class="flex justify-between rounded-xl bg-slate-50 px-4 py-3"><dt>Partisipasi selesai</dt><dd class="font-extrabold text-primary">{{ number_format($analytics['activities']['completed_participations']) }}</dd></div><div class="flex justify-between rounded-xl bg-slate-50 px-4 py-3"><dt>Keanggotaan aktif</dt><dd class="font-extrabold text-primary">{{ number_format($analytics['communities']['active_memberships']) }}</dd></div></dl></x-ui.card>
    </div>
</div>
@endsection
