@props(['profile'])
@php($initials = collect(explode(' ', $profile['name']))->filter()->take(2)->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))->implode(''))
<article {{ $attributes->class('group flex h-full flex-col overflow-hidden rounded-2xl border border-border bg-white shadow-xs transition duration-200 hover:-translate-y-0.5 hover:border-primary-200 hover:shadow-sm') }}>
    <div class="flex gap-4 p-5">
        <div class="grid size-20 shrink-0 place-items-center overflow-hidden rounded-2xl bg-gradient-to-br from-primary-100 to-primary-300 text-lg font-black text-primary" aria-hidden="{{ $profile['photo_url'] ? 'true' : 'false' }}">
            @if($profile['photo_url'])<img class="h-full w-full object-cover" src="{{ $profile['photo_url'] }}" alt="Foto {{ $profile['name'] }}" loading="lazy">@else<span>{{ $initials ?: 'YP' }}</span>@endif
        </div>
        <div class="min-w-0 flex-1"><h2 class="text-lg font-bold leading-6 text-text-primary">{{ $profile['name'] }}</h2>@if($profile['headline'])<p class="mt-1 text-sm font-semibold text-primary">{{ $profile['headline'] }}</p>@endif @if($profile['domicile'])<p class="mt-1 flex items-center gap-1.5 text-xs text-text-muted"><x-ui.icon name="map-pin" class="size-3.5" />{{ $profile['domicile'] }}, Pemalang</p>@endif</div>
    </div>
    <div class="flex flex-1 flex-col px-5 pb-5">@if($profile['bio'])<p class="line-clamp-3 text-sm leading-6 text-text-secondary">{{ $profile['bio'] }}</p>@endif
        @if($profile['skills'])<div class="mt-4 flex flex-wrap gap-2">@foreach($profile['skills'] as $skill)<span class="rounded-full bg-primary-50 px-2.5 py-1 text-xs font-semibold text-primary">{{ $skill }}</span>@endforeach</div>@endif
        @if(!is_null($profile['activities_count']) || !is_null($profile['communities_count']) || !is_null($profile['certificates_count']))<dl class="mt-5 flex flex-wrap gap-x-5 gap-y-2 border-t border-border pt-4 text-xs text-text-secondary">@if(!is_null($profile['activities_count']))<div><dt class="sr-only">Activity terverifikasi</dt><dd><strong class="text-text-primary">{{ $profile['activities_count'] }}</strong> Activity</dd></div>@endif @if(!is_null($profile['communities_count']))<div><dt class="sr-only">Community publik</dt><dd><strong class="text-text-primary">{{ $profile['communities_count'] }}</strong> Community</dd></div>@endif @if(!is_null($profile['certificates_count']))<div><dt class="sr-only">Sertifikat terverifikasi</dt><dd><strong class="text-text-primary">{{ $profile['certificates_count'] }}</strong> Sertifikat</dd></div>@endif</dl>@endif
        <a href="{{ $profile['url'] }}" class="mt-5 inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-primary-200 px-4 text-sm font-bold text-primary transition hover:bg-primary-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">Lihat Portfolio <x-ui.icon name="chevron-right" class="size-4" /></a>
    </div>
</article>
