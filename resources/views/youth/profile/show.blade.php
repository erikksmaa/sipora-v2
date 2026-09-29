@extends('layouts.youth')
@section('title', 'Profil Saya · SIPORA')
@section('content')
<div class="space-y-6">

{{-- Hero card --}}
<section class="sipora-card">
    <div class="flex flex-col gap-6 sm:flex-row sm:items-center">
        <div class="grid size-28 shrink-0 place-items-center overflow-hidden rounded-full bg-[#eef0ff] text-3xl font-black text-[#243378]">
            @if($user->profile?->profile_photo_path)
                <img class="size-full object-cover" src="{{ route('youth.profile.photo') }}" alt="Foto profil {{ $user->profile->full_name }}">
            @else
                {{ str($user->profile?->full_name ?? $user->name)->substr(0,1)->upper() }}
            @endif
        </div>
        <div class="min-w-0 flex-1">
            <div class="flex flex-wrap items-center gap-2">
                <h1 class="text-3xl font-extrabold">{{ $user->profile?->full_name ?? $user->name }}</h1>
                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $user->identity?->verification_status === 'verified' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                    {{ $user->identity?->verification_status === 'verified' ? 'Youth terverifikasi' : 'Belum terverifikasi' }}
                </span>
            </div>
            <p class="mt-2 text-slate-600">
                {{ $user->profile?->occupation_title ?: 'Tambahkan pekerjaan atau institusi' }}
                @if($user->primaryDomicile) · {{ $user->primaryDomicile->administrativeArea->name }}@endif
            </p>
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach($user->interests as $interest)
                    <span class="interest-chip">{{ $interest->name }}</span>
                @endforeach
                @if($user->interests->isEmpty())
                    <a class="text-sm font-semibold text-[#3346a8]" href="{{ route('youth.interests.edit') }}">+ Tambah minat</a>
                @endif
            </div>
        </div>
        <a class="btn-secondary" href="{{ route('youth.profile.edit') }}">Edit profil</a>
    </div>
</section>

{{-- Profile completion --}}
<x-profile-completion :completion="$completion" />

<div class="grid gap-6 lg:grid-cols-2">

    {{-- Tentang saya --}}
    <section class="sipora-card">
        <h2 class="text-xl font-bold">Tentang saya</h2>
        <p class="mt-3 whitespace-pre-line text-slate-600">{{ $user->profile?->bio ?: 'Bio belum diisi.' }}</p>
        <dl class="mt-6 grid gap-4 text-sm sm:grid-cols-2">
            <div><dt class="font-semibold text-slate-500">Status</dt><dd class="mt-1">{{ $user->profile?->occupation_status ?: 'Belum diisi' }}</dd></div>
            <div><dt class="font-semibold text-slate-500">Kelayakan usia</dt><dd class="mt-1">{{ $eligibility['eligible'] === true ? 'Segmen inti Youth' : ($eligibility['eligible'] === false ? 'Di luar segmen inti' : 'Belum dihitung') }}</dd></div>
        </dl>
    </section>

    {{-- Privasi profil --}}
    <section class="sipora-card">
        <h2 class="text-xl font-bold">Privasi profil</h2>
        <p class="mt-2 text-sm text-slate-600">NIK, dokumen identitas, tanggal lahir lengkap, alamat detail, telepon, dan email selalu privat.</p>
        <form class="mt-5 space-y-3" method="post" action="{{ route('youth.profile.visibility.update') }}">
            @csrf @method('PUT')
            @foreach(['is_profile_public'=>'Publikasikan Portfolio','show_photo'=>'Tampilkan foto','show_bio'=>'Tampilkan bio','show_interests'=>'Tampilkan minat','show_skills'=>'Tampilkan keahlian','show_education'=>'Tampilkan pendidikan','show_organization_experience'=>'Tampilkan pengalaman organisasi mandiri','show_community_membership'=>'Tampilkan keanggotaan Community','show_activity_passport'=>'Tampilkan Activity Passport','show_certificates'=>'Tampilkan sertifikat','show_achievements'=>'Tampilkan prestasi'] as $key=>$label)
                <label class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 p-3">
                    <span class="font-medium">{{ $label }}</span>
                    <input type="hidden" name="{{ $key }}" value="0">
                    <input class="size-5 accent-[#243378]" type="checkbox" name="{{ $key }}" value="1" @checked($user->profileVisibility?->{$key} ?? true)>
                </label>
            @endforeach
            <button class="btn mt-2 w-full" type="submit">Simpan privasi</button>
        </form>
    </section>

