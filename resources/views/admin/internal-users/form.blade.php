@extends('layouts.admin')
@section('title', ($target ? 'Edit' : 'Tambah').' Akun Internal · SIPORA')
@section('content')
<div class="mx-auto max-w-2xl space-y-6">
    <x-ui.page-header eyebrow="Pengguna Internal" :title="$target ? 'Edit Akun Internal' : 'Tambah Akun Internal'" description="Role akun existing tidak dapat diubah melalui formulir ini." />
    <form method="POST" action="{{ $target ? route('admin.internal-users.update',$target) : route('admin.internal-users.store') }}" class="sipora-card space-y-5" x-data="{role:@js(old('role',$target?->hasRole('admin') ? 'admin' : 'verifier'))}" @if(!$target) data-confirm="Buat akun internal dengan role yang dipilih? Pengguna wajib mengganti password sementara setelah login." data-confirm-title="Buat akun internal?" @endif>
        @csrf @if($target) @method('PATCH') @endif
        <label class="block"><span class="field-label">Nama</span><input class="field" name="name" value="{{ old('name',$target?->name) }}" maxlength="255" required></label>
        <label class="block"><span class="field-label">Email</span><input class="field" name="email" type="email" value="{{ old('email',$target?->email) }}" maxlength="255" required></label>
        @if(!$target)<label class="block"><span class="field-label">Role</span><select class="field" name="role" x-model="role"><option value="verifier">Verifier</option><option value="admin">Administrator</option></select></label>@else<p class="text-sm text-text-secondary">Role tetap: {{ $target->hasRole('admin')?'Administrator':'Verifier' }}. Perubahan email menghapus status verifikasi email dan mengakhiri sesi akun tersebut.</p>@endif
        <label class="block" x-show="role==='admin'"><span class="field-label">Konfirmasi password Administrator Anda</span><x-password-input class="field" name="admin_password" autocomplete="current-password" maxlength="72" x-bind:disabled="role!=='admin'" x-bind:required="role==='admin'" /></label>
        @if(!$target)<p class="text-sm text-text-secondary">Password sementara acak ditampilkan sekali setelah akun dibuat. Pengguna menentukan password permanennya sendiri. Email akun belum terverifikasi sampai tautan verifikasi dibuka.</p>@endif
        <div class="flex flex-wrap gap-3"><button class="btn-primary">{{ $target?'Simpan Perubahan':'Buat Akun' }}</button><a class="btn-outline" href="{{ route('admin.internal-users.index') }}">Kembali</a></div>
    </form>
</div>
@endsection
