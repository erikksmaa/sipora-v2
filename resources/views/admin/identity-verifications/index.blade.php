@extends('layouts.admin')
@section('title', 'Antrean Verifikasi Identitas · SIPORA')
@section('content')
<div class="space-y-6">
    <x-ui.page-header eyebrow="Verifikasi identitas" title="Antrean Identitas Pemuda" description="Tinjau informasi minimum di antrean. Data identitas lengkap hanya tersedia pada detail privat.">
        <x-slot:actions><x-ui.badge tone="info" icon="shield-check">{{ $counts['pending'] ?? 0 }} menunggu</x-ui.badge></x-slot:actions>
    </x-ui.page-header>
    <section class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4" aria-label="Ringkasan status">
        @foreach(['pending' => ['Menunggu', 'info'], 'revision' => ['Perlu revisi', 'warning'], 'verified' => ['Disetujui', 'success'], 'rejected' => ['Ditolak', 'danger']] as $key => [$label, $tone])
            <x-ui.metric-card :title="$label" :value="number_format($counts[$key] ?? 0)" icon="shield-check" :tone="$tone === 'warning' ? 'accent' : $tone" :href="route('admin.identity-verifications.index', ['status' => $key])" action="Lihat antrean" />
        @endforeach
    </section>
    <x-workspace.filter-bar :reset-href="route('admin.identity-verifications.index')">
        <label><span class="sr-only">Cari nama atau email</span><input class="field" type="search" name="search" value="{{ $search }}" placeholder="Cari nama atau email Youth"></label>
        <label><span class="sr-only">Filter status</span><select class="field" name="status">@foreach(['pending'=>'Menunggu','revision'=>'Perlu revisi','verified'=>'Disetujui','rejected'=>'Ditolak','all'=>'Semua status'] as $value=>$label)<option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>@endforeach</select></label>
    </x-workspace.filter-bar>
    <section class="table-shell">
        <div class="overflow-x-auto"><table class="min-w-[760px]"><thead><tr><th>Youth</th><th>Dokumen</th><th>Diajukan</th><th>Status</th><th class="text-right">Aksi</th></tr></thead><tbody>@forelse($submissions as $submission)@php($applicant = $submission->userIdentity->user)<tr class="border-t border-border hover:bg-surface-soft"><td><strong class="block text-text-primary">{{ $applicant->profile?->full_name ?? $applicant->name }}</strong><span class="text-text-secondary">{{ $applicant->email }}</span></td><td class="font-bold uppercase">{{ str_replace('_', ' ', $submission->document_type) }}</td><td class="text-text-secondary">{{ $submission->submitted_at->translatedFormat('d M Y, H:i') }}</td><td><x-ui.badge :tone="match($submission->status){'verified'=>'success','revision'=>'warning','rejected'=>'danger',default=>'info'}">{{ str($submission->status)->replace('_',' ')->title() }}</x-ui.badge></td><td class="text-right"><a class="font-bold text-primary hover:text-accent" href="{{ route('admin.identity-verifications.show', $submission) }}">Tinjau</a></td></tr>@empty<tr><td colspan="5"><x-ui.empty-state icon="shield-check" title="Tidak ada pengajuan" description="Tidak ada pengajuan identitas untuk filter ini." /></td></tr>@endforelse</tbody></table></div>
    </section>
    {{ $submissions->links() }}
</div>
@endsection
