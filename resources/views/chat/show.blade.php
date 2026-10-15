@extends('layouts.'.$workspace)
@section('title', ($room->type === 'global' ? 'Chat Global' : 'Chat Community').' · SIPORA')
@section($workspace === 'manager' ? 'manager-content' : ($workspace === 'verifier' ? 'verifier-content' : 'content'))
<div class="mx-auto max-w-5xl space-y-4" x-data="siporaChat(@js($config))">
    <x-ui.page-header eyebrow="Percakapan SIPORA" :title="$room->type === 'global' ? 'Chat Global' : 'Chat Community'" :description="$room->type === 'global' ? 'Obrolan singkat ekosistem untuk pengguna SIPORA yang telah masuk.' : ($room->community?->name ?? 'Community diarsipkan').' · Percakapan khusus anggota aktif.'" />
    <p class="text-sm text-text-secondary">Hindari membagikan NIK, nomor telepon, dan informasi pribadi. Gunakan Forum untuk diskusi terstruktur.</p>
    <div x-cloak x-show="error" role="alert" class="rounded-lg border border-danger/30 bg-danger-soft p-3 text-sm text-danger" x-text="error"></div>
    <p x-cloak x-show="notice" role="status" class="text-sm text-text-secondary" x-text="notice"></p>
    <section class="sipora-card !p-0 overflow-hidden" aria-label="Ruang percakapan">
        <div x-ref="stream" class="h-[50dvh] min-h-64 overflow-y-auto overscroll-contain p-3 sm:p-5 space-y-4" @scroll="if(nearBottom()) unread=0" role="log" aria-label="Pesan chat" aria-live="polite" aria-relevant="additions text" tabindex="0">
            <div x-show="hasOlder" class="text-center"><button class="btn-outline btn-sm" @click="older()" :disabled="loading" x-text="loading ? 'Memuat…' : 'Muat pesan sebelumnya'"></button></div>
            <p x-show="!messages.length" class="py-12 text-center text-sm text-text-secondary">{{ $room->type === 'global' ? 'Belum ada percakapan. Mulai obrolan pertama.' : 'Belum ada pesan di Community ini.' }}</p>
            <template x-for="message in messages" :key="message.id">
                <article class="chat-message flex min-w-0 gap-3 rounded-lg border border-border p-3" :class="message.own ? 'bg-accent/10' : 'bg-surface-soft'" :data-message-id="message.id">
                    <span class="grid size-9 shrink-0 place-items-center overflow-hidden rounded-lg border border-border bg-white text-xs font-bold text-text-primary"><template x-if="message.avatar_url"><img :src="message.avatar_url" alt="" class="size-full object-cover"></template><span x-show="!message.avatar_url" x-text="message.name.split(' ').map(n=>n[0]).slice(0,2).join('')"></span></span>
                    <div class="min-w-0 flex-1"><div class="flex flex-wrap items-center gap-x-3 gap-y-1"><template x-if="message.profile_url"><a :href="message.profile_url" class="font-bold text-primary underline-offset-4 hover:underline" x-text="message.name"></a></template><span x-show="!message.profile_url" class="font-bold text-text-primary" x-text="message.name"></span><time class="text-xs text-text-muted" :datetime="message.created_at" x-text="timestamp(message.created_at)"></time><span x-show="message.own" class="text-xs text-text-muted">Anda</span></div>
                        <p class="mt-2 whitespace-pre-wrap [overflow-wrap:anywhere] text-sm leading-6" :class="message.deleted ? 'italic text-text-muted' : 'text-text-primary'" x-text="message.deleted ? 'Pesan telah dihapus.' : message.message"></p>
                        <div class="mt-2 flex flex-wrap gap-3"><button x-show="message.can_report" class="text-xs font-semibold text-text-secondary underline underline-offset-4" @click="openReport(message)" :aria-label="'Laporkan pesan dari '+message.name">Laporkan</button><button x-show="message.can_delete" class="text-xs font-semibold text-danger underline underline-offset-4" @click="remove(message)" :aria-label="'Hapus pesan dari '+message.name">Hapus</button></div>
                    </div>
                </article>
            </template>
        </div>
        <div x-cloak x-show="unread" class="border-t border-border p-2 text-center"><button class="btn-outline btn-sm" @click="bottom()" x-text="'Pesan baru ('+unread+')'"></button></div>
        <form @submit.prevent="send()" class="space-y-3 border-t border-border p-3 sm:p-5">
            <label for="chat-message" class="field-label">Pesan</label><textarea id="chat-message" class="field min-h-24 resize-y" x-model="text" maxlength="1500" :disabled="!canSend || sending" rows="3" placeholder="Tulis pesan…" @keydown.enter="if(!$event.shiftKey && !$event.isComposing && !window.matchMedia('(pointer:coarse)').matches){$event.preventDefault();send()}"></textarea>
            <div class="flex flex-wrap items-center justify-between gap-3"><span class="text-xs text-text-muted"><span x-text="text.length"></span>/1500 · Shift+Enter untuk baris baru</span><button class="btn-primary" :disabled="sending || !canSend || !text.trim()" x-text="sending ? 'Mengirim…' : 'Kirim Pesan'"></button></div>
            <p x-show="!canSend" class="text-sm text-text-secondary">Ruang sedang dinonaktifkan atau akun Anda hanya memiliki akses baca.</p>
        </form>
    </section>
    <dialog x-ref="reportDialog" class="m-auto w-[calc(100%-2rem)] max-w-md rounded-xl border border-border bg-white p-5 text-text-primary backdrop:bg-primary-dark/50" aria-labelledby="report-title">
        <form @submit.prevent="report()" class="space-y-4"><h2 id="report-title" class="text-xl font-bold">Laporkan pesan</h2><label class="block"><span class="field-label">Alasan laporan</span><select class="field" x-model="reason">@foreach(\App\Services\ChatService::REASONS as $value => $label)<option value="{{ $value }}">{{ $label }}</option>@endforeach</select></label><div class="flex flex-wrap gap-3"><button class="btn-primary" :disabled="reporting" x-text="reporting ? 'Mengirim…' : 'Kirim Laporan'"></button><button type="button" class="btn-outline" @click="$refs.reportDialog.close()">Batal</button></div></form>
    </dialog>
</div>
@endsection
