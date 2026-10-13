# Integrasi manifest aset demo SIPORA

Selesai pada 13 Oktober 2026 di branch `feature/social-feed`. Integrasi memakai
`CompetitionDemoSeeder` dan domain native aplikasi. Tidak ada migration, kolom
demo ID, model organizer baru, collaborator/co-host, atau commit otomatis.

## 1. File yang berubah

- `database/seeders/data/demo-assets-manifest.json` — manifest canonical privat.
- `app/Support/Demo/DemoAssetManifest.php` — lookup, preflight, penyimpanan aset,
  dan resolusi media demo.
- `database/seeders/CompetitionDemoSeeder.php`
- `database/seeders/DemoYouthSeeder.php`
- `database/seeders/DemoEcosystemSeeder.php`
- `database/seeders/DemoProgramLogbookSeeder.php`
- `database/seeders/DemoSocialFeedMediaSeeder.php`
- `database/seeders/DemoNotificationSeeder.php`
- `database/seeders/DemoFinalEvaluationSeeder.php`
- `public/media/demo/08F_NOTIFICATIONS/notification-mapping.json`
- `app/Support/Media/RepresentativeMedia.php`
- `resources/views/components/media/opportunity.blade.php`
- `resources/views/components/media/representative.blade.php`
- `resources/views/components/media/demo-gallery.blade.php`
- `resources/views/public/activities/show.blade.php`
- `resources/views/public/communities/show.blade.php`
- `resources/views/portfolio/show.blade.php`
- `docs/DEMO_ASSET_MANIFEST_PREFLIGHT.md` dan laporan ini.

Script verifikasi dan screenshot disimpan di `output/demo-assets/` sebagai
artefak lokal, bukan entry point seeder baru.

## 2. Lokasi dan koreksi manifest

Manifest berada di `database/seeders/data/demo-assets-manifest.json`. File asli
di Downloads tidak diubah. Repository copy memuat keputusan pengguna:

| Program | Owner | Activity |
| --- | --- | --- |
| P001 — Pemalang Youth Digital Empowerment 2026 | C001 | A001, A002, A003, A005 |
| P011 — Youth Career Readiness Program | C012 | A010, A013 |

C004 tetap Community independen. Program lain yang memiliki Activity menggunakan
satu owner sesuai kelompok Activity di manifest. Preflight berhenti sebelum
seeding bila ada konflik baru. Slug Opportunity lama dipertahankan melalui
`seeder_keys`, sehingga perubahan judul tidak membuat record atau URL baru.
Dua belas media portfolio yang sudah tersedia ditambahkan dari mapping 08B,
dengan pemilik Youth yang eksplisit dan status self-reported demo media.

## 3. Seeder yang dipakai ulang

Orkestrasi utama tetap `CompetitionDemoSeeder`. Workflow
`DemoProposalStatusSeeder`, `DemoProgramFinancialReportSeeder`, dan
`DemoDiscussionSeeder` tetap digunakan tanpa penulisan ulang. Seeder logbook,
evaluasi, notification, Youth, ecosystem, dan social feed tetap menjadi bagian
pipeline yang sama. Factory bukan penghubung canonical ID; lookup memakai
email, nama, dan slug native.

## 4. Perubahan seeder

Youth mempertahankan akun legacy dan memakai ulang identitas canonical yang
sudah ada; akun yang belum tersedia dibuat dengan email demo deterministik.
Foto mengikuti manifest, termasuk varian canonical Y017/Y019. Community dan
Program memakai lookup native; Activity disambungkan ke Community dan Program
yang telah disetujui. Cover disimpan melalui disk media native.

Social feed memakai F001–F015, caption mapping existing, dan hash media stabil
untuk mempertahankan UUID post serta komentar ketika reseed. F015 terkait O002.
Caption foto teman yang dibagikan Erik tidak mengklaim Erik sebagai orang dalam
foto. Notification mengacu pada event native yang tersedia.

Program tidak dibuat completed melalui indeks fixture. Completion mengikuti
evaluasi final native. Program tanpa proposal approved tidak dipaksa running.
Riwayat proposal, logbook, LPJ, dan evaluasi existing dipertahankan.

## 5. Helper dan media

`DemoAssetManifest` memvalidasi JSON, status, sumber path, keberadaan file, dan
ownership. Lookup tidak bergantung pada primary key numerik. Aset private
disalin bila belum ada ke path relatif berbasis hash pada disk native; tidak ada
absolute machine path dalam database. RepresentativeMedia memilih media
canonical yang tersedia, lalu menggunakan fallback existing.

Gallery Activity/Community memakai dokumentasi available. Gallery portfolio
hanya tampil pada halaman pemilik, diberi label demo/belum diverifikasi, dan
tidak menambahkan SIPORA Verified Record. Public portfolio tidak menampilkan
gallery tersebut. Privacy threshold dan sistem role global tidak diubah;
membership Community menggunakan contextual role native.

## 6. Jumlah entitas

Canonical mapping tervalidasi: 20 Youth, 15 Community, 15 Activity, 15 Program,
15 Opportunity, dan 15 post bermedia. Post tanpa gambar existing tetap ada.
Data legacy dan QA tidak dihapus; karena itu jumlah total database lebih besar:

