@props(['author'])
@php
    $public = $author->profileVisibility?->is_profile_public && filled($author->profile?->public_slug) && filled($author->profile?->full_name);
    $name = \App\Support\ForumDisplayName::for($author);
    $photo = $public && $author->profileVisibility?->show_photo && filled($author->profile?->profile_photo_path);
    $initials = $public ? collect(explode(' ', $name))->filter()->take(2)->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))->implode('') : 'PS';
@endphp
@if($public)<a class="author-identity card-independent text-text-primary hover:text-interactive" href="{{ route('portfolio.show', $author->profile->public_slug) }}" aria-label="Lihat Portfolio {{ $name }}">@else<span class="author-identity text-text-secondary">@endif
    <span class="author-avatar" aria-hidden="true">@if($photo)<img data-media-fallback src="{{ route('portfolio.photo', $author->profile->public_slug) }}" alt="" loading="lazy">@else{{ $initials }}@endif</span>
    <span class="min-w-0 break-words font-semibold">{{ $name }}</span>
@if($public)</a>@else</span>@endif
