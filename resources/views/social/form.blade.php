@extends('layouts.public')
@section('title', ($post ? 'Edit post' : 'Buat post').' · SIPORA')
@section('content')
<div class="mx-auto max-w-3xl px-5 py-10"><a class="btn-outline mb-5" href="{{ route('social.index') }}">Kembali ke Feed</a><section class="sipora-card p-5 sm:p-8"><h1 class="font-heading text-2xl font-bold text-text-primary">{{ $post ? 'Edit post' : 'Bagikan update' }}</h1><p class="mt-2 text-sm leading-6 text-text-secondary">Post publik bisa dibaca siapa saja. Hindari NIK, kontak pribadi, dan dokumen identitas. Nama profil privat ditampilkan sebagai alias.</p>
<form class="mt-6 space-y-5" method="post" enctype="multipart/form-data" action="{{ $post ? route('social.update', $post) : route('social.store') }}">@csrf @if($post) @method('PUT') @endif
@if(!$post)<label class="block text-sm font-bold text-text-primary">Bagikan sebagai<select name="organization_id" class="field mt-2 w-full"><option value="">Youth · akun saya</option>@foreach($communities as $community)<option value="{{ $community->slug }}" @selected(old('organization_id') === $community->slug)>{{ $community->name }}</option>@endforeach</select></label>@endif
<label class="block text-sm font-bold text-text-primary">Update kamu<textarea name="body" class="field mt-2 min-h-40 w-full" maxlength="3000" required>{{ old('body', $post?->body) }}</textarea></label>
<label class="block text-sm font-bold text-text-primary">Visibilitas<select name="visibility" class="field mt-2 w-full"><option value="public" @selected(old('visibility', $post?->visibility) !== 'private')>Publik</option><option value="private" @selected(old('visibility', $post?->visibility) === 'private')>Privat · hanya saya / pengelola Community</option></select></label>
<label class="block text-sm font-bold text-text-primary">Gambar opsional<input class="field mt-2 w-full" type="file" name="image" accept="image/jpeg,image/png,image/webp"><span class="mt-2 block text-xs font-normal text-text-muted">JPG, PNG, WEBP · maksimal 3 MB dan 4096 × 4096 piksel.</span></label>
@if($post?->image_path)<label class="flex items-center gap-2 text-sm text-text-primary"><input name="remove_image" type="checkbox" value="1">Hapus gambar saat ini</label>@endif
<button class="btn-primary" type="submit">{{ $post ? 'Simpan perubahan' : 'Bagikan post' }}</button></form></section></div>
@endsection
