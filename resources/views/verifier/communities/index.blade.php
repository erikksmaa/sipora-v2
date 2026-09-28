@extends('layouts.verifier')
@section('title', 'Verifikasi Komunitas · SIPORA')
@section('verifier-content')
<div class="space-y-6">
    <div><p class="text-sm font-bold text-orange-600">Organisasi & Komunitas</p><h1 class="mt-1 text-3xl font-extrabold text-[#142565]">Antrean verifikasi komunitas</h1><p class="mt-2 text-slate-600">Tinjau pengajuan yang telah dikirim oleh Youth.</p></div>

    <section class="grid gap-3 sm:grid-cols-4">@foreach(['pending_review' => 'Menunggu', 'revision' => 'Revisi', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'] as $key => $label)<a href="{{ route('verifier.community-verifications.index', ['status' => $key]) }}" class="sipora-card !p-4 {{ $status === $key ? 'ring-2 ring-[#243378]' : '' }}"><p class="text-xs font-bold uppercase text-slate-500">{{ $label }}</p><p class="mt-1 text-2xl font-extrabold text-[#243378]">{{ $counts[$key] ?? 0 }}</p></a>@endforeach</section>

    <section class="sipora-card">
        <form method="get" class="grid gap-3 sm:grid-cols-[1fr_190px_auto]"><label class="sr-only" for="search">Cari pengajuan</label><input class="field" id="search" name="search" value="{{ $search }}" placeholder="Cari komunitas atau pemohon"><label class="sr-only" for="status">Status</label><select class="field" id="status" name="status"><option value="all" @selected($status === 'all')>Semua status</option><option value="pending_review" @selected($status === 'pending_review')>Menunggu</option><option value="revision" @selected($status === 'revision')>Revisi</option><option value="approved" @selected($status === 'approved')>Disetujui</option><option value="rejected" @selected($status === 'rejected')>Ditolak</option></select><button class="btn" type="submit">Terapkan</button></form>

        <div class="mt-6 overflow-x-auto"><table class="w-full min-w-[760px] text-left text-sm"><thead class="border-y border-slate-200 bg-slate-50 text-xs uppercase tracking-wider text-slate-500"><tr><th class="px-4 py-3">Komunitas</th><th class="px-4 py-3">Pemohon</th><th class="px-4 py-3">Kategori</th><th class="px-4 py-3">Dikirim</th><th class="px-4 py-3">Status</th><th class="px-4 py-3"></th></tr></thead><tbody class="divide-y divide-slate-100">
            @forelse($communities as $community)<tr><td class="px-4 py-4 font-bold text-[#243378]">{{ $community->name }}</td><td class="px-4 py-4"><p class="font-semibold">{{ $community->creator?->profile?->full_name ?? $community->creator?->name ?? 'Akun tidak tersedia' }}</p><p class="text-xs text-slate-500">{{ $community->creator?->email }}</p></td><td class="px-4 py-4">{{ $community->category->name }}</td><td class="px-4 py-4">{{ $community->latestVerificationRequest?->submitted_at?->format('d M Y, H:i') ?? '—' }}</td><td class="px-4 py-4"><x-community.status-badge :status="$community->review_status" /></td><td class="px-4 py-4 text-right"><a class="font-bold text-[#243378]" href="{{ route('verifier.community-verifications.show', $community) }}">Tinjau →</a></td></tr>
            @empty<tr><td colspan="6" class="px-4 py-12 text-center text-slate-500">Tidak ada pengajuan untuk filter ini.</td></tr>@endforelse
        </tbody></table></div><div class="mt-5">{{ $communities->links() }}</div>
    </section>
</div>
@endsection
