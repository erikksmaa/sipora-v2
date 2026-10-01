@extends('layouts.verifier')
@section('title', 'Dashboard Verifier · SIPORA')
@section('verifier-content')
<div class="space-y-7">
    <x-ui.page-header eyebrow="Ringkasan antrean" title="Dashboard Pengawasan & Kurasi" description="Jumlah item yang sedang menunggu tindakan sesuai kewenangan Verifier Dindikpora.">
        <x-slot:actions><x-ui.badge tone="success" icon="badge-check">Mode Verifier Aktif</x-ui.badge></x-slot:actions>
    </x-ui.page-header>

    <aside class="flex items-start gap-3 rounded-2xl border border-indigo-100 bg-indigo-50 p-4 text-sm text-primary"><span class="grid size-10 shrink-0 place-items-center rounded-xl bg-primary text-white"><x-ui.icon name="shield-check" /></span><div><strong class="font-extrabold">Kewenangan review operasional</strong><p class="mt-1 leading-6 text-slate-600">Community, Activity, Proposal, Logbook, E-LPJ, dan Evaluasi Program berada dalam antrean Verifier. Verifikasi identitas pribadi tetap menjadi kewenangan Admin.</p></div></aside>

    <section aria-labelledby="verifier-queues"><h2 id="verifier-queues" class="sr-only">Antrean Verifier</h2><div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        <x-ui.metric-card title="Community" :value="number_format($queues['communities'])" icon="building" :href="route('verifier.community-verifications.index')" action="Buka antrean" />
        <x-ui.metric-card title="Activity" :value="number_format($queues['activities'])" icon="clipboard-check" tone="accent" :href="route('verifier.activity-verifications.index')" action="Buka antrean" />
        <x-ui.metric-card title="Proposal Program" :value="number_format($queues['proposals'])" icon="file-text" tone="info" :href="route('verifier.program-proposals.index')" action="Buka antrean" />
        <x-ui.metric-card title="Logbook" :value="number_format($queues['logbooks'])" icon="notebook" tone="success" :href="route('verifier.program-monitoring.index')" action="Buka antrean" />
        <x-ui.metric-card title="E-LPJ" :value="number_format($queues['financial_reports'])" icon="wallet" tone="accent" :href="route('verifier.financial-reports.index')" action="Buka antrean" />
        <x-ui.metric-card title="Evaluasi akhir siap" :value="number_format($queues['evaluations'])" icon="badge-check" tone="success" :href="route('verifier.program-evaluations.index')" action="Buka antrean" />
    </div></section>
</div>
@endsection
