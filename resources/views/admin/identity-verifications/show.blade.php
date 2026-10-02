@extends('layouts.admin')
@section('title', 'Tinjau Verifikasi Identitas · SIPORA')
@section('content')
@php($applicant = $submission->userIdentity->user)
<div class="space-y-6">
    <x-ui.page-header eyebrow="{{ $applicant->profile?->full_name ?? $applicant->name }}" title="Tinjau Identitas Pemuda" description="Dokumen dan nomor identitas bersifat privat dan hanya tersedia untuk Admin terotorisasi.">
        <x-slot:actions><a class="btn-secondary" href="{{ route('admin.identity-verifications.index') }}">Kembali ke antrean</a><x-ui.badge :tone="match($submission->status){'verified'=>'success','revision'=>'warning','rejected'=>'danger',default=>'info'}">{{ str($submission->status)->title() }}</x-ui.badge></x-slot:actions>
    </x-ui.page-header>
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_380px]">
        <main class="space-y-6">
            <x-ui.card><div class="flex items-center gap-4"><span class="grid size-12 place-items-center rounded-xl bg-primary-50 font-bold text-primary">{{ str($applicant->name)->substr(0,2)->upper() }}</span><div><h2 class="text-lg font-bold text-text-primary">{{ $applicant->profile?->full_name ?? $applicant->name }}</h2><p class="text-sm text-text-secondary">{{ $applicant->email }}</p></div></div><dl class="mt-5 grid gap-4 border-t border-border pt-5 sm:grid-cols-2"><div><dt class="text-xs font-bold text-text-muted">JENIS DOKUMEN</dt><dd class="mt-1 font-bold uppercase">{{ str_replace('_',' ',$submission->document_type) }}</dd></div><div><dt class="text-xs font-bold text-text-muted">DIAJUKAN</dt><dd class="mt-1">{{ $submission->submitted_at->translatedFormat('d M Y, H:i') }}</dd></div><div><dt class="text-xs font-bold text-text-muted">NOMOR DOKUMEN</dt><dd class="mt-1 break-all font-mono font-bold">{{ $documentNumber }}</dd></div><div><dt class="text-xs font-bold text-text-muted">DOMISILI PROFIL</dt><dd class="mt-1">{{ $applicant->primaryDomicile?->administrativeArea?->name ?? '—' }}</dd></div></dl></x-ui.card>
            <x-workspace.file-card title="Dokumen identitas" :description="'Dokumen '.str_replace('_',' ',$submission->document_type)" :href="route('admin.identity-verifications.document',$submission)" type="Dokumen privat" icon="shield-check" />
            <section class="sipora-card"><h2 class="text-lg font-bold text-text-primary">Pratinjau privat</h2><p class="mt-1 text-sm text-text-secondary">Konten dilayani melalui route Admin terotorisasi tanpa mengekspos path penyimpanan.</p><iframe title="Dokumen identitas pengajuan" class="mt-4 h-[520px] w-full rounded-xl border border-border bg-surface-soft" src="{{ route('admin.identity-verifications.document',$submission) }}"></iframe></section>
            @if($submission->review_notes || $previousReview?->review_notes)<x-workspace.review-history><article><x-ui.badge :tone="$submission->status === 'rejected' ? 'danger' : 'warning'">{{ str($submission->status)->title() }}</x-ui.badge><p class="mt-2 whitespace-pre-line text-sm text-text-secondary">{{ $submission->review_notes ?? $previousReview->review_notes }}</p></article></x-workspace.review-history>@endif
        </main>
        <aside class="h-fit space-y-4 xl:sticky xl:top-24">
            @if($submission->status === 'pending')
                <x-workspace.decision-panel title="Keputusan Admin" :action="route('admin.identity-verifications.review',$submission)" notes-name="review_notes" approve-label="Setujui identitas" approve-value="verified" />
            @else
                <x-ui.card><h2 class="text-lg font-bold text-text-primary">Keputusan tersimpan</h2><p class="mt-2 text-sm text-text-secondary">Ditinjau {{ $submission->reviewed_at?->translatedFormat('d M Y, H:i') }} oleh {{ $submission->reviewer?->name ?? 'Admin SIPORA' }}.</p></x-ui.card>
            @endif
        </aside>
    </div>
</div>
@endsection
