@auth
@php($unreadNotifications = auth()->user()->siporaNotifications()->whereNull('read_at')->count())
<a href="{{ route('notifications.index') }}" class="relative grid size-10 place-items-center rounded-full border border-slate-200 bg-white text-primary hover:border-indigo-300" aria-label="Notifikasi{{ $unreadNotifications ? ', '.$unreadNotifications.' belum dibaca' : '' }}">
    <x-ui.icon name="bell" class="size-5" />
    @if ($unreadNotifications > 0)<span class="absolute -right-1 -top-1 min-w-5 rounded-full bg-orange-500 px-1.5 py-0.5 text-center text-[10px] font-black text-white">{{ min($unreadNotifications, 99) }}</span>@endif
</a>
@endauth
