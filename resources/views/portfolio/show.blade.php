@extends($portfolio['is_owner'] ? 'layouts.youth' : 'layouts.public')
@section('title', $portfolio['identity']['name'].' · Youth Portfolio SIPORA')
@if(!$portfolio['is_owner'])
@section('meta_description', 'Youth Portfolio SIPORA milik '.$portfolio['identity']['name'].'.')
@section('canonical', $portfolio['public_url'])
@endif
@section('content')
@php
    $initials = collect(explode(' ', $portfolio['identity']['name']))->filter()->take(2)->map(fn($word) => mb_substr($word, 0, 1))->implode('');
    $hidden = fn(string $section) => $portfolio['is_owner'] && !($portfolio['visibility'][$section] ?? false);
@endphp
<div class="{{ $portfolio['is_owner'] ? '' : 'mx-auto max-w-7xl px-5 py-10' }} space-y-6">
    @if(!$portfolio['is_owner'])<a class="inline-flex items-center gap-2 text-sm font-bold text-primary hover:text-accent" href="{{ route('youth-directory.index') }}"><x-ui.icon name="chevron-left" class="size-4" />Direktori Pemuda</a>@endif
    @if($portfolio['is_owner'])
        <section class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-indigo-100 bg-indigo-50 px-5 py-4 text-sm text-primary">
            <div><strong>Pratinjau Portfolio pemilik.</strong> Bagian bertanda privat tetap terlihat di sini, tetapi tidak tampil kepada publik.</div>
            <div class="flex flex-wrap gap-2">
                <a class="btn-secondary !min-h-10 !px-4" href="{{ route('youth.profile.show') }}">Atur profil & privasi</a>
                @if($portfolio['is_public'] && $portfolio['public_url'])<a class="btn !min-h-10 !px-4" href="{{ $portfolio['public_url'] }}" target="_blank" rel="noopener">Lihat tampilan publik</a>@else<span class="rounded-xl bg-white px-4 py-2 font-semibold text-slate-500">Publikasi nonaktif</span>@endif
            </div>
        </section>
    @endif

    <section class="overflow-hidden rounded-3xl border border-indigo-100 bg-white shadow-sm">
        <div class="relative h-28 overflow-hidden bg-gradient-to-r from-primary-dark via-primary to-interactive sm:h-36"><div class="absolute inset-0 opacity-20" style="background-image:linear-gradient(rgb(255 255 255 / .25) 1px,transparent 1px),linear-gradient(90deg,rgb(255 255 255 / .25) 1px,transparent 1px);background-size:36px 36px"></div></div>
        <div class="px-5 pb-6 sm:px-8 sm:pb-8">
            <div class="-mt-12 flex flex-col gap-5 sm:-mt-14 sm:flex-row sm:items-end">
                <div class="grid size-24 shrink-0 place-items-center overflow-hidden rounded-2xl border-4 border-white bg-orange-100 text-3xl font-black text-orange-700 shadow-md sm:size-28">
                    @if($portfolio['identity']['photo_url'])<img class="h-full w-full object-cover" src="{{ $portfolio['identity']['photo_url'] }}" alt="Foto {{ $portfolio['identity']['name'] }}">@else{{ $initials }}@endif
                </div>
                <div class="min-w-0 flex-1 pb-1 sm:pt-16">
                    <div class="flex flex-wrap items-center gap-2"><h1 class="text-2xl font-black text-text-primary sm:text-3xl">{{ $portfolio['identity']['name'] }}</h1><span class="rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-bold text-primary">Terdata di SIPORA</span></div>
                    @if($portfolio['identity']['headline'])<p class="mt-1 font-semibold text-slate-700">{{ $portfolio['identity']['headline'] }}</p>@endif
                    @if($portfolio['identity']['domicile'])<p class="mt-1 text-sm text-slate-500">Domisili {{ $portfolio['identity']['domicile'] }}, Kabupaten Pemalang</p>@endif
                </div>
                @if($portfolio['is_owner'])<a class="btn-secondary" href="{{ route('youth.profile.edit') }}">Edit profil</a>@endif
            </div>
            @if($portfolio['identity']['bio'])<p class="mt-5 max-w-4xl whitespace-pre-line text-sm leading-7 text-slate-600">{{ $portfolio['identity']['bio'] }}</p>@elseif($portfolio['is_owner'])<p class="mt-5 text-sm text-slate-500">Bio belum diisi. <a class="font-bold text-primary" href="{{ route('youth.profile.edit') }}">Lengkapi profil</a></p>@endif
            @if($portfolio['interests'])<div class="mt-5 flex flex-wrap gap-2">@foreach($portfolio['interests'] as $interest)<span class="interest-chip">{{ $interest }}</span>@endforeach</div>@endif
        </div>
    </section>

    <section class="grid grid-cols-2 gap-3 sm:grid-cols-4" aria-label="Ringkasan Portfolio">
        @foreach(['activities'=>['Activity selesai','activity'],'communities'=>['Community aktif','building'],'certificates'=>['Sertifikat','award'],'achievements'=>['Prestasi','trophy']] as $key=>[$label,$icon])
            <div class="rounded-2xl border border-indigo-100 bg-white p-4 shadow-card"><div class="flex items-center gap-3"><span class="grid size-10 place-items-center rounded-xl bg-indigo-50 text-primary"><x-ui.icon :name="$icon" class="size-5" /></span><div><p class="font-heading text-3xl font-bold leading-none text-primary">{{ $portfolio['summary'][$key] }}</p><p class="mt-1 text-xs font-semibold text-slate-500">{{ $label }}</p></div></div></div>
        @endforeach
    </section>

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.45fr)_minmax(300px,.55fr)]">
        <div class="min-w-0 space-y-6">
            @if($portfolio['activities'] || $portfolio['is_owner'])
            <section class="sipora-card">
                <div class="flex flex-wrap items-center justify-between gap-3"><div><p class="text-xs font-extrabold uppercase tracking-wider text-orange-600">Rekam jejak resmi</p><h2 class="mt-1 text-2xl font-black text-text-primary">Pengalaman Terverifikasi SIPORA</h2></div><x-portfolio.provenance-badge verified />@if($hidden('activity_passport'))<span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">Privat</span>@endif</div>
                <div class="mt-5 space-y-4">@forelse($portfolio['activities'] as $activity)
                    <article class="rounded-2xl border border-indigo-100 bg-surface-muted p-5"><div class="flex flex-wrap items-start justify-between gap-3"><div><p class="text-xs font-bold uppercase tracking-wider text-interactive">{{ $activity['category'] ?: 'Activity' }}</p><h3 class="mt-1 text-lg font-black text-text-primary">@if($activity['url'])<a class="hover:underline" href="{{ $activity['url'] }}">{{ $activity['title'] }}</a>@else{{ $activity['title'] }}@endif</h3><p class="mt-1 text-sm text-slate-600">{{ $activity['organizer'] }}</p></div><span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">Selesai</span></div><dl class="mt-4 grid gap-2 text-sm sm:grid-cols-2"><div><dt class="text-slate-500">Peran</dt><dd class="font-bold capitalize">{{ $activity['role'] }}</dd></div><div><dt class="text-slate-500">Periode</dt><dd class="font-bold">{{ $activity['period'] }}</dd></div></dl>@if($activity['certificate'])<div class="mt-4 flex flex-wrap items-center justify-between gap-3 rounded-xl bg-white p-3 text-sm"><span class="font-semibold text-slate-600">Sertifikat {{ $activity['certificate']['number'] }}</span><a class="font-bold text-primary" href="{{ $activity['certificate']['verify_url'] }}">Verifikasi sertifikat →</a></div>@endif</article>
                @empty<p class="rounded-xl border-2 border-dashed border-slate-200 p-8 text-center text-sm text-slate-500">Belum ada Activity yang diselesaikan. @if($portfolio['is_owner'])Activity akan muncul setelah partisipasi dinyatakan selesai oleh pengelola.@endif</p>@endforelse</div>
            </section>
            @endif

            @if($portfolio['communities'] || $portfolio['is_owner'])
            <section class="sipora-card"><div class="flex flex-wrap items-center justify-between gap-3"><div><p class="text-xs font-extrabold uppercase tracking-wider text-orange-600">Keterlibatan kontekstual</p><h2 class="mt-1 text-2xl font-black text-text-primary">Community SIPORA</h2></div><x-portfolio.provenance-badge verified />@if($hidden('community_membership'))<span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">Privat</span>@endif</div>
                <div class="mt-5 grid gap-3 sm:grid-cols-2">@forelse($portfolio['communities'] as $community)<a class="rounded-2xl border border-slate-200 p-4 transition hover:border-indigo-300 hover:bg-indigo-50" href="{{ $community['url'] }}"><h3 class="font-black text-text-primary">{{ $community['name'] }}</h3><p class="mt-1 text-sm text-slate-600">{{ $community['position'] ?: ucfirst($community['role']) }}</p><p class="mt-2 text-xs font-bold text-emerald-700">Keanggotaan aktif{{ $community['since'] ? ' sejak '.$community['since'] : '' }}</p></a>@empty<p class="text-sm text-slate-500">Belum ada keanggotaan Community aktif.</p>@endforelse</div>
            </section>
            @endif

            @if($portfolio['certificates'] || $portfolio['is_owner'])
            <section class="sipora-card"><div class="flex flex-wrap items-center justify-between gap-3"><div><p class="text-xs font-extrabold uppercase tracking-wider text-orange-600">Kredensial resmi</p><h2 class="mt-1 text-2xl font-black text-text-primary">Sertifikat SIPORA</h2></div>@if($hidden('certificates'))<span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">Privat</span>@endif</div>
                <div class="mt-5 grid gap-4 sm:grid-cols-2">@forelse($portfolio['certificates'] as $certificate)<article class="rounded-2xl border border-indigo-100 bg-indigo-50 p-5"><x-portfolio.provenance-badge verified /><h3 class="mt-3 font-black text-text-primary">{{ $certificate['name'] }}</h3><p class="mt-1 text-sm text-slate-600">{{ $certificate['issuer'] }}</p><p class="mt-3 break-all font-mono text-xs text-slate-500">{{ $certificate['number'] }}</p><p class="mt-1 text-xs text-slate-500">Terbit {{ $certificate['issued_at'] }}</p><div class="mt-4 flex flex-wrap gap-3"><a class="font-bold text-primary" href="{{ $certificate['verify_url'] }}">Verifikasi publik →</a>@if($certificate['owner_url'])<a class="font-bold text-orange-600" href="{{ $certificate['owner_url'] }}">Buka sertifikat</a>@endif</div></article>@empty<p class="text-sm text-slate-500">Belum ada sertifikat SIPORA.</p>@endforelse</div>
            </section>
            @endif
        </div>

        <aside class="min-w-0 space-y-6">
            @if($portfolio['skills'] || $portfolio['is_owner'])<section class="sipora-card"><div class="flex items-center justify-between gap-3"><h2 class="text-xl font-black text-text-primary">Keahlian</h2><x-portfolio.provenance-badge />@if($hidden('skills'))<span class="text-xs font-bold text-amber-700">Privat</span>@endif</div><div class="mt-4 flex flex-wrap gap-2">@forelse($portfolio['skills'] as $skill)<span class="interest-chip">{{ $skill }}</span>@empty<p class="text-sm text-slate-500">Belum ada keahlian. @if($portfolio['is_owner'])<a class="font-bold text-primary" href="{{ route('youth.skills.edit') }}">Tambahkan</a>@endif</p>@endforelse</div></section>@endif

            @if($portfolio['educations'] || $portfolio['is_owner'])<section class="sipora-card"><div class="flex flex-wrap items-center justify-between gap-2"><h2 class="text-xl font-black text-text-primary">Pendidikan</h2><x-portfolio.provenance-badge />@if($hidden('education'))<span class="text-xs font-bold text-amber-700">Privat</span>@endif</div><div class="mt-4 space-y-4">@forelse($portfolio['educations'] as $education)<article class="border-l-2 border-indigo-200 pl-4"><h3 class="font-bold">{{ $education['institution'] }}</h3><p class="text-sm text-slate-600">{{ $education['level'] }}{{ $education['field'] ? ' · '.$education['field'] : '' }}</p><p class="mt-1 text-xs text-slate-500">{{ $education['period'] }}</p></article>@empty<p class="text-sm text-slate-500">Belum ada riwayat pendidikan.</p>@endforelse</div></section>@endif

            @if($portfolio['organization_experiences'] || $portfolio['is_owner'])<section class="sipora-card"><div class="flex flex-wrap items-center justify-between gap-2"><h2 class="text-xl font-black text-text-primary">Pengalaman Organisasi Mandiri</h2><x-portfolio.provenance-badge />@if($hidden('organization_experience'))<span class="text-xs font-bold text-amber-700">Privat</span>@endif</div><div class="mt-4 space-y-4">@forelse($portfolio['organization_experiences'] as $experience)<article><h3 class="font-bold">{{ $experience['organization'] }}</h3>@if($experience['role'])<p class="text-sm text-slate-600">{{ $experience['role'] }}</p>@endif<p class="mt-1 text-xs text-slate-500">{{ $experience['period'] }}</p></article>@empty<p class="text-sm text-slate-500">Belum ada pengalaman organisasi mandiri.</p>@endforelse</div></section>@endif

            @if($portfolio['achievements'] || $portfolio['is_owner'])<section class="sipora-card"><div class="flex flex-wrap items-center justify-between gap-2"><h2 class="text-xl font-black text-text-primary">Prestasi</h2>@if($hidden('achievements'))<span class="text-xs font-bold text-amber-700">Privat</span>@endif</div><div class="mt-4 space-y-4">@forelse($portfolio['achievements'] as $achievement)<article class="rounded-xl bg-slate-50 p-4"><x-portfolio.provenance-badge :verified="$achievement['provenance']==='verified'" /><h3 class="mt-2 font-bold">{{ $achievement['title'] }}</h3>@if($achievement['issuer'])<p class="text-sm text-slate-600">{{ $achievement['issuer'] }}</p>@endif
            @if($achievement['date'])<p class="mt-1 text-xs text-slate-500">{{ $achievement['date'] }}</p>@endif</article>@empty<p class="text-sm text-slate-500">Belum ada prestasi.</p>@endforelse</div></section>@endif

            <section class="rounded-2xl bg-primary p-5 text-white"><h2 class="font-black">Tentang validasi Portfolio</h2><p class="mt-2 text-sm leading-6 text-indigo-100">Label terverifikasi berasal dari catatan Community, Activity, dan sertifikat resmi SIPORA. Keahlian, pendidikan, pengalaman organisasi mandiri, dan sebagian prestasi diisi sendiri oleh pemilik.</p></section>
        </aside>
    </div>
</div>
@endsection
