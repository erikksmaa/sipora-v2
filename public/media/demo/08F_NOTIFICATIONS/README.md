# SIPORA Batch 8F — Notification Demo Data

Delapan notifikasi sudah di-seed ke tabel notifications lokal, memakai tipe dan payload
kelas notification native SIPORA. Tujuh untuk Erik Kusuma Rais (youth1@sipora.test),
satu revisi proposal P002 untuk Aditya Pratama (demo.youth01@sipora.test).
Total batch awal: enam unread dan dua read. Status baca/deletion tetap dipertahankan saat reseed.

Event:
N001 Pendaftaran A001 diterima — memakai participation accepted existing Erik.
N002 Keanggotaan C001 disetujui — keanggotaan Erik sudah active, saat ini leader.
N003 Sertifikat A001 tersedia — sertifikat SIPORA verified existing milik Erik.
N004 Proposal P002 perlu revisi — latest proposal revision, ditujukan ke creator C002.
N005 E-LPJ P001 disetujui — report approved dan Program completed dari Batch 7G.
N006 Thread mendapat balasan — thread Laravel/JavaScript existing Erik memiliki balasan terlihat.
N007 Post mendapat komentar — komentar fiktif Aulia pada post belajar web existing Erik.
N008 Akun diikuti — follow fiktif Aulia terhadap Erik.

Seeder tidak mengubah approval/registration/completion/program/certificate yang sudah ada.
Komentar dan follow dibuat sebagai data lokal pendukung, firstOrCreate untuk menghindari duplikat.
Notification model ditulis langsung menggunakan payload native, tanpa email, queue, atau broadcast.
Waktu notification adalah timeline presentasi sintetis, bukan timestamp audit historis workflow.
Semua body mempunyai penanda DATA DEMO SIPORA; data menyimpan demo_batch dan demo_event_id.

Seeder: database/seeders/DemoNotificationSeeder.php
Jalankan pada lingkungan local dengan database sipora:
php artisan db:seed --class=DemoNotificationSeeder --force
CompetitionDemoSeeder memanggilnya setelah data workflow dan social feed lokal tersedia.

Verifikasi scoped:
php output/demo-assets/verify-notification-demo.php
Delapan event dan owner/state dicek; delapan action links berhasil di-resolve;
halaman NotificationController native untuk kedua penerima berhasil dirender;
reseed mempertahankan UUID, timestamp, read state dan jumlah interaksi sosial.
Tidak menjalankan RefreshDatabase atau menghapus data.

Buka /notifications setelah login sebagai penerima untuk screenshot aplikasi native.
index.html adalah preview snapshot kedua inbox, bukan halaman notifikasi login native.
JSON/CSV menyertakan data event dan tujuan tautan. Tidak ada aset gambar baru.