| Domain | Total sesudah seeding |
| --- | ---: |
| Users / profiles | 78 / 76 |
| Community / membership | 29 / 101 |
| Activity / participation / session / attendance | 26 / 187 / 40 / 231 |
| Certificate | 28 |
| Program / proposal | 34 / 26 |
| Logbook / logbook media | 22 / 18 |
| Financial report / item / evaluation | 12 / 18 / 12 |
| Forum thread / reply | 17 / 46 |
| Social post / comment / follow | 37 / 34 / 31 |
| Notification | 47 |

## 7. Aset available

181 referensi unik berstatus available: 179 file dan 2 referensi direktori.
Semua tersedia. Hash 20 foto profil, 15 cover Activity, dan 15 logo Community
dibandingkan dengan media native dan sesuai. Angka ini menghitung referensi
manifest, bukan seluruh asset pack historis atau semua salinan runtime.

## 8. Aset planned/missing

115 referensi planned dipertahankan. Tidak ada referensi available yang hilang.
Tidak ada path planned baru yang dipasang sebagai media rusak. Dokumen native
existing tetap dipakai ketika manifest baru hanya menyediakan rencana aset.

## 9. Struktur aset ganda

`activities/` dan `03_ACTIVITIES/Axxx/` menyimpan cover compatibility/source
yang byte-identical pada audit sebelumnya. Logo Community juga mempunyai
struktur compatibility dan paket sumber. Mapping canonical mengikuti manifest;
salinan dan varian dokumentasi A001-03-02 tidak dihapus. Ini tetap menjadi utang
penataan aset, bukan alasan membuat arsitektur media baru.

## 10. Golden demo journey

Y001 Erik → A001 → C001 → P001 tervalidasi. Erik accepted/completed, hadir pada
tiga sesi, memiliki certificate native, dan Passport diturunkan melalui service
partisipasi. P001 memiliki proposal approved, LPJ approved, final evaluation
approved, dan status completed melalui workflow existing.

Keempat Activity P001 mempunyai owner C001. A010/A013 mempunyai owner C012
yang sama dengan P011. Seluruh 15 mapping melewati rule native
`ProgramBelongsToOrganization`; jumlah link Activity–Program berbeda owner di
seluruh database adalah 0.

Perbedaan ID Batch 7 lama diselesaikan melalui judul/slug: Creative Youth
Development Program adalah P006 pada manifest baru, sedangkan Pemuda Kreatif
Kecamatan Taman adalah P002. Riwayat dan nama file PDF historis tidak dipindahkan
ke Program lain hanya karena label P002 lama berbeda.

## 11. Verifikasi

- `verify-integrated-manifest.php`: passed; preflight, canonical lookup,
  ownership rule, hash media, organizer, feed, relative DB paths, Passport,
  final workflow, privacy portfolio, dan render sanity diperiksa.
- Eksekusi kedua CompetitionDemoSeeder: jumlah row 22 tabel tetap; tidak ada
  pertambahan duplikat pada pemeriksaan ini.
- `check-integrated-following.php`: passed; hasil feed general 85 dan following
  13, sehingga kedua query berbeda; tanggal golden sesuai manifest.
- Render sanity passed untuk `/activities`, `/communities`, `/opportunities`,
  `/feed`, `/forum`, `/youth/development-pathway`, `/youth/passport`,
  `/youth/portfolio`, `/statistics`, `/ecosystem-map`, `/notifications`, dan
  `/verifier/program-evaluations`.
- Browser HTTP Activity dan Opportunity menampilkan foto canonical; bukti
  screenshot berada di `integrated-activity-proof.png` dan
  `integrated-opportunity-cards-proof.png`.
- `php artisan optimize:clear`, lint PHP yang berubah, dan `git diff --check`
  berhasil.

Render sanity protected pages adalah pemeriksaan controller/view dengan konteks
yang sesuai, bukan klaim seluruh middleware flow telah diuji end-to-end.
Full PHPUnit tidak dijalankan: TestCase mewajibkan database canonical `sipora`
dan RefreshDatabase dapat menghapus data demo. Tidak ada migrate:fresh.

## 12. Build

`npm.cmd run build` terakhir berhasil: Vite memproses 79 modules dan selesai
dalam 6,67 detik. Percobaan awal di sandbox mengalami EPERM; eksekusi ulang
dengan akses filesystem yang diperlukan berhasil.

## 13. Batas schema dan konflik

Tidak ada konflik ownership tersisa. ForumThread belum memiliki attachment
field/relationship, sehingga mapping 08E dipertahankan sebagai aset dan thread
existing tidak dipaksa menyimpan attachment. Organizer tetap provider text plus
resolusi logo/card canonical; tidak dibuat domain model baru. Portfolio generic
project evidence memakai gallery pemilik karena tidak ada native project-record
schema. Media tidak menaikkan trust/verification.

Demo scenario tag bukan pengganti enum/status native. Program tanpa approval
tetap planned; statistik dan ecosystem map tetap berasal dari domain records,
bukan score atau analytics buatan. Tidak ada dokumen identitas resmi, NIK asli,
tanda tangan asli, atau real PII baru.

## 14. Git diff

Sebelum laporan akhir, tracked diff mencatat 14 file, 163 insertions dan 18
deletions; helper, manifest, gallery, serta dua dokumen laporan merupakan file
baru di luar angka tracked tersebut. Branch tetap `feature/social-feed`.
Peringatan CRLF→LF berasal dari normalisasi Git; diff whitespace check lulus.
Tidak ada commit otomatis atau destructive cleanup.

Hasil machine-readable final:
`output/demo-assets/integrated-manifest-verification.json` dan
`output/demo-assets/integrated-following-verification.json`.