</div>

{{-- ====================== ENRICHMENT SECTIONS ====================== --}}

{{-- Keahlian --}}
<section class="sipora-card" id="skills-section">
    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-orange-600">Pengembangan Diri</p>
            <h2 class="mt-1 text-xl font-bold">Keahlian</h2>
        </div>
        <a class="btn-secondary text-sm" href="{{ route('youth.skills.edit') }}">Atur keahlian</a>
    </div>
    @if($user->skills->isNotEmpty())
        <div class="mt-4 flex flex-wrap gap-2">
            @foreach($user->skills as $skill)
                <span class="interest-chip">{{ $skill->name }}</span>
            @endforeach
        </div>
    @else
        <p class="mt-4 text-sm text-slate-500">Belum ada keahlian ditambahkan. <a class="font-semibold text-[#3346a8]" href="{{ route('youth.skills.edit') }}">Tambahkan sekarang →</a></p>
    @endif
</section>

{{-- Riwayat Pendidikan --}}
<section class="sipora-card" id="education-section">
    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-orange-600">Riwayat</p>
            <h2 class="mt-1 text-xl font-bold">Pendidikan</h2>
        </div>
        <button type="button" onclick="document.getElementById('edu-add-form').classList.toggle('hidden')" class="btn-secondary text-sm">+ Tambah</button>
    </div>

    {{-- Add form (hidden by default) --}}
    <div id="edu-add-form" class="mt-4 hidden rounded-xl border border-indigo-200 bg-[#f8f9ff] p-5">
        <h3 class="mb-4 font-bold">Tambah riwayat pendidikan</h3>
        <form method="post" action="{{ route('youth.educations.store') }}" class="space-y-4">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <label><span class="field-label">Jenjang pendidikan</span>
                    <select class="field" name="education_level" required>
                        <option value="">Pilih jenjang</option>
                        @foreach(['sd'=>'SD','smp'=>'SMP','sma_smk'=>'SMA/SMK','d1'=>'D1','d2'=>'D2','d3'=>'D3','d4_s1'=>'D4/S1','s2'=>'S2','s3'=>'S3','other'=>'Lainnya'] as $v=>$l)
                            <option value="{{ $v }}">{{ $l }}</option>
                        @endforeach
                    </select>
                    @error('education_level')<span class="field-error">{{ $message }}</span>@enderror
                </label>
                <label><span class="field-label">Nama institusi</span>
                    <input class="field" name="institution_name" maxlength="180" required>
                    @error('institution_name')<span class="field-error">{{ $message }}</span>@enderror
                </label>
                <label><span class="field-label">Bidang studi <span class="font-normal text-slate-500">(opsional)</span></span>
                    <input class="field" name="field_of_study" maxlength="180">
                </label>
                <label><span class="field-label">Tahun mulai</span>
                    <input class="field" type="date" name="start_date">
                </label>
                <label class="flex items-center gap-3 sm:col-span-2">
                    <input type="checkbox" name="is_current" value="1" class="size-4 accent-[#243378]" id="edu-is-current">
                    <span class="font-medium">Masih berlangsung</span>
                </label>
                <label><span class="field-label">Tahun selesai <span class="font-normal text-slate-500">(kosongkan jika masih berlangsung)</span></span>
                    <input class="field" type="date" name="end_date">
                </label>
            </div>
            <label><span class="field-label">Deskripsi <span class="font-normal text-slate-500">(opsional)</span></span>
                <textarea class="field" name="description" rows="2" maxlength="1000"></textarea>
            </label>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('edu-add-form').classList.add('hidden')" class="btn-secondary">Batal</button>
                <button class="btn" type="submit">Simpan</button>
            </div>
        </form>
    </div>

    {{-- Education list --}}
    @forelse($user->educations as $edu)
        <div class="mt-4 rounded-xl border border-slate-200 p-4" id="edu-{{ $edu->uuid() }}">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="font-bold">{{ $edu->institution_name }}</p>
                    <p class="text-sm text-slate-600">{{ strtoupper($edu->education_level) }}{{ $edu->field_of_study ? ' · '.$edu->field_of_study : '' }}</p>
                    <p class="mt-1 text-xs text-slate-400">
                        {{ $edu->start_date?->format('Y') }}{{ $edu->is_current ? ' – sekarang' : ($edu->end_date ? ' – '.$edu->end_date->format('Y') : '') }}
                    </p>
                    @if($edu->description)<p class="mt-2 text-sm text-slate-600">{{ $edu->description }}</p>@endif
                </div>
                <form method="post" action="{{ route('youth.educations.destroy', $edu->uuid()) }}" onsubmit="return confirm('Hapus riwayat ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-800">Hapus</button>
                </form>
            </div>
        </div>
    @empty
        <p class="mt-4 text-sm text-slate-500">Belum ada riwayat pendidikan. Klik "+ Tambah" di atas.</p>
    @endforelse
</section>

{{-- Pengalaman Organisasi --}}
<section class="sipora-card" id="org-section">
    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-orange-600">Riwayat</p>
            <h2 class="mt-1 text-xl font-bold">Pengalaman Organisasi</h2>
        </div>
        <button type="button" onclick="document.getElementById('org-add-form').classList.toggle('hidden')" class="btn-secondary text-sm">+ Tambah</button>
    </div>

    {{-- Add form --}}
    <div id="org-add-form" class="mt-4 hidden rounded-xl border border-indigo-200 bg-[#f8f9ff] p-5">
        <h3 class="mb-4 font-bold">Tambah pengalaman organisasi</h3>
        <form method="post" action="{{ route('youth.organization-experiences.store') }}" class="space-y-4">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <label><span class="field-label">Nama organisasi</span>
                    <input class="field" name="organization_name" maxlength="180" required>
                    @error('organization_name')<span class="field-error">{{ $message }}</span>@enderror
                </label>
                <label><span class="field-label">Jabatan / peran <span class="font-normal text-slate-500">(opsional)</span></span>
                    <input class="field" name="role_title" maxlength="160">
                </label>
                <label><span class="field-label">Mulai</span>
                    <input class="field" type="date" name="start_date">
                </label>
                <label class="flex items-center gap-3">
                    <input type="checkbox" name="is_current" value="1" class="size-4 accent-[#243378]">
                    <span class="font-medium">Masih aktif</span>
                </label>
                <label><span class="field-label">Selesai <span class="font-normal text-slate-500">(kosongkan jika masih aktif)</span></span>
                    <input class="field" type="date" name="end_date">
                </label>
            </div>
            <label><span class="field-label">Deskripsi <span class="font-normal text-slate-500">(opsional)</span></span>
                <textarea class="field" name="description" rows="2" maxlength="1000"></textarea>
            </label>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('org-add-form').classList.add('hidden')" class="btn-secondary">Batal</button>
                <button class="btn" type="submit">Simpan</button>
            </div>
        </form>
    </div>

    {{-- List --}}
    @forelse($user->organizationExperiences as $exp)
        <div class="mt-4 rounded-xl border border-slate-200 p-4">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="font-bold">{{ $exp->organization_name }}</p>
                    @if($exp->role_title)<p class="text-sm text-slate-600">{{ $exp->role_title }}</p>@endif
                    <p class="mt-1 text-xs text-slate-400">
                        {{ $exp->start_date?->format('Y') }}{{ $exp->is_current ? ' – sekarang' : ($exp->end_date ? ' – '.$exp->end_date->format('Y') : '') }}
                    </p>
                    @if($exp->description)<p class="mt-2 text-sm text-slate-600">{{ $exp->description }}</p>@endif
                </div>
                <form method="post" action="{{ route('youth.organization-experiences.destroy', $exp->uuid()) }}" onsubmit="return confirm('Hapus pengalaman ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-800">Hapus</button>
                </form>
            </div>
        </div>
    @empty
        <p class="mt-4 text-sm text-slate-500">Belum ada pengalaman organisasi. Klik "+ Tambah" di atas.</p>
    @endforelse
</section>

{{-- Penghargaan & Prestasi --}}
<section class="sipora-card" id="achievement-section">
    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-orange-600">Portofolio</p>
            <h2 class="mt-1 text-xl font-bold">Penghargaan &amp; Prestasi</h2>
        </div>
        <button type="button" onclick="document.getElementById('ach-add-form').classList.toggle('hidden')" class="btn-secondary text-sm">+ Tambah</button>
    </div>

    {{-- Add form --}}
    <div id="ach-add-form" class="mt-4 hidden rounded-xl border border-indigo-200 bg-[#f8f9ff] p-5">
        <h3 class="mb-4 font-bold">Tambah penghargaan / prestasi</h3>
        <form method="post" action="{{ route('youth.achievements.store') }}" class="space-y-4">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <label class="sm:col-span-2"><span class="field-label">Judul penghargaan / prestasi</span>
                    <input class="field" name="title" maxlength="180" required>
                    @error('title')<span class="field-error">{{ $message }}</span>@enderror
                </label>
                <label><span class="field-label">Pemberi penghargaan <span class="font-normal text-slate-500">(opsional)</span></span>
                    <input class="field" name="issuer_name" maxlength="180">
                </label>
                <label><span class="field-label">Tanggal <span class="font-normal text-slate-500">(opsional)</span></span>
                    <input class="field" type="date" name="achievement_date">
                </label>
            </div>
            <label><span class="field-label">Deskripsi <span class="font-normal text-slate-500">(opsional)</span></span>
                <textarea class="field" name="description" rows="2" maxlength="1000"></textarea>
            </label>
            <p class="text-xs text-slate-500">Unggahan bukti prestasi akan didukung pada fase selanjutnya. Kamu bisa menambahkan catatan deskripsi untuk saat ini.</p>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('ach-add-form').classList.add('hidden')" class="btn-secondary">Batal</button>
                <button class="btn" type="submit">Simpan</button>
            </div>
        </form>
    </div>

    {{-- List --}}
    @forelse($user->achievements as $ach)
        <div class="mt-4 rounded-xl border border-slate-200 p-4">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <div class="flex items-center gap-2">
                        <p class="font-bold">{{ $ach->title }}</p>
                        <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $ach->verification_status === 'verified' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                            {{ $ach->verification_status === 'verified' ? 'Terverifikasi' : 'Self-reported' }}
                        </span>
                    </div>
                    @if($ach->issuer_name)<p class="text-sm text-slate-600">{{ $ach->issuer_name }}</p>@endif
                    @if($ach->achievement_date)<p class="mt-1 text-xs text-slate-400">{{ $ach->achievement_date->format('d M Y') }}</p>@endif
                    @if($ach->description)<p class="mt-2 text-sm text-slate-600">{{ $ach->description }}</p>@endif
                </div>
                <form method="post" action="{{ route('youth.achievements.destroy', $ach->uuid()) }}" onsubmit="return confirm('Hapus prestasi ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-800">Hapus</button>
                </form>
            </div>
        </div>
    @empty
        <p class="mt-4 text-sm text-slate-500">Belum ada prestasi dicatat. Klik "+ Tambah" di atas.</p>
    @endforelse
</section>

</div>
@endsection
