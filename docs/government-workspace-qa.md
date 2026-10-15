# Government Workspace — Information Architecture & Quick Actions

Validasi: 15 Oktober 2026. Tidak ada commit atau merge otomatis.

## 1. Navigasi Admin

Overview tetap berisi Dashboard/Peta Ekosistem. Grup baru **Data Ekosistem** berisi Pemuda, Community, Activity, Program. Grup Verifikasi tetap khusus Identitas Pemuda. Konten dan Sistem existing dipertahankan. Direktori tidak menggantikan antrean verifikasi.

## 2. Navigasi Verifier

Overview, Data Ekosistem, Verifikasi Ekosistem, Program, Akun dipisahkan. Data Ekosistem memiliki Pemuda/Community/Activity/Program Terdaftar. Antrean memakai label **Verifikasi Community** dan **Verifikasi Activity**, sehingga tidak rancu dengan direktori. Seluruh menu Program existing dipertahankan. Detail direktori tetap menandai menu Data Ekosistem yang tepat.

## 3. Direktori Pemuda Admin

Seluruh akun dengan role Youth disertakan, termasuk tanpa pengajuan identitas atau Portfolio publik. Pagination 15, pencarian nama, kecamatan, status identitas, dan visibilitas Portfolio tersedia. Ditampilkan nama, foto publik jika diizinkan, kecamatan, status verifikasi email/identitas, kelengkapan profil, jumlah Community aktif, Activity selesai, visibilitas, tanggal terdaftar. Persentase memakai `ProfileCompletionService` existing; tidak ada skor baru. Detail Admin hanya ringkasan fakta baca-saja.

## 4. Direktori Community Admin

Seluruh Community non-deleted disertakan, termasuk draft/revisi/inactive. Filter pencarian, kategori, verifikasi, operasional, dan pagination tersedia. Kolom kategori/kecamatan/status/leader/anggota aktif/Activity/Program/tanggal terdaftar berasal dari relasi aktual. Nama leader mengikuti projection nama publik existing. Logo hanya memakai endpoint media publik bila Community approved dan active. Tidak ada akses file privat baru.

## 5. Direktori Pemuda Verifier

Seluruh Youth tetap tercatat, tetapi nama profil privat memakai pseudonim existing `ForumDisplayName`. Foto, minat, skill, hitungan Community dan Activity mengikuti visibilitas Portfolio. Pencarian nama dan filter kecamatan hanya mencocokkan Portfolio publik sehingga tidak menjadi sarana menebak data tersembunyi. Status identitas dan status email Admin tidak ditampilkan; filter identitas ditolak server-side. Detail memakai projection terbatas yang sama dengan daftar.

## 6. Direktori Community Verifier

Ringkasan Community terdaftar dipisahkan dari antrean review. Semua status dapat dicari/filter tanpa memberikan tombol review/update/delete. Jumlah relasi memakai `withCount`, bukan query per baris. Halaman detail tetap memakai shell Verifier.

## 7. Direktori Activity Verifier

Judul, Community, kategori, jadwal, verifikasi, publikasi, pelaksanaan, peserta accepted, dan asosiasi Program ditampilkan. Filter pencarian/status/publikasi dan pagination tersedia. Detail baca-saja tidak membuka meeting URL, alamat privat, daftar PII peserta, atau bukti privat. Direktori Activity/Program baca-saja juga tersedia bagi Admin agar destinasi dashboard sah tanpa mengarah ke manager atau workflow Verifier.

## 8. Dashboard Admin

Empat kartu utama sekarang merupakan anchor penuh: Youth ke direktori, Community aktif ke direktori approved+active, Activity publik ke direktori approved+published, Opportunity terbuka ke pengelolaan Opportunity published. Kartu status Program juga menuju direktori dengan filter pelaksanaan yang sesuai. Ringkasan analytics lain existing dipertahankan.

## 9. Dashboard Verifier

