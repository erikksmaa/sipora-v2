@extends('layouts.youth')
@section('title', 'Edit Pengajuan Komunitas · SIPORA')
@section('content')
<div class="mx-auto max-w-4xl"><div class="mb-6 flex flex-wrap items-end justify-between gap-4"><div><a class="text-sm font-bold text-[#243378]" href="{{ route('youth.communities.show', $organization) }}">← Kembali ke detail</a><h1 class="mt-3 text-3xl font-extrabold text-[#243378]">Edit pengajuan</h1></div><x-community.status-badge :status="$organization->review_status" /></div>@if($organization->review_status === 'revision' && $organization->latestVerificationRequest?->review_notes)<div class="mb-6 rounded-xl border border-orange-300 bg-orange-50 p-4"><p class="font-bold text-orange-900">Catatan revisi</p><p class="mt-1 text-sm text-orange-800">{{ $organization->latestVerificationRequest->review_notes }}</p></div>@endif @include('youth.communities._form')</div>
@endsection
