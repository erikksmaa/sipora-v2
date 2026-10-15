<?php

namespace Database\Seeders;

use App\Models\ChatMessage;
use App\Models\Organization;
use App\Services\ChatService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class DemoChatSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing']) || (! app()->environment('testing') && DB::connection()->getDatabaseName() !== 'sipora')) {
            throw new \RuntimeException('Demo chat hanya untuk development sipora atau testing terisolasi.');
        }
        $service = app(ChatService::class);
        $room = $service->globalRoom();
        $users = DemoYouthSeeder::users()->filter(fn ($u) => $u->hasVerifiedEmail() && $u->hasRole('youth'))->values();
        if ($users->isEmpty()) {
            return;
        }
        $global = ['Selamat datang di ruang obrolan SIPORA. Mari saling berbagi kegiatan positif.', 'Ada rekomendasi kegiatan belajar web untuk pemula?', 'Coba cek daftar Activity, ada workshop yang bisa diikuti bersama.', 'Terima kasih, saya sedang menyiapkan portofolio pertama.', 'Saya biasanya mulai dari satu proyek kecil yang dekat dengan keseharian.', 'Setuju, proyek sederhana membantu kita belajar secara konsisten.', 'Adakah teman yang tertarik latihan public speaking?', 'Bisa mulai berbagi cerita singkat tentang kegiatan komunitas.', 'Untuk diskusi yang panjang, kita lanjutkan di Forum ya.', 'Baik, nanti saya tulis topik belajar bersama di Forum.', 'Senang bisa bertemu teman-teman dari berbagai kecamatan.', 'Semoga semakin banyak kegiatan kolaborasi pemuda Pemalang.'];
        foreach ($global as $i => $text) {
            $this->message($service, $users[$i % $users->count()], $room, 'global-'.$i, $text);
        }
        $community = Organization::where('slug', 'komunitas-pemuda-digital-pemalang')->first();
        if (! $community) {
            return;
        }
        $members = $community->activeMemberships()->with('user')->get()->pluck('user')->filter(fn ($u) => $u->hasVerifiedEmail())->values();
        if ($members->isEmpty()) {
            return;
        }
        $room = $service->communityRoom($members[0], $community);
        $local = ['Halo teman-teman, ruang ini untuk koordinasi kegiatan komunitas kita.', 'Materi pengenalan web kemarin sangat membantu.', 'Saya ingin mencoba membuat halaman profil sederhana.', 'Boleh, kita saling memberi masukan lewat pertemuan berikutnya.', 'Bagian layout responsif masih perlu saya pelajari.', 'Kita bisa latihan dengan contoh halaman kegiatan komunitas.', 'Siap, saya akan menyiapkan contoh kecil terlebih dahulu.', 'Terima kasih semuanya. Tetap jaga informasi pribadi saat berbagi di chat.'];
        foreach ($local as $i => $text) {
            $this->message($service, $members[$i % $members->count()], $room, 'community-'.$i, $text);
        }
    }

    private function message($service, $user, $room, string $key, string $text): void
    {
        $nonce = Uuid::uuid5(Uuid::NAMESPACE_URL, 'sipora-demo-chat/'.$key)->toString();
        if (! ChatMessage::withTrashed()->where('chat_room_id', $room->getKey())->where('client_nonce', Uuid::fromString($nonce)->getBytes())->exists()) {
            $service->send($user, $room, $text, $nonce);
        }
    }
}