Bagian pertama **Gambaran ekosistem**: Pemuda Terdaftar, Community Aktif, Activity Publik, Program Berjalan. Bagian kedua **Ringkasan antrean**: enam antrean existing tetap utuh. Counter queue tidak diubah menjadi counter direktori.

`AdminAnalyticsService::ecosystem()` dipakai Admin dan Verifier untuk rumus yang sama. Registered Youth berasal dari `YouthStatisticsService`; Community aktif berarti approved+active, Activity publik berarti approved+published tanpa menyiratkan bahwa pendaftaran masih terbuka, Program berjalan berarti execution_status running. Tidak ada tren, ranking, atau skor rekaan.

## 10. Quick actions

Kartu memakai anchor biasa, tidak memerlukan JavaScript dan tidak memiliki nested anchor. Hover Government tetap restrained (translateY -1px existing); focus ring solid berwarna SIPORA ditambahkan pada kartu navigasi Government. Kartu Program Berjalan benar-benar membuka `status=running`; kartu Logbook tetap membuka antrean submitted. Nama/detail record memakai anchor dan tombol detail yang sah, bukan memaksa navigasi melalui workspace lain.

## 11. Authorization dan privasi

Semua route baru berada dalam middleware auth/verified/role existing. Service juga memverifikasi actor dan workspace. Tidak ada perubahan policy, grant role, identitas, aturan verifikasi, atau mutasi domain. Tidak ada endpoint edit/delete baru.

Data dikirim ke view sebagai array projection, bukan model mentah. Tidak ada email/nomor telepon/NIK/tanggal lahir/alamat rinci/dokumen identitas/evidence privat yang dirender. Verifier tidak memuat data identity dan hanya memilih kolom profil yang diperlukan. Admin memakai data internal yang diperlukan calculator kelengkapan existing, tetapi hanya hasil persentasenya dirender. Relasi enrichment hanya memuat ID untuk pemeriksaan keberadaan; path evidence tidak dibawa ke view.

Domisili desa dipetakan ke kecamatan parent aktual; filter kecamatan mendukung keduanya. Nama desa/alamat privat tidak ditampilkan. Tidak ada coordinate/geographic score baru. Tautan Portfolio/media publik tetap mengikuti akses existing.

## 12. File fase ini

Baru:

- `app/Services/GovernmentDirectoryService.php`
- `app/Http/Controllers/GovernmentDirectoryController.php`
- `resources/views/government/directory/index.blade.php`
- `resources/views/government/directory/show.blade.php`
- `tests/Feature/GovernmentDirectoryTest.php`
- `docs/government-workspace-qa.md`

Diubah:

- `app/Services/AdminAnalyticsService.php`
- `app/Http/Controllers/Verifier/VerifierDashboardController.php`
- `resources/views/layouts/workspace.blade.php`
- `resources/views/workspaces/admin.blade.php`
- `resources/views/workspaces/verifier.blade.php`
- `resources/css/app.css` (focus kartu Government)
- `routes/admin.php`, `routes/verifier.php`

Perubahan Program Intake dan tabel dari fase sebelumnya tetap dipertahankan. Tidak ada migration/schema baru dalam fase ini.

## 13. Routes

Untuk masing-masing prefix `/admin` dan `/verifier`:

- `GET /directory/youth` dan `/directory/youth/{record}`
- `GET /directory/communities` dan `/directory/communities/{record}`
- `GET /directory/activities` dan `/directory/activities/{record}`
- `GET /directory/programs` dan `/directory/programs/{record}`

Total 16 route baru; nama `admin.directory.{kind}.{index|show}` / `verifier.directory.{kind}.{index|show}`. Audit route:list tidak menemukan nama duplikat. Semua route verifikasi/manager existing dipertahankan.

## 14. Tests

Tujuh test baru mencakup akses daftar/detail kedua role; Youth/wrong-role denial; penolakan tindakan Admin dari Verifier; profil privat dan filter yang tidak membocorkan informasi; pencarian/filter/pagination/empty state; counter faktual dan tujuan quick-action; stabilitas jumlah query terhadap banyak Youth; serta pemetaan desa ke kecamatan. Test regression existing tidak perlu diubah.

