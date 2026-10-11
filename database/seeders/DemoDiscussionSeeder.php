<?php

namespace Database\Seeders;

use App\Models\ForumCategory;
use App\Models\ForumReaction;
use App\Models\ForumReply;
use App\Models\ForumReport;
use App\Models\ForumThread;
use App\Models\Organization;
use App\Models\SocialComment;
use App\Models\SocialFollow;
use App\Models\SocialLike;
use App\Models\SocialPost;
use App\Models\SocialReport;
use App\Models\UserNotification;
use Illuminate\Database\Seeder;

class DemoDiscussionSeeder extends Seeder
{
    public function run(): void
    {
        $users = DemoYouthSeeder::users()->take(20)->values();
        $topics = [
            ['teknologi', 'Mulai belajar Laravel atau JavaScript dulu?', 'Saya sudah memahami HTML dan CSS. Lebih baik memperdalam JavaScript sebelum masuk Laravel?', ['Mulai JavaScript dasar agar paham interaksi di browser. Laravel bisa menyusul ketika mulai belajar backend.', 'Saya mencoba membuat daftar tugas sederhana dahulu. Latihan DOM membantu memahami alur data.', 'Belajar PHP dasar sebelum Laravel juga membantu. Tidak harus menguasai semuanya sekaligus.']],
            ['teknologi', 'Tips membuat portfolio web untuk mahasiswa', 'Bagaimana memilih proyek yang menunjukkan kemampuan tanpa harus punya pengalaman kerja?', ['Cantumkan masalah yang diselesaikan, bukan hanya screenshot.', 'Website kegiatan komunitas bisa menjadi proyek nyata. Minta izin sebelum menampilkan data peserta.', 'Dua proyek yang tuntas dan dokumentasinya jelas lebih mudah dinilai daripada banyak proyek kosong.']],
            ['teknologi', 'Hosting untuk project organisasi', 'Kami perlu hosting untuk situs informasi kegiatan. Apa saja yang perlu diperiksa sebelum memilih layanan?', ['Periksa backup dan kemudahan memindahkan data.', 'Untuk situs statis, hosting statis cukup. Jangan menyimpan data anggota di repositori publik.', 'Pisahkan domain dan hosting agar mudah pindah penyedia.']],
            ['karier', 'Persiapan magang semester enam', 'Saya ingin magang di bidang pengembangan web. Bagaimana menyiapkan dokumen dan jadwal kuliah?', ['Tanyakan jadwal wajib kampus sebelum memilih durasi magang.', 'Saya menyiapkan CV, portofolio, dan surat pengantar dalam satu folder.', 'Baca tanggung jawab posisi dan siapkan contoh kerja yang relevan.']],
            ['karier', 'Cara membuat CV untuk internship', 'Pengalaman saya baru kepanitiaan kampus. Bagaimana menjelaskan kontribusinya di CV?', ['Tulis tugas konkret yang dikerjakan dan hasilnya.', 'Contoh: mengelola jadwal relawan atau menyiapkan materi publikasi.', 'Gunakan satu halaman yang mudah dibaca. Jangan mencantumkan NIK.']],
            ['karier', 'Skill untuk junior developer', 'Selain framework, keterampilan apa yang perlu dilatih agar siap bekerja dalam tim?', ['Git dan komunikasi saat review kode sangat membantu.', 'Latih debugging dan menjelaskan penyebab masalah.', 'Dasar database serta menulis tes untuk alur penting juga berguna.']],
            ['kewirausahaan', 'Ide usaha digital untuk pemuda', 'Adakah pengalaman memulai jasa digital untuk UMKM di sekitar Pemalang?', ['Saya mulai membantu foto produk warung tetangga dengan persetujuan pemilik.', 'Coba tawarkan satu layanan sederhana dan jelaskan batas revisinya.', 'Wawancarai calon pelanggan agar layanan menjawab kebutuhan mereka.']],
            ['kewirausahaan', 'Mulai jualan online dengan modal kecil', 'Bagaimana menguji produk tanpa langsung membeli stok besar?', ['Bisa mulai pesanan terbatas dan catat biaya dengan jelas.', 'Uji satu jenis produk, lalu minta umpan balik pembeli.', 'Pisahkan uang usaha dan pribadi sejak awal.']],
            ['organisasi-community', 'Membagi tugas panitia event', 'Panitia festival kami sering tumpang tindih tugas. Bagaimana menyusun pembagian kerja?', ['Buat daftar hasil kerja dan satu penanggung jawab untuk setiap hasil.', 'Gunakan rapat singkat mingguan untuk mengecek hambatan.', 'Siapkan kontak cadangan untuk tugas saat hari pelaksanaan.']],
            ['organisasi-community', 'Meningkatkan partisipasi anggota', 'Kegiatan rutin komunitas mulai sepi. Bagaimana mengetahui kebutuhan anggota?', ['Tanyakan pilihan jadwal dan topik lewat survei singkat.', 'Beri ruang anggota mengusulkan dan memimpin kegiatan kecil.', 'Coba pertemuan dengan tujuan konkret, seperti menyelesaikan satu karya.']],
            ['volunteer-sosial', 'Pengalaman pertama menjadi volunteer', 'Saya akan menjadi relawan festival. Apa yang perlu disiapkan sebelum bertugas?', ['Pelajari pembagian area dan jalur komunikasi panitia.', 'Bawa air minum serta baca panduan keselamatan.', 'Jika ada masalah peserta, hubungi koordinator dan jangan membagikan data pribadinya.']],
            ['volunteer-sosial', 'Mencari kegiatan sosial di Pemalang', 'Saya ingin bergabung dalam aksi lingkungan pada akhir pekan. Bagaimana memastikan kegiatannya jelas?', ['Periksa penyelenggara dan jadwal di halaman Activity SIPORA.', 'Tanyakan perlengkapan dan titik berkumpul kepada koordinator.', 'Pastikan peran relawan dan aturan keselamatan dijelaskan.']],
            ['pendidikan', 'Belajar dari course gratis', 'Bagaimana memilih materi belajar web gratis agar tidak berpindah course terus?', ['Pilih satu silabus dasar lalu kerjakan latihan sampai selesai.', 'Dokumentasi resmi cocok sebagai rujukan saat praktik.', 'Buat catatan pertanyaan untuk dibahas bersama komunitas.']],
            ['pendidikan', 'Menjaga konsistensi belajar', 'Saya sering berhenti belajar setelah beberapa hari. Adakah cara mengatur target yang realistis?', ['Mulai dua puluh menit per hari dan satu target kecil.', 'Teman belajar membantu menjaga jadwal tanpa harus membandingkan hasil.', 'Sisihkan satu hari untuk mengulang materi dan menyelesaikan latihan.']],
            ['kreativitas', 'Foto produk UMKM dengan cahaya alami', 'Saya ingin belajar foto produk menggunakan ponsel. Bagaimana mengatur cahaya dan latar?', ['Coba cahaya dekat jendela dan hindari matahari langsung.', 'Latar polos membantu produk terlihat jelas.', 'Gunakan kertas putih sebagai pemantul dan bandingkan hasilnya.']],
            ['olahraga', 'Persiapan futsal komunitas', 'Kami merencanakan futsal persahabatan. Apa yang perlu diperhatikan agar aman dan inklusif?', ['Sediakan waktu pemanasan dan air minum.', 'Jelaskan aturan permainan serta kesempatan rotasi pemain.', 'Catat kontak penanggung jawab fasilitas sebelum pertandingan.']],
            ['diskusi-umum', 'Agenda temu komunitas bulan ini', 'Mari berbagi rencana pertemuan komunitas agar jadwal kolaborasi tidak berbenturan.', ['Komunitas kami menyiapkan kelas desain akhir pekan.', 'Kami akan membahas jadwal bersama koordinator terlebih dahulu.', 'Bagikan tautan Activity setelah jadwal disetujui.']],
        ];
        foreach ($topics as $i => [$category, $title, $body, $answers]) {
            $author = $users[$i % 20];
            $thread = ForumThread::firstOrCreate(['title' => $title, 'author_id' => $author->getKey()], [
                'category_id' => ForumCategory::where('slug', $category)->firstOrFail()->getKey(), 'body' => $body, 'status' => 'active',
            ]);
            if ($thread->wasRecentlyCreated) {
                $thread->forceFill(['created_at' => now()->subDays(18 - $i), 'updated_at' => now()->subDays(18 - $i)])->save();
            }
            $replyCount = [3, 3, 2, 6, 3, 2, 3, 4, 3, 2, 3, 2, 3, 4, 2, 1, 0][$i];
            for ($j = 0; $j < $replyCount; $j++) {
                $replyAuthor = $users[($i + $j + 1) % 20];
                $answer = $answers[$j % 3];
                if ($j >= 3) {
                    $answer = ['Terima kasih. Saya akan mencoba langkah itu dan membagikan hasil praktiknya.', 'Setuju, kami pernah mencoba pendekatan tersebut saat kegiatan komunitas.', 'Saya akan membawa pertanyaan ini ke pertemuan komunitas berikutnya.'][$j - 3];
                }
                ForumReply::firstOrCreate(['thread_id' => $thread->getKey(), 'author_id' => $replyAuthor->getKey(), 'body' => $answer], ['is_hidden' => false, 'created_at' => $thread->created_at->copy()->addHours($j + 1)]);
            }
            for ($j = 0; $j < 1 + $i % 5; $j++) {
                ForumReaction::firstOrCreate(['thread_id' => $thread->getKey(), 'user_id' => $users[($i + $j + 2) % 20]->getKey()]);
            }
            if (in_array($i, [2, 6, 14])) {
                ForumReport::firstOrCreate(['thread_id' => $thread->getKey(), 'reply_id' => null, 'reporter_id' => $users[19]->getKey()], ['reason' => 'other', 'details' => 'Skenario demo: mohon tinjau informasi layanan eksternal sebelum dibagikan.', 'status' => 'pending']);
            }
            $org = $i % 3 === 0 ? Organization::where('slug', ['komunitas-pemuda-digital-pemalang', 'forum-pemuda-taman', 'youth-entrepreneur-pemalang', 'pemuda-olahraga-pemalang', 'sanggar-seni-muda-belik', 'komunitas-pemuda-digital-pemalang'][intdiv($i, 3)])->firstOrFail() : null;
            $postAuthor = $org ? $org->creator : $author;
            $postBody = [
                'Workshop web selesai! Kami belajar HTML, CSS, JavaScript, lalu mempresentasikan mini project. Terima kasih untuk teman-teman yang saling membantu.',
                'Hari ini merapikan portofolio web. Saya menambahkan penjelasan masalah, proses, dan hasil untuk setiap proyek.',
                'Sedang membandingkan pilihan hosting untuk situs komunitas. Fokus pada backup dan biaya yang transparan.',
                'Forum Pemuda Taman membuka diskusi persiapan magang. Bawa CV dan contoh karya agar bisa saling memberi masukan.',
                'CV pertama akhirnya selesai. Pengalaman kepanitiaan saya tulis sebagai kontribusi konkret.',
                'Latihan Git bersama teman membuat saya lebih memahami review kode dan kerja tim.',
                'Youth Entrepreneur Pemalang mengajak pemuda berdiskusi tentang foto produk dan pencatatan usaha kecil.',
                'Menguji ide usaha dengan pesanan terbatas dulu. Catatan biaya ternyata sangat membantu.',
                'Tim festival mulai menyusun pembagian tugas. Setiap bagian punya penanggung jawab dan jadwal koordinasi.',
                'Pemuda Olahraga Pemalang menyiapkan futsal persahabatan. Pemanasan dan rotasi pemain tetap menjadi perhatian.',
                'Pengalaman pertama sebagai relawan festival: belajar mendengarkan peserta dan mengikuti arahan koordinator.',
                'Aksi lingkungan akhir pekan terasa lebih ringan saat dikerjakan bersama. Sampah dipilah sesuai arahan panitia.',
                'Sanggar Seni Muda Belik mengajak pemuda belajar karya kreatif dan mendokumentasikan prosesnya.',
                'Target belajar minggu ini sederhana: dua puluh menit per hari dan satu latihan yang selesai.',
                'Mencoba foto produk dengan cahaya jendela dan latar polos. Hasilnya lebih jelas tanpa peralatan mahal.',
                'Komunitas Pemuda Digital Pemalang menyiapkan kelas pengembangan web lanjutan. Lihat jadwal Activity untuk sesi berikutnya.',
                'Mencatat agenda kolaborasi komunitas bulan ini. Senang bertemu teman dari kecamatan lain.',
            ][$i];
            $post = SocialPost::firstOrCreate(['author_id' => $postAuthor->getKey(), 'organization_id' => $org?->getKey(), 'body' => $postBody], ['visibility' => 'public', 'is_hidden' => false, 'is_removed' => false]);
            if ($post->wasRecentlyCreated) {
                $post->forceFill(['created_at' => now()->subHours(2 + $i * 5)])->save();
            }
            foreach (range(0, $i % 6) as $j) {
                SocialLike::firstOrCreate(['post_id' => $post->getKey(), 'user_id' => $users[($i + $j + 1) % 20]->getKey()]);
            }
            for ($j = 0; $j < ($i === 0 ? 2 : $i % 5); $j++) {
                $comments = ['Topik '.mb_strtolower($title).' juga sedang kami bahas di komunitas.', $answers[0], $answers[1], 'Terima kasih sudah berbagi pengalaman. Saya ingin mencoba bersama teman.'];
                SocialComment::firstOrCreate(['post_id' => $post->getKey(), 'author_id' => $users[($i + $j + 2) % 20]->getKey(), 'body' => $comments[$j]], ['is_hidden' => false]);
            }
            if (in_array($i, [2, 6])) {
                SocialReport::firstOrCreate(['post_id' => $post->getKey(), 'comment_id' => null, 'reporter_id' => $users[19]->getKey()], ['reason' => 'other', 'details' => 'Skenario demo moderasi: periksa informasi layanan sebelum diteruskan.', 'status' => 'pending']);
            }
            if ($i === 0) {
                foreach ([
                    ['social_commented', 'Seorang pemuda menanggapi cerita workshop web kamu.', ['post_id' => $post->uuid()]],
                    ['social_followed', 'Seorang pemuda mengikuti update publik kamu.', []],
                    ['forum_thread_replied', 'Ada balasan baru pada diskusi belajar web.', ['thread_id' => $thread->uuid()]],
                ] as [$type, $notificationBody, $data]) {
                    UserNotification::withTrashed()->firstOrCreate(['user_id' => $users[0]->getKey(), 'notification_type' => $type, 'title' => 'Demo — Update ekosistem'], ['body' => $notificationBody, 'data' => $data + ['development_fixture' => true], 'read_at' => null]);
                }
            }
        }
        $clusters = ['komunitas-pemuda-digital-pemalang', 'komunitas-desain-pemalang', 'relawan-muda-pemalang', 'youth-entrepreneur-pemalang'];
        foreach ($users->take(16) as $i => $user) {
            SocialFollow::firstOrCreate(['user_id' => $user->getKey(), 'organization_id' => Organization::where('slug', $clusters[$i % 4])->firstOrFail()->getKey(), 'youth_id' => null]);
            // Follow only public Youth portfolios; no blanket graph.
            $target = $users[$i === 15 ? 0 : ($i + 2) % 19];
            if ($target->profileVisibility?->is_profile_public) {
                SocialFollow::firstOrCreate(['user_id' => $user->getKey(), 'youth_id' => $target->getKey(), 'organization_id' => null]);
            }
        }
    }
}
