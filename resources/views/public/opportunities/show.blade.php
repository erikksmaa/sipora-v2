@extends('layouts.public')
@section('title', $opportunity->title.' · SIPORA')
@section('content')
<article class="mx-auto max-w-5xl overflow-hidden rounded-3xl bg-white shadow-xl">
    <div class="bg-[#18245c] p-6 text-white md:p-10"><div class="flex flex-wrap items-center gap-3"><span class="rounded-full bg-orange-400/20 px-3 py-1 text-xs font-black text-orange-200">{{ $opportunity->category->name }}</span><span class="text-sm text-indigo-200">Peluang eksternal terkurasi</span></div><h1 class="mt-5 text-4xl font-black sm:text-5xl">{{ $opportunity->title }}</h1><p class="mt-4 text-lg text-indigo-100">{{ $opportunity->provider_name }}</p></div>
    <div class="p-6 md:p-10">
        <div class="grid gap-4 rounded-2xl bg-[#eef0ff] p-6 sm:grid-cols-2 lg:grid-cols-3"><div><p class="text-xs font-black uppercase text-slate-500">Lokasi</p><p class="mt-1 font-bold text-[#18245c]">{{ $opportunity->location_text ?: $opportunity->administrativeArea?->name ?: 'Tidak ditentukan' }}</p></div><div><p class="text-xs font-black uppercase text-slate-500">Batas pendaftaran</p><p class="mt-1 font-bold text-[#18245c]">{{ $opportunity->deadline_at?->translatedFormat('d M Y, H:i') ?? 'Tidak ditentukan' }}</p></div><div><p class="text-xs font-black uppercase text-slate-500">Periode</p><p class="mt-1 font-bold text-[#18245c]">{{ $opportunity->starts_at?->translatedFormat('d M Y') ?? 'Fleksibel' }}@if ($opportunity->ends_at) – {{ $opportunity->ends_at->translatedFormat('d M Y') }}@endif</p></div></div>
        <section class="mt-8"><p class="text-xs font-black uppercase tracking-wider text-orange-600">Tentang peluang</p><p class="mt-3 whitespace-pre-line text-lg leading-8 text-slate-700">{{ $opportunity->description }}</p></section>
        <div class="mt-8 flex flex-wrap gap-3"><a class="landing-btn-primary" href="{{ $opportunity->external_url }}" target="_blank" rel="noopener noreferrer external">Daftar / Lihat Informasi ↗</a>@auth @role('youth')<form method="POST" action="{{ $bookmarked ? route('youth.opportunities.bookmark.destroy', $opportunity) : route('youth.opportunities.bookmark', $opportunity) }}">@csrf @if ($bookmarked) @method('DELETE') @endif<button class="landing-btn-secondary" type="submit">{{ $bookmarked ? 'Hapus dari tersimpan' : 'Simpan Opportunity' }}</button></form>@endrole @endauth</div>
        <p class="mt-5 text-sm text-slate-500">SIPORA menyediakan informasi terkurasi. Proses pendaftaran dan ketentuan akhir berada pada penyedia Opportunity.</p>
    </div>
</article>
@endsection
