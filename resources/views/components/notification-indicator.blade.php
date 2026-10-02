@auth
@php($unreadNotifications = auth()->user()->siporaNotifications()->whereNull('read_at')->count())
<a href="{{ route('notifications.index') }}" class="relative grid size-10 place-items-center rounded-full border border-border bg-white text-primary shadow-xs transition hover:border-primary-300 hover:bg-primary-50" aria-label="Notifikasi{{ $unreadNotifications ? ', '.$unreadNotifications.' belum dibaca' : '' }}">
    <x-ui.icon name="bell" class="size-5" />
    @if ($unreadNotifications > 0)<span class="absolute -right-1 -top-1 min-w-5 rounded-full bg-accent px-1.5 py-0.5 text-center text-[10px] font-black text-white shadow-xs">{{ min($unreadNotifications, 99) }}</span>@endif
</a>
@endauth