## 15. Hasil tests

- Targeted: **13 passed, 175 assertions, 21,15 detik**.
- Full regression final: **316 passed, 2.706 assertions, 170,25 detik**.
- Syntax tujuh file PHP terkait: lulus.
- Pint lima file PHP implementasi/test: lulus menggunakan temporary copies dan atomic replacement untuk menghindari file mapping Windows.
- `git diff --check`: lulus.

Pengujian menggunakan database terisolasi `sipora_attendance_test`, dengan temporary test configuration/guard dan compiled-view path terpisah. `tests/TestCase.php` serta `phpunit.xml` dipulihkan setelah run. Development database `sipora` tidak di-reset atau di-seed ulang. Full suite mencakup Community/Activity verification, Proposal, Logbook, E-LPJ, Final Evaluation dan domain lain existing.

Log: `storage/logs/government-targeted-tests.log`, `storage/logs/government-regression.log`.

## 16. Build

`npm run build`: **lulus**, 79 module, 11,40 detik. Tidak ada dependency baru. `php artisan view:cache` juga berhasil. Log build: `storage/logs/government-build.log`.

## 17. Manual QA dan performa

Dashboard dan keempat direktori kedua role diperiksa dalam matrix 1440/768/390 px × light/dark: 60 kombinasi. Detail, menu mobile, antrean, dan quick actions menambah pemeriksaan menjadi 73 entry geometris: **0 overflow halaman**. Tabel lebar bergulir dalam container. Active sidebar benar pada index/detail. Drawer mobile bisa membuka direktori Community. Profil privat Verifier menampilkan nama netral tanpa detail tersembunyi. Keyboard Tab menunjukkan focus ring kartu; region tabel yang dapat difokuskan merespons ArrowRight dengan scroll horizontal.

Antrean Community, Activity, Proposal, Pendaftaran Program, Logbook, E-LPJ, Evaluasi Akhir tetap membuka shell/menu Verifier yang tepat. Tidak ada keputusan verifikasi atau perubahan record dilakukan saat QA browser. Memakai akun demo existing; tidak membuat akun atau mengubah role/password/identity. Sesi QA logout, tema awal dipulihkan, dan viewport override di-reset.

Pengukuran service read-only pada data development, maksimal 15 record per halaman, dengan role actor sudah dimuat:

| Direktori | Query Admin / Verifier | Waktu SQL Admin / Verifier |
| --- | --- | --- |
| Youth | 15 / 11 | 24,27 / 14,50 ms |
| Community | 10 / 10 | 14,24 / 10,33 ms |
| Activity | 5 / 5 | 6,73 / 4,42 ms |
| Program | 4 / 4 | 3,95 / 4,50 ms |

Elapsed service lokal sekitar 10–114 ms; ini sanity check lokal, bukan benchmark produksi/HTTP. Counter relasi menggunakan subquery aggregates dan relasi daftar di-eager-load. Test memverifikasi jumlah query tidak tumbuh per Youth. Tidak ada query per baris untuk calculator kelengkapan.

Bukti: `storage/app/government-workspace-qa/browser-checks.json`, `performance.json`, `routes.json`, dan screenshot pada folder yang sama.

## 18. Batas dan masalah tersisa

Tidak ditemukan kegagalan pada validasi di atas. Direktori bersifat read-oriented; dokumen identitas dan bukti privat hanya melalui workflow berwenang existing. Foto/logo privat sengaja tidak diambil melalui endpoint baru. Filter kelengkapan memakai status visibilitas yang benar-benar tersedia; persentase kelengkapan Admin memakai calculator existing, tidak dibuatkan rumus/filter SQL kedua.

Tidak ada perubahan aturan verifikasi, assessment, score, chat, messaging, database redesign, commit atau merge otomatis.
