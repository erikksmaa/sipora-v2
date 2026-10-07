@extends('layouts.youth')
@section('title', 'Edit Profil · SIPORA')
@section('content')
<div class="mx-auto max-w-4xl space-y-5">
    <x-workspace.page-header eyebrow="Profil" title="Perkenalkan dirimu" description="Profil berisi informasi presentasi diri. Data lahir, domisili, dan kontak dapat diperbarui di Biodata." />
    <section class="sipora-card">
        <form action="{{ route('youth.profile.update') }}" method="post" enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PUT')
            <label><span class="field-label">Status saat ini</span><select class="field" name="occupation_status" required><option value="">Pilih</option>@foreach(['student'=>'Pelajar','university_student'=>'Mahasiswa','worker'=>'Pekerja','entrepreneur'=>'Wirausaha','unemployed'=>'Belum bekerja','other'=>'Lainnya'] as $value=>$label)<option value="{{ $value }}" @selected(old('occupation_status', $user->profile?->occupation_status)===$value)>{{ $label }}</option>@endforeach</select>@error('occupation_status')<span class="field-error">{{ $message }}</span>@enderror</label>
            <label><span class="field-label">Pekerjaan / institusi <span class="font-normal">(opsional)</span></span><input class="field" name="occupation_title" value="{{ old('occupation_title', $user->profile?->occupation_title) }}" maxlength="160">@error('occupation_title')<span class="field-error">{{ $message }}</span>@enderror</label>
            <label><span class="field-label">Bio singkat</span><textarea class="field min-h-32" name="bio" maxlength="2000" required placeholder="Ceritakan minat dan tujuanmu">{{ old('bio', $user->profile?->bio) }}</textarea>@error('bio')<span class="field-error">{{ $message }}</span>@enderror</label>
            <label><span class="field-label">Foto profil publik <span class="font-normal">(opsional, maks. 2 MB)</span></span><input class="field" type="file" name="profile_photo" accept="image/jpeg,image/png,image/webp">@error('profile_photo')<span class="field-error">{{ $message }}</span>@enderror</label>
            <div class="flex flex-wrap justify-between gap-3"><a class="btn-secondary" href="{{ route('youth.biodata.edit') }}">Lihat Biodata</a><button class="btn" type="submit">Simpan profil</button></div>
        </form>
    </section>
</div>
@endsection
