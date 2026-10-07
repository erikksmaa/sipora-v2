@extends('layouts.youth')
@section('title', 'Bukti Portfolio · SIPORA')
@section('content')
@php
    $title = match ($type) {
        'education' => $item->institution_name,
        'organization' => $item->organization_name,
        default => $item->title,
    };
    $hasEvidence = $type === 'achievement' ? filled($item->evidence_path) : $evidence !== null;
    $verified = $type === 'achievement' && $item->verification_status === 'verified';
@endphp
<div class="mx-auto max-w-3xl space-y-6">
    <x-workspace.page-header eyebrow="Portfolio · Bukti Pendukung" :title="$title" description="Bukti ini privat. Mengunggah file tidak mengubah klaim menjadi catatan terverifikasi SIPORA." />
    <section class="sipora-card space-y-4">
        <div class="flex flex-wrap gap-2"><span class="status-badge">{{ $verified ? 'SIPORA Verified' : 'Self-reported' }}</span><span class="status-badge">{{ $hasEvidence ? 'Bukti terlampir' : 'Tanpa bukti' }}</span></div>
        <dl class="grid gap-4 text-sm sm:grid-cols-2">
            <div><dt class="font-bold">Jenis catatan</dt><dd>{{ match ($type) { 'education' => 'Pendidikan', 'organization' => 'Pengalaman organisasi', default => 'Prestasi' } }}</dd></div>
            <div><dt class="font-bold">Visibilitas catatan</dt><dd>{{ $visible ? 'Mengikuti pengaturan Portfolio publik' : 'Disembunyikan dari publik' }}</dd></div>
            <div><dt class="font-bold">Visibilitas file</dt><dd>Privat, hanya akun pemilik</dd></div>
            @if($evidence)<div><dt class="font-bold">Nama file</dt><dd class="break-all">{{ $evidence->original_name }}</dd></div><div><dt class="font-bold">Jenis dan ukuran</dt><dd>{{ $evidence->mime_type }} · {{ number_format($evidence->size_bytes / 1024, 1) }} KB</dd></div>@endif
            @if($achievementEvidenceInfo)<div><dt class="font-bold">Jenis dan ukuran bukti prestasi</dt><dd>{{ $achievementEvidenceInfo['mime_type'] }} · {{ number_format($achievementEvidenceInfo['size_bytes'] / 1024, 1) }} KB</dd></div>@endif
        </dl>
        @if($hasEvidence)<a class="btn-secondary" href="{{ route('youth.portfolio.evidence.file', [$type, $item->uuid()]) }}" target="_blank" rel="noopener">Lihat / unduh bukti privat</a>@endif
    </section>
    @unless($verified)
        <section class="sipora-card space-y-4">
            <h2 class="text-lg font-extrabold">{{ $hasEvidence ? 'Ganti bukti' : 'Lampirkan bukti' }}</h2>
            <p class="text-sm text-text-secondary">PDF, JPG, atau PNG. Maksimum 5 MB. File tetap privat dan tidak memperoleh status verifikasi otomatis.</p>
            <form method="post" enctype="multipart/form-data" action="{{ route('youth.portfolio.evidence.store', [$type, $item->uuid()]) }}" class="space-y-4">
                @csrf
                <label><span class="field-label">File bukti</span><input class="field" type="file" name="evidence" accept=".pdf,.jpg,.jpeg,.png" required>@error('evidence')<span class="field-error">{{ $message }}</span>@enderror</label>
                <button class="btn" type="submit">Simpan bukti privat</button>
            </form>
            @if($hasEvidence)<form method="post" action="{{ route('youth.portfolio.evidence.destroy', [$type, $item->uuid()]) }}" data-confirm="Hapus bukti ini?">@csrf @method('DELETE')<button class="btn-secondary" type="submit">Hapus bukti</button></form>@endif
        </section>
    @endunless
    <a class="btn-secondary" href="{{ route('youth.profile.show') }}">Kembali ke Profil</a>
</div>
@endsection
