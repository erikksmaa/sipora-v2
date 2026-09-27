@extends('layouts.youth')
@section('title', 'Keahlian Saya · SIPORA')
@section('content')
<div class="mx-auto max-w-3xl">
    <a class="text-sm font-bold text-[#243378]" href="{{ route('youth.profile.show') }}">← Kembali ke profil</a>
    <section class="sipora-card mt-4">
        <p class="text-xs font-bold uppercase tracking-wider text-orange-600">Pengembangan Diri</p>
        <h1 class="mt-2 text-3xl font-extrabold">Keahlian yang kamu miliki</h1>
        <p class="mt-2 text-slate-600">Pilih satu atau lebih keahlian. Kamu bisa menambahkan keahlian baru nanti.</p>
        <form class="mt-6" method="post" action="{{ route('youth.skills.update') }}">
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
            <div class="mt-6 flex justify-end">
                <button class="btn" type="submit">Simpan keahlian</button>
            </div>
        </form>
    </section>
</div>
@endsection
