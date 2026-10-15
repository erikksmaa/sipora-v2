@extends('layouts.verifier')
@section('title','Dashboard Verifier · SIPORA')
@section('verifier-content')
<div class="space-y-7"><x-ui.page-header eyebrow="Government Workspace" title="Dashboard Pengawasan & Kurasi" description="Gambaran ekosistem dan pekerjaan review sesuai kewenangan Verifier Dindikpora. Identitas pribadi tetap menjadi kewenangan Admin."><x-slot:actions><x-ui.badge tone="success" icon="badge-check">Verifier aktif</x-ui.badge></x-slot:actions></x-ui.page-header>
<section aria-labelledby="ecosystem-summary"><h2 id="ecosystem-summary" class="mb-2 text-lg font-bold text-text-primary">Gambaran ekosistem</h2><p class="mb-4 text-sm text-text-secondary">Berdasarkan record terdaftar di SIPORA, terpisah dari antrean review.</p><div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
<x-ui.metric-card title="Pemuda Terdaftar" :value="number_format($ecosystem['youth'])" icon="users" :href="route('verifier.directory.youth.index')" action="Lihat direktori" />
<x-ui.metric-card title="Community Aktif" :value="number_format($ecosystem['communities'])" icon="building" tone="info" :href="route('verifier.directory.communities.index', ['status'=>'approved','operational'=>'active'])" action="Lihat Community aktif" />
<x-ui.metric-card title="Activity Publik" :value="number_format($ecosystem['activities'])" icon="clipboard-check" tone="accent" :href="route('verifier.directory.activities.index', ['status'=>'approved','publication'=>'published'])" action="Lihat Activity terbit" />
<x-ui.metric-card title="Program Berjalan" :value="number_format($ecosystem['programs']['running'] ?? 0)" icon="file-text" :href="route('verifier.directory.programs.index', ['status'=>'running'])" action="Lihat Program berjalan" />
</div></section>
<section aria-labelledby="queue-summary"><h2 id="queue-summary" class="mb-4 text-lg font-bold text-text-primary">Ringkasan antrean</h2><div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
@foreach([
['Community',$queues['communities'],'building',route('verifier.community-verifications.index', ['status'=>'pending_review']),'primary'],
['Activity',$queues['activities'],'clipboard-check',route('verifier.activity-verifications.index', ['status'=>'pending_review']),'accent'],
['Proposal Program',$queues['proposals'],'file-text',route('verifier.program-proposals.index', ['status'=>'submitted']),'info'],
['Logbook',$queues['logbooks'],'notebook',route('verifier.program-monitoring.index', ['status'=>'submitted']),'success'],
['E-LPJ Keuangan',$queues['financial_reports'],'wallet',route('verifier.financial-reports.index', ['status'=>'submitted']),'accent'],
['Evaluasi Akhir',$queues['evaluations'],'badge-check',route('verifier.program-evaluations.index'),'success']
] as [$label,$count,$icon,$href,$tone])<a href="{{ $href }}" class="surface-card surface-card-hover flex items-center gap-4 p-5"><span class="grid size-11 shrink-0 place-items-center rounded-xl bg-primary-50 text-primary"><x-ui.icon :name="$icon" /></span><span class="min-w-0 flex-1"><span class="block text-sm font-bold text-text-secondary">{{ $label }}</span><strong class="mt-1 block text-3xl text-text-primary">{{ number_format($count) }}</strong><span class="mt-1 block text-xs font-bold text-primary">Buka antrean</span></span><x-ui.icon name="chevron-right" class="text-primary" /></a>@endforeach
</div></section></div>
@endsection
