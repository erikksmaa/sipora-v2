<article class="sipora-card space-y-4 p-5 sm:p-6">
<div class="flex flex-wrap items-start justify-between gap-3"><div>
@if($post->organization)<a class="font-bold text-text-primary" href="{{ route('communities.show', $post->organization) }}">{{ $post->organization->name }}</a><p class="text-xs text-text-muted">Update Community</p>
@else <x-public.author-identity :author="$post->author" /><p class="text-xs text-text-muted">Youth</p>@endif
<time class="text-xs text-text-muted" datetime="{{ $post->created_at->toIso8601String() }}">{{ $post->created_at->diffForHumans() }}</time>@if($post->visibility === 'private')<x-ui.badge tone="warning">Privat · pengelola saja</x-ui.badge>@endif</div>
@if($post->organization)@include('social.follow', ['targetType'=>'community', 'targetId'=>$post->organization->slug])@elseif($post->author->profileVisibility?->is_profile_public && $post->author->profile && auth()->user()?->getKey() !== $post->author_id)@include('social.follow', ['targetType'=>'youth','targetId'=>$post->author->profile->public_slug])@endif</div>
<p class="whitespace-pre-line break-words text-sm leading-7 text-text-secondary">{{ $post->body }}</p>
@if($post->image_path)<div class="social-post-media"><img src="{{ route('social.image', $post) }}" alt="Gambar lampiran post" loading="lazy"></div>@endif
<div class="flex flex-wrap items-center gap-3 border-t border-border pt-4">@auth @if(auth()->user()->hasRole('youth') && $post->visibility === 'public')<form method="post" action="{{ route('social.like', $post) }}">@csrf<input type="hidden" name="liked" value="{{ $liked ? 0 : 1 }}"><button class="btn-outline !min-h-10 !px-3 text-sm" aria-pressed="{{ $liked ? 'true' : 'false' }}" type="submit">{{ $liked ? 'Batal suka' : 'Suka' }} · {{ $post->likes_count }}</button></form>@endif @endauth
@if(!$detail)<a class="btn-outline !min-h-10 !px-3 text-sm" href="{{ route('social.show', $post) }}">Komentar · {{ $post->comments_count }}</a>@else<p class="text-xs text-text-muted">{{ $post->likes_count }} suka</p>@endif
</div>
</article>
