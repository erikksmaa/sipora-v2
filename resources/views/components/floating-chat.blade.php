@if(auth()->check() && !request()->routeIs('chat.global', 'chat.community', 'chat.room', 'manager.chat') && auth()->user()->can('view', new \App\Models\ChatRoom(['type' => 'global'])))
<div class="floating-chat" x-data="floatingChat(@js(route('chat.rooms')))" :data-keyboard="keyboardOpen"
    @keydown.escape.window="if(chatOpen && !$event.target.closest('dialog')) close()">
    <button x-ref="launcher" type="button" class="fc-launcher" x-show="!chatOpen" @click="open()"
        aria-label="Buka Chat SIPORA" aria-controls="floating-chat-panel" :aria-expanded="chatOpen">
        <x-ui.icon name="message-circle" class="size-6" />
    </button>
    <div x-cloak x-show="chatOpen" class="fc-backdrop" @click="close()" aria-hidden="true"></div>
    <section x-ref="panel" id="floating-chat-panel" class="fc-panel" x-cloak x-show="chatOpen"
        x-transition:enter="fc-panel-enter" x-transition:enter-start="fc-panel-hidden" x-transition:enter-end="fc-panel-visible"
        x-transition:leave="fc-panel-leave" x-transition:leave-start="fc-panel-visible" x-transition:leave-end="fc-panel-hidden"
        role="dialog" aria-label="Chat SIPORA" :aria-modal="window.matchMedia('(max-width: 767px)').matches"
        @keydown.tab="trapFocus($event)" @click.outside="if(chatOpen && !$event.target.closest('.fc-launcher')) close()">
        <header class="fc-header">
            <button x-ref="roomBack" x-show="view === 'room'" type="button" @click="showRooms()" class="fc-control" aria-label="Kembali ke daftar ruang"><x-ui.icon name="chevron-left" class="size-5" /></button>
            <div class="min-w-0 flex-1"><h2 class="truncate text-base font-bold" x-text="view === 'room' ? activeRoom?.name : 'Chat SIPORA'"></h2><p class="text-xs text-text-muted" x-text="view === 'room' ? activeRoom?.subtitle : 'Terhubung dengan ekosistem pemuda'"></p></div>
            <button type="button" @click="close()" class="fc-control" aria-label="Tutup Chat SIPORA"><x-ui.icon name="x" class="size-5" /></button>
        </header>
        <p x-show="widgetError" role="alert" class="m-3 text-sm text-danger" x-text="widgetError"></p>
        <div x-show="view === 'list'" class="fc-room-list">
            <div class="p-3"><label for="floating-chat-search" class="sr-only">Cari ruang chat</label><input x-ref="search" id="floating-chat-search" x-model="search" class="field w-full" placeholder="Cari ruang chat" type="search"></div>
            <p x-show="roomsLoading" class="p-4 text-sm text-text-muted" role="status">Memuat ruang chat…</p>
            <div class="fc-room-items">
                <template x-for="room in filteredRooms" :key="room.key">
                    <button type="button" class="fc-room" @click="openRoom(room)">
                        <span class="fc-room-avatar" x-data="{ imageFailed: false }"><template x-if="room.logo && !imageFailed"><img :src="room.logo" alt="" x-on:error="imageFailed = true" class="size-full object-contain p-1"></template><span x-show="!room.logo || imageFailed" x-text="room.kind === 'global' ? '🌐' : room.name.trim().split(/\s+/).slice(0,2).map(part => part[0]).join('').toUpperCase()"></span></span>
                        <span class="min-w-0"><strong class="block truncate text-sm" x-text="room.name"></strong><small class="text-text-muted" x-text="room.subtitle"></small></span>
                    </button>
                </template>
                <p x-show="!roomsLoading && !rooms.some(room => room.kind === 'community')" class="p-4 text-xs text-text-muted">Belum ada Community yang dapat Anda akses.</p>
                <p x-show="!roomsLoading && rooms.length && !filteredRooms.length" class="p-4 text-sm text-text-muted">Ruang chat tidak ditemukan.</p>
            </div>
        </div>
        <p x-show="roomLoading" class="p-4 text-sm text-text-muted" role="status">Memuat percakapan…</p>
        <template x-for="session in sessions" :key="session.key">
            <div x-data="siporaChat(session)" x-show="chatOpen && view === 'room' && activeKey === session.key" class="fc-session">
                @include('chat._conversation', ['compact' => true])
                <a :href="session.roomUrl" class="fc-full-page">Buka halaman chat penuh</a>
            </div>
        </template>
    </section>
</div>
@endif
