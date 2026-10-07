@extends('layouts.youth')
@php
    $titles = ['education' => 'Pendidikan', 'experience' => 'Pengalaman Organisasi', 'achievements' => 'Prestasi', 'training' => 'Pelatihan & Sertifikasi'];
    $title = $titles[$section];
@endphp
@section('title', $title.' · SIPORA')
@section('content')
<div class="mx-auto max-w-4xl space-y-5">
    <a class="text-sm font-bold text-interactive" href="{{ route('youth.portfolio.show') }}">← Portfolio Saya</a>
    <x-workspace.page-header eyebrow="Portfolio" :title="$title" description="Catatan pribadi tetap self-reported. Bukti yang kamu lampirkan tidak otomatis menjadi verifikasi SIPORA." />

    <section class="sipora-card">
        <h2 class="text-lg font-extrabold">Tambah {{ $title }}</h2>
        @if($section === 'education')
            <form class="mt-4 grid gap-4 sm:grid-cols-2" method="post" action="{{ route('youth.educations.store') }}">@csrf
                <label><span class="field-label">Jenjang</span><select class="field" name="education_level" required><option value="">Pilih jenjang</option>@foreach(['sd'=>'SD','smp'=>'SMP','sma_smk'=>'SMA/SMK','d1'=>'D1','d2'=>'D2','d3'=>'D3','d4_s1'=>'D4/S1','s2'=>'S2','s3'=>'S3','other'=>'Lainnya'] as $value=>$label)<option value="{{ $value }}">{{ $label }}</option>@endforeach</select></label>
                <label><span class="field-label">Institusi</span><input class="field" name="institution_name" maxlength="180" required></label>
                <label><span class="field-label">Bidang studi</span><input class="field" name="field_of_study" maxlength="180"></label>
                <label><span class="field-label">Mulai</span><input class="field" type="date" name="start_date"></label>
                <label><span class="field-label">Selesai</span><input class="field" type="date" name="end_date"></label>
                <label class="flex items-center gap-2"><input type="checkbox" name="is_current" value="1"><span>Masih berlangsung</span></label>
                <label class="sm:col-span-2"><span class="field-label">Deskripsi</span><textarea class="field" name="description" maxlength="1000"></textarea></label>
                <div class="sm:col-span-2"><button class="btn" type="submit">Simpan pendidikan</button></div>
            </form>
        @elseif($section === 'experience')
            <form class="mt-4 grid gap-4 sm:grid-cols-2" method="post" enctype="multipart/form-data" action="{{ route('youth.organization-experiences.store') }}">@csrf
                <label><span class="field-label">Nama organisasi</span><input class="field" name="organization_name" maxlength="180" required></label>
                <label><span class="field-label">Jabatan / peran</span><input class="field" name="role_title" maxlength="160"></label>
                <label><span class="field-label">Mulai</span><input class="field" type="date" name="start_date"></label>
                <label><span class="field-label">Selesai</span><input class="field" type="date" name="end_date"></label>
                <label class="flex items-center gap-2"><input type="checkbox" name="is_current" value="1"><span>Masih aktif</span></label>
                <label class="sm:col-span-2"><span class="field-label">Deskripsi</span><textarea class="field" name="description" maxlength="1000"></textarea></label>
                <label class="sm:col-span-2"><span class="field-label">Sertifikat / bukti (opsional) · PDF/JPG/PNG, maks. 5 MB</span><input class="field" type="file" name="evidence" accept=".pdf,.jpg,.jpeg,.png"></label>
                <div class="sm:col-span-2"><button class="btn" type="submit">Simpan pengalaman</button></div>
            </form>
        @elseif($section === 'achievements')
            <form class="mt-4 grid gap-4 sm:grid-cols-2" method="post" enctype="multipart/form-data" action="{{ route('youth.achievements.store') }}">@csrf
                <label><span class="field-label">Judul prestasi</span><input class="field" name="title" maxlength="180" required></label>
                <label><span class="field-label">Pemberi penghargaan</span><input class="field" name="issuer_name" maxlength="180"></label>
                <label><span class="field-label">Tanggal</span><input class="field" type="date" name="achievement_date"></label>
                <label class="sm:col-span-2"><span class="field-label">Deskripsi</span><textarea class="field" name="description" maxlength="1000"></textarea></label>
                <label class="sm:col-span-2"><span class="field-label">Sertifikat / bukti (opsional) · PDF/JPG/PNG, maks. 5 MB</span><input class="field" type="file" name="evidence" accept=".pdf,.jpg,.jpeg,.png"></label>
                <div class="sm:col-span-2"><button class="btn" type="submit">Simpan prestasi</button></div>
            </form>
        @else
            <form class="mt-4 grid gap-4 sm:grid-cols-2" method="post" enctype="multipart/form-data" action="{{ route('youth.portfolio.external-certificates.store') }}">@csrf
                <label><span class="field-label">Nama pelatihan / sertifikat</span><input class="field" name="name" maxlength="180" required></label>
                <label><span class="field-label">Penerbit / penyelenggara</span><input class="field" name="issuer_name" maxlength="180" required></label>
                <label><span class="field-label">Tanggal terbit</span><input class="field" type="date" name="issued_at" required></label>
                <label><span class="field-label">Nomor kredensial (opsional)</span><input class="field" name="certificate_number" maxlength="120"></label>
                <label class="sm:col-span-2"><span class="field-label">Sertifikat privat (opsional) · PDF/JPG/PNG, maks. 5 MB</span><input class="field" type="file" name="evidence" accept=".pdf,.jpg,.jpeg,.png"></label>
                <div class="sm:col-span-2"><button class="btn" type="submit">Simpan sertifikat eksternal</button></div>
            </form>
        @endif
        @if($errors->any())<div class="mt-4 rounded-sm border border-danger bg-danger-soft p-3 text-sm text-danger" role="alert">{{ $errors->first() }}</div>@endif
    </section>

    <section class="sipora-card">
        <h2 class="text-lg font-extrabold">Catatan tersimpan</h2>
        <div class="mt-4 space-y-4">
            @forelse($items as $item)
                @php
                    $recordType = match($section) {'education'=>'education','experience'=>'organization','achievements'=>'achievement',default=>'certificate'};
                    $evidenceAttached = $section === 'training' ? filled($item->file_path) : ($section === 'achievements' ? filled($item->evidence_path) : $evidences->has($recordType.':'.bin2hex($item->getKey())));
                    $itemTitle = match($section) {'education'=>$item->institution_name,'experience'=>$item->organization_name,'achievements'=>$item->title,default=>$item->name};
                @endphp
                <article class="rounded-sm border border-border bg-surface-soft p-4">
                    <div class="flex flex-wrap items-start justify-between gap-3"><div><h3 class="font-extrabold">{{ $itemTitle }}</h3><p class="mt-1 text-sm text-text-secondary">{{ match($section) {'education'=>strtoupper($item->education_level),'experience'=>$item->role_title,'achievements'=>$item->issuer_name,default=>$item->issuer_name} }}</p></div><div class="flex flex-wrap gap-2"><span class="status-badge">{{ $section === 'achievements' && $item->verification_status === 'verified' ? 'SIPORA Verified' : 'Self-reported' }}</span><span class="status-badge">{{ $evidenceAttached ? 'Evidence attached' : 'Tanpa bukti' }}</span></div></div>
                    <div class="mt-4 flex flex-wrap gap-2">
                        @if($section === 'training' && $evidenceAttached)<a class="btn-secondary !min-h-9" href="{{ route('youth.portfolio.external-certificates.file', $item) }}">Lihat bukti privat</a>
                        @elseif($section !== 'training')<a class="btn-secondary !min-h-9" href="{{ route('youth.portfolio.evidence.show', [$recordType, $item->uuid()]) }}">Detail / bukti privat</a>@endif
                    </div>
                    @if($section !== 'training')
                    <details class="mt-4 border-t border-border pt-3"><summary class="cursor-pointer font-bold">Edit catatan</summary>
                        <form class="mt-3 grid gap-3 sm:grid-cols-2" method="post" enctype="multipart/form-data" action="{{ match($section) {'education'=>route('youth.educations.update',$item->uuid()),'experience'=>route('youth.organization-experiences.update',$item->uuid()),default=>route('youth.achievements.update',$item->uuid())} }}">@csrf @method('PUT')
                            @if($section === 'education')<label><span class="field-label">Jenjang</span><input class="field" name="education_level" value="{{ $item->education_level }}" required></label><label><span class="field-label">Institusi</span><input class="field" name="institution_name" value="{{ $item->institution_name }}" required></label><label><span class="field-label">Bidang</span><input class="field" name="field_of_study" value="{{ $item->field_of_study }}"></label>
                            @elseif($section === 'experience')<label><span class="field-label">Organisasi</span><input class="field" name="organization_name" value="{{ $item->organization_name }}" required></label><label><span class="field-label">Peran</span><input class="field" name="role_title" value="{{ $item->role_title }}"></label>
                            @else<label><span class="field-label">Judul</span><input class="field" name="title" value="{{ $item->title }}" required></label><label><span class="field-label">Pemberi</span><input class="field" name="issuer_name" value="{{ $item->issuer_name }}"></label>@endif
                            @if($section === 'achievements')<label><span class="field-label">Tanggal</span><input class="field" type="date" name="achievement_date" value="{{ $item->achievement_date?->format('Y-m-d') }}"></label>
                            @else<label><span class="field-label">Mulai</span><input class="field" type="date" name="start_date" value="{{ $item->start_date?->format('Y-m-d') }}"></label><label><span class="field-label">Selesai</span><input class="field" type="date" name="end_date" value="{{ $item->end_date?->format('Y-m-d') }}"></label><label class="flex items-center gap-2"><input type="checkbox" name="is_current" value="1" @checked($item->is_current)>Masih berlangsung</label>@endif
                            <label class="sm:col-span-2"><span class="field-label">Deskripsi</span><textarea class="field" name="description" maxlength="1000">{{ $item->description }}</textarea></label>
                            @if($section !== 'education')<label class="sm:col-span-2"><span class="field-label">Ganti / tambahkan sertifikat atau bukti (opsional) · PDF/JPG/PNG, maks. 5 MB</span><input class="field" type="file" name="evidence" accept=".pdf,.jpg,.jpeg,.png"></label>@endif
                            <div class="sm:col-span-2"><button class="btn-secondary" type="submit">Simpan perubahan</button></div>
                        </form>
                    </details>
                    @endif
                    <form class="mt-3" method="post" action="{{ match($section) {'education'=>route('youth.educations.destroy',$item->uuid()),'experience'=>route('youth.organization-experiences.destroy',$item->uuid()),'achievements'=>route('youth.achievements.destroy',$item->uuid()),default=>route('youth.portfolio.external-certificates.destroy',$item)} }}" data-confirm="Hapus catatan ini beserta bukti privatnya?">@csrf @method('DELETE')<button class="text-sm font-bold text-danger" type="submit">Hapus</button></form>
                </article>
            @empty
                <p class="text-sm text-text-muted">Belum ada catatan {{ strtolower($title) }}.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection
