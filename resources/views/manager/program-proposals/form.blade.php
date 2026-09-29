@extends('layouts.manager')
@section('title', $proposal->exists ? 'Edit Proposal Program' : 'Buat Proposal Program')
@section('manager-content')
<div class="mx-auto max-w-3xl space-y-6">
    <header><p class="text-sm font-bold text-orange-600">PROGRAM · PROPOSAL</p><h1 class="mt-1 text-3xl font-black text-[#14205c]">{{ $proposal->exists ? 'Edit draft Proposal v'.$proposal->version : 'Buat draft Proposal' }}</h1><p class="mt-2 text-slate-600">{{ $program->title }}</p></header>
    <form method="POST" enctype="multipart/form-data" action="{{ $proposal->exists ? route('manager.program-proposals.update', [$organization, $program, $proposal]) : route('manager.program-proposals.store', [$organization, $program]) }}" class="space-y-6 rounded-2xl bg-white p-6 shadow-sm">
        @csrf @if($proposal->exists) @method('PATCH') @endif
        <div><label for="requested_budget" class="label">Anggaran yang diajukan (Rp)</label><input id="requested_budget" name="requested_budget" type="number" min="0" step="0.01" class="field" value="{{ old('requested_budget', $proposal->requested_budget) }}"><x-input-error :messages="$errors->get('requested_budget')" /></div>
        <div><label for="proposal_document" class="label">Dokumen Proposal</label><input id="proposal_document" name="proposal_document" type="file" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" class="field"><p class="mt-2 text-xs text-slate-500">PDF, DOC, atau DOCX. Maksimal 10 MB. Dokumen disimpan privat.</p><x-input-error :messages="$errors->get('proposal_document')" />@if($proposal->exists && $proposal->proposal_document_path)<a class="mt-3 inline-flex text-sm font-bold text-[#243378] hover:underline" href="{{ route('manager.program-proposals.document', [$organization, $program, $proposal]) }}" target="_blank">Lihat dokumen saat ini</a>@endif</div>
        <div class="flex flex-wrap gap-3"><button class="btn-primary">Simpan draft</button><a class="btn-secondary" href="{{ route('manager.programs.show', [$organization, $program]) }}">Kembali</a></div>
    </form>
</div>
@endsection
