@extends('layouts.admin')
@section('title', 'Password Sementara · SIPORA')
@section('content')
<section class="sipora-card mx-auto max-w-2xl space-y-5" x-data="{copied:false}" x-init="window.addEventListener('pagehide',()=>{$refs.temporaryPassword.value=''})">
    <h1 class="text-2xl font-bold">Password sementara berhasil dibuat.</h1>
    <p class="text-sm text-text-secondary">Akun: <strong>{{ $user->name }}</strong>. Pengguna wajib mengganti password setelah login. Password ini tidak akan dapat dilihat kembali setelah halaman ditutup.</p>
    <label class="block"><span class="field-label">Password sementara</span><input x-ref="temporaryPassword" class="field font-mono" type="text" readonly autocomplete="off" value="{{ $temporaryPassword }}"></label>
    <p class="text-xs text-text-secondary">Sampaikan hanya kepada pemilik akun melalui saluran yang aman. Jangan bagikan melalui komentar atau log aplikasi.</p>
    <div class="flex flex-wrap gap-3"><button type="button" class="btn-primary" @click="navigator.clipboard.writeText($refs.temporaryPassword.value).then(()=>copied=true)"><span x-text="copied?'Tersalin':'Salin Password'">Salin Password</span></button><a class="btn-outline" href="{{ route('admin.internal-users.show',$user) }}" @click="$refs.temporaryPassword.value=''">Tutup</a></div>
</section>
@endsection
