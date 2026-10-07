@extends('layouts.public')
@section('title', 'Kontak · SIPORA')
@section('meta_description', 'Informasi resmi pengelola platform SIPORA Kabupaten Pemalang.')
@section('canonical', route('contact'))
@section('content')
<x-public.page-intro eyebrow="Informasi Resmi" title="Kontak SIPORA"
    description="Informasi pengelola platform dan kanal layanan resmi yang telah dikonfigurasi." variant="contact" />
<section class="bg-surface-soft px-5 py-16">
    <div class="mx-auto max-w-6xl">
        <div class="grid gap-8 lg:grid-cols-[.8fr_1.2fr]">
            <article class="rounded-2xl bg-primary-900 p-7 text-white shadow-md"><span
                    class="grid size-12 place-items-center rounded-xl bg-white/10 text-accent-300"><x-ui.icon
                        name="building" /></span><x-media.representative class="mt-5" domain="contact"
                    identity="service" label="Ilustrasi layanan informasi, bukan foto kantor atau peta lokasi" />
                <p class="mt-6 text-xs font-bold uppercase tracking-wider text-accent-300">Pengelola Platform</p>
                <h2 class="font-heading text-white mt-2 text-3xl font-bold">{{ $contact['organization'] }}</h2>
                <p class="mt-4 text-sm leading-7 text-white/75">SIPORA dikelola sebagai platform ekosistem olahraga dan
                    kepemudaan Kabupaten Pemalang.</p>
            </article>
            <div>
                <h2 class="font-heading text-3xl font-bold text-text-primary">Kanal informasi resmi</h2>
                <p class="mt-3 text-text-secondary">Gunakan kanal yang tersedia di bawah. Halaman ini tidak mengirim
                    tiket atau pesan otomatis.</p>
                <div class="mt-7 grid gap-4 sm:grid-cols-2">
                    @php($methods = [['map-pin', 'Alamat', $contact['address'], null], ['mail', 'Email', $contact['email'], $contact['email'] ? 'mailto:' . $contact['email'] : null], ['phone', 'Telepon', $contact['phone'], $contact['phone'] ? 'tel:' . preg_replace('/[^+0-9]/', '', $contact['phone']) : null], ['globe', 'Website', $contact['website'], $contact['website']]])
                    @foreach($methods as [$icon, $label, $value, $href])
                        <article class="rounded-2xl border border-border bg-white p-5 shadow-xs"><span
                                class="grid size-10 place-items-center rounded-xl bg-primary-50 text-primary"><x-ui.icon
                                    :name="$icon" class="size-5" /></span>
                            <h3 class="mt-4 text-sm font-bold text-text-primary">{{ $label }}</h3>@if($value) @if($href)<a
                                    class="mt-1 block break-words text-sm text-primary hover:underline" href="{{ $href }}"
                                @if($label === 'Website') rel="noopener" @endif>{{ $value }}</a>@else<p
                            class="mt-1 text-sm leading-6 text-text-secondary">{{ $value }}</p>@endif @else<p
                                class="mt-1 text-sm text-text-muted">Belum dikonfigurasi.</p>@endif
                    </article>@endforeach
                </div>@if($contact['service_hours'])
                    <article class="mt-4 rounded-2xl border border-border bg-white p-5 shadow-xs">
                        <h3 class="font-bold text-text-primary">Jam layanan</h3>
                        <p class="mt-2 text-sm text-text-secondary">{{ $contact['service_hours'] }}</p>
                </article>@endif
            </div>
        </div>
    </div>
</section>
<section class="px-5 py-14">
    <div
        class="mx-auto flex max-w-6xl flex-col justify-between gap-5 rounded-2xl border border-border bg-white p-7 shadow-xs sm:flex-row sm:items-center">
        <div>
            <h2 class="font-heading text-2xl font-bold text-text-primary">Mencari informasi ekosistem?</h2>
            <p class="mt-2 text-sm text-text-secondary">Gunakan halaman Jelajahi untuk menemukan data publik yang sudah
                tersedia.</p>
        </div><a class="landing-btn-primary" href="{{ route('search.index') }}">Buka Pencarian</a>
    </div>
</section>
@endsection
