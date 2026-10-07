@extends('layouts.youth')
@section('title', 'Keahlian Saya · SIPORA')
@section('content')
<div class="mx-auto max-w-3xl">
    <a class="text-sm font-bold text-[#243378]" href="{{ route('youth.profile.show') }}">← Kembali ke profil</a>
    <section class="sipora-card mt-4">
        <p class="text-xs font-bold uppercase tracking-wider text-orange-600">Pengembangan Diri</p>
        <h1 class="mt-2 text-3xl font-extrabold">Keahlian yang kamu miliki</h1>
        <p class="mt-2 text-slate-600">Pilih satu atau lebih keahlian. Kamu bisa menambahkan keahlian baru nanti.</p>
        <form class="mt-6" method="post" action="{{ route('youth.skills.update') }}" x-data="{ other: {{ old('custom_skill') ? 'true' : 'false' }} }">
            @csrf @method('PUT')
            @if($skills->isEmpty())
                <p class="rounded-xl bg-slate-50 p-6 text-center text-slate-500">Daftar keahlian belum tersedia. Administrator akan segera mengisi master data.</p>
            @else
                <div class="grid gap-3 sm:grid-cols-2">
                    @foreach($skills as $skill)
                        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 p-4 has-checked:border-[#3346a8] has-checked:bg-[#eef0ff]">
                            <input class="size-5 accent-[#243378]" type="checkbox" name="skills[]" value="{{ $skill->uuid() }}" @checked(in_array($skill->uuid(), old('skills', $selectedSkills)))>
                            <span class="font-semibold">{{ $skill->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('skills') <p class="field-error mt-3">{{ $message }}</p> @enderror
            @endif
            <label class="mt-5 flex items-center gap-3"><input type="checkbox" class="size-5" x-model="other" name="other_selected" value="1"><span class="font-semibold">Lainnya — keahlian belum ada di daftar</span></label>
            <label x-show="other" x-cloak class="mt-3 block"><span class="field-label">Masukkan keahlian lainnya</span><input class="field" name="custom_skill" value="{{ old('custom_skill') }}" maxlength="120" :required="other" :disabled="!other">@error('custom_skill')<span class="field-error">{{ $message }}</span>@enderror</label>
            <div class="mt-6 flex justify-end">
                <button class="btn" type="submit">Simpan keahlian</button>
            </div>
        </form>
        @if($customSkills->isNotEmpty())<div class="mt-6 border-t border-border pt-5"><h2 class="font-bold">Keahlian lainnya milikmu</h2><div class="mt-3 flex flex-wrap gap-2">@foreach($customSkills as $tag)<form method="post" action="{{ route('youth.custom-tags.destroy', $tag) }}" data-confirm="Hapus keahlian {{ $tag->name }}?"><span class="interest-chip">{{ $tag->name }} <button class="ml-1" type="submit" aria-label="Hapus keahlian {{ $tag->name }}">×</button></span>@csrf @method('DELETE')</form>@endforeach</div></div>@endif
    </section>
</div>
@endsection
