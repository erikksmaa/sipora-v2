# SIPORA — offline competition readiness audit

Tanggal: **17 Oktober 2026**. Branch saat audit: `develop`. Scope: audit, dokumentasi, template konfigurasi lokal; tidak ada perubahan workflow, commit/push, ekspor SQL, download installer/font/tile, atau arsip besar otomatis.

**Kesimpulan:** fitur inti dapat didemokan offline setelah salinan disiapkan dengan dependensi, SQL dan media yang cocok serta profil lokal eksplisit. Mesin pengembangan saat audit masih mengaktifkan reCAPTCHA; jangan menyebut konfigurasi aktif itu sudah offline-ready. Restore pada komputer Windows kedua dan rehearsal cold browser tanpa internet **masih harus dilakukan**.

Panduan operasional: [OFFLINE_COMPETITION_SETUP.md](OFFLINE_COMPETITION_SETUP.md). Checklist ringkas: [COMPETITION_QUICK_START.md](COMPETITION_QUICK_START.md). Template tanpa secrets: [../.env.competition.example](../.env.competition.example).

## 1. Runtime dan development requirements

Versi di bawah berasal dari source/lock dan perintah lokal, bukan asumsi daftar awal.

| Komponen | Kebutuhan berdasar project | Versi terverifikasi / rekomendasi |
| --- | --- | --- |
| OS | PHP/MySQL lokal; transfer native npm harus cocok OS/arsitektur | Windows 10/11 x64 sebagai target; komputer kedua belum diuji |
| Laravel | composer.json `^13.0` | lock dan runtime **13.33.0** |
| PHP | root composer.json/lock **`^8.4`**, activitylog `^8.4`; framework sendiri `^8.3` tidak menurunkan syarat project | **8.4.15**, NTS, VS17, x64; pilih 8.4.x yang cocok SAPI |
| MySQL | schema MySQL-specific, InnoDB, binary UUID, JSON/CHECK, collation 0900 | server dan client **8.0.30**; gunakan baseline sama |
| Composer | lock meminta composer-runtime-api **`^2.2`**; tidak ada pin versi CLI root | CLI **2.10.3**, runtime API **2.2.2**, platform lulus |
| Node | root tidak punya `engines`; Vite/plugin locked meminta **`^20.19.0 || >=22.12.0`** | **24.21.0 x64** untuk rebuild; tidak perlu untuk request web hasil build |
| npm | tidak ada constraint npm atau `packageManager` root | **11.19.0**, gunakan lock yang sama; tidak perlu untuk run only |
| Web server | document root `public/`, PHP writable storage/cache | Laragon Apache/Nginx yang cocok PHP; fallback `php artisan serve` |
| Browser | JS modules, fetch, CSS modern; frontend diuji lewat suite Node | browser modern lokal; cold browser offline harus direhearsal |
| Storage | vendor, build, media private/public dan folder framework writable | lihat bagian media; ruang ekstra untuk backup+extract diperlukan |
| Layanan lain | session binary-database dan cache database memakai MySQL; chat HTTP polling | Redis/WebSocket server tidak diwajibkan; queue sync pada template |

Laragon, Composer, Node/npm, Git, browser dan VS Code dicantumkan dalam checklist installer setup. Laragon adalah kemudahan operasional, bukan dependency Composer. Composer CLI dan Git direkomendasikan untuk pemeriksaan/history; Node/npm hanya untuk rebuild; VS Code opsional. Jangan memakai script root `composer run setup` pada snapshot siap demo karena menghasilkan key dan melakukan install dependency.

### PHP extensions dan bukti

`composer check-platform-reqs` **lulus**, tetapi tidak mendeteksi semua pemakaian extension langsung oleh aplikasi.

| Extension | Bukti kebutuhan | Hasil mesin audit |
| --- | --- | --- |
| `PDO`, **`pdo_mysql`** | config/database.php, Eloquent dan schema MySQL; tidak dideklarasikan root sebagai ext constraint | tersedia, wajib runtime DB |
| **`bcmath`** | FinancialReportTotalsService memakai `bcadd`, `bcsub`, `bccomp` untuk LPJ keuangan | tersedia, wajib alur keuangan |
| `ctype`, `filter`, `hash`, `mbstring`, `openssl`, `session`, `tokenizer` | composer.lock laravel/framework dan dependency terkait | tersedia; Composer melaporkan polyfill ctype/mbstring juga memenuhi constraint |
| `json`, `pcre` | Guzzle/Socialite/Carbon dan phpdotenv pada lock; JSON aplikasi | tersedia |
| `fileinfo` | flysystem-local/mime-type-detection, validasi/preview berkas | tersedia |
| `iconv` | symfony/polyfill-mbstring pada lock | tersedia |
| `dom`, `libxml` | tijsverkoyen/css-to-inline-styles (runtime email) dan PHPUnit | tersedia |
| `xml`, `xmlwriter`, `Phar` | Pint, PHPUnit/code coverage, phar-io (dev/test) | tersedia; bawa bila menjalankan QA di lokasi |
| `curl` | HTTP integrasi Google via Guzzle; tidak merupakan hard require seluruh lock (Guzzle memiliki transport lain) | tersedia; direkomendasikan untuk mode online |
| `intl`, `gd` | tidak ditemukan pemanggilan extension langsung yang mewajibkan keduanya pada app; lock memakai beberapa polyfill intl | tersedia, bukan blocker offline yang dibuktikan |
| `zip` | tidak diwajibkan lock atau dipakai langsung oleh app yang diaudit | **tidak dimuat**; bukan blocker runtime. Composer fresh install/arsip perlu extraction tool yang tersedia atau extension sesuai kebutuhan |

Verifikasi CLI dan php.ini web server, bukan CLI saja:

```powershell
php -v
php --ini
php -m
composer check-platform-reqs
```

Jangan menggunakan `--ignore-platform-reqs`. Jalankan alur LPJ untuk membuktikan bcmath, sebab platform check saja tidak memeriksanya.

## 2. Composer/frontend dependency inventory

| Paket Composer utama | Constraint root | Locked |
| --- | --- | --- |
| laravel/framework | ^13.0 | 13.33.0 |
| laravel/socialite | ^5.31 | 5.31.0 |
| spatie/laravel-permission | ^8.3 | 8.3.0 |
| spatie/laravel-activitylog | ^5.1 | 5.1.1 |
| phpunit/phpunit (dev) | ^12.5.12 | 12.5.36 |

`vendor/` tersedia: **9.620 file, 49,66 MiB**. Bawa langsung untuk mode siap jalan. Fresh `composer install` memakai lock dan membutuhkan cache/internet bila paket belum tersedia; jangan `update`. Tidak ada package Composer layanan PDF/Node runtime yang wajib untuk request web dalam project ini.

| npm package locked | Peran |
| --- | --- |
| alpinejs **3.17.4** | bundle app, interaksi lokal |
| chart.js **4.5.1** | bundle statistik lokal |
| gsap **3.15.0** | dynamic chunk animasi lokal |
| leaflet **1.9.4** | bundle peta dan CSS lokal |
| sweetalert2 **11.26.25** | bundle alert dan CSS lokal |
| axios **1.20.0** | bootstrap app lokal; klasifikasi devDependency di package.json tidak berarti tidak masuk bundle |
| vite **7.3.6**, laravel-vite-plugin **2.1.0** | build/dev server saja |
| tailwindcss **4.3.3**, @tailwindcss/vite **4.3.3** | menghasilkan CSS saat build, tidak butuh CDN runtime |
| concurrently **9.2.4** | menjalankan proses dev saja |

Blade adalah template PHP. Ikon komponen `ui/icon.blade.php` berupa SVG lokal, bukan font/icon CDN. `node_modules/` tersedia: **4.205 file, 64,95 MiB**. Sertakan untuk rebuild onsite Windows x64; tidak dihapus atau dipindahkan dalam audit.

`npm run build` lulus, **89 modules**, **15,13 detik**. `public/build` memiliki 16 file sekitar **0,73 MiB**; 14 entry manifest beserta semua file CSS/assets/import/dynamicImport yang dirujuk ditemukan. `public/hot` tidak ada. `@vite` memakai build manifest ketika hot file tidak ada, sehingga node_modules tidak diperlukan untuk melayani hasil build. Ini diverifikasi lewat source pipeline dan keberadaan seluruh chunk, bukan dengan menghapus node_modules.

## 3. Database portability

- Koneksi aktif `mysql`; MySQL server **8.0.30**, database demo `sipora`, **69 tabel InnoDB**, 30 migration semuanya `Ran`.
- Migrations memakai **BINARY(16)** primary/foreign keys, **DATETIME(6)**, `utf8mb4_0900_ai_ci`, JSON/`JSON_OBJECT()` expression defaults, `JSON_TYPE`, enforced CHECK, FK, dan beberapa descending index/ROW_FORMAT=DYNAMIC. App memakai BinaryUuid dan provider/session khusus untuk kompatibilitas UUID.
- `utf8mb4_unicode_ci` default koneksi tidak menghilangkan collation 0900 yang eksplisit di tabel. MariaDB/SQLite bukan pengganti drop-in; tidak diuji dan tidak direkomendasikan. Tidak ada constraint versi MySQL eksplisit pada composer.json. Inferensi lantai fitur MySQL 8.0.16 (CHECK enforcement; expression defaults sejak 8.0.13), **bukan minimum yang sudah diuji**.
- Metadata read-only menemukan **0 trigger, 0 stored routine, 0 event**; source app/migrations juga tidak mendefinisikannya. Tidak perlu mengarang ekspor routines/events.
- Gunakan mysqldump `--single-transaction --quick --hex-blob --default-character-set=utf8mb4 --triggers --skip-routines --skip-events --no-tablespaces --set-gtid-purged=OFF --column-statistics=0 --result-file=...` pada **sipora**, bukan database tes. Command lengkap, pembuatan schema baru dan restore CLI/HeidiSQL ada pada setup.
- Prepared database dan media adalah satu snapshot. Jangan `migrate:fresh`, `migrate:fresh --seed`, reset/reseed/rollback saat memakai SQL demo. Periksa migrate:status; jangan otomatis menerapkan migrasi jika snapshot/kode belum cocok.

## 4. Storage, media dan manifest

| Folder/disk | Kegunaan dan akses |
| --- | --- |
| `storage/app/public` / public | Foto profil dan berkas public; route foto tetap dapat memeriksa privacy/owner |
| `storage/app/private` / private, local | Identity submission dan gambar feed yang disajikan melalui controller sesuai izin |
| `storage/app/portfolio-evidence` | Evidence portfolio dan external certificate privat |
| `storage/app/community-media` | Logo komunitas; disajikan controller dengan gate status/akses |
| `storage/app/activity-media` | Poster aktivitas; disajikan controller |
| `storage/app/proposal-documents` | Dokumen proposal privat |
| `storage/app/program-logbook-media` | Media logbook privat |
| `storage/app/financial-receipts` | Bukti transaksi privat |
| `public/media/demo` | Aset demo kanonik/cover/gallery/PDF untuk source manifest; **315 file, 109,50 MiB** |
| `public/data` | Geometri lokal dan metadata peta; **14 fitur kecamatan** pada GeoJSON |

Seluruh disk storage di atas harus disalin; tidak boleh dipindah ke public untuk memperbaiki 404. `public/storage → storage/app/public` adalah link yang dikonfigurasi, **belum dibuat pada mesin audit**. Foto/controller utama tetap berhasil membaca storage langsung. Di tujuan rekomendasinya buat ulang link dengan `storage:link`, terutama bila ada URL `/storage`; link bukan pengganti copy file dan tidak boleh mengarah ke private.

Audit path kolom DB berikut (termasuk baris soft-deleted karena query tabel) menemukan **90 referensi berkas, semuanya ada**: foto 20, logo 15, poster 15, proposal 16, logbook 8, feed 15, portfolio evidence 1. Kolom identity document, financial receipt, achievement evidence dan certificate file saat ini memiliki 0 referensi berkas. Karena itu ketersediaan **seluruh kategori evidence** tidak boleh diasumsikan dari contoh aset statis saja. Pemeriksaan ini tidak membuka atau mencetak isi dokumen privat dan bukan pemeriksaan setiap referensi JSON/semua calon aset planned.

`DemoAssetManifest::preflight()` lulus: **181 available**, **115 planned**, **0 konflik ownership**. Planned memang belum tersedia; jangan tampilkan seolah semua asset pack sudah selesai. Seeder memetakan sumber public ke nama hash deterministik pada disk storage. Jangan seeding ulang atau mengacak mapping untuk memperbaiki perpindahan komputer.

`storage/app` total **3.215,06 MiB (~3,14 GiB)**, dengan `public` sekitar **3.167,35 MiB**. Sebagian besar ukuran berada pada storage public, bukan artefak QA. Folder `*-qa`/`tmp` terpisah boleh tidak disalin, tetapi jangan mengecualikan atau membersihkan isi disk runtime hanya untuk mengurangi ukuran tanpa audit referensi yang lebih lengkap. Audit ini tidak menghapus berkas lama.

## 5. Audit URL dan layanan eksternal

Kategori: **A** blocker alur inti yang disebutkan bila konfigurasi tetap membutuhkan layanan; **B** fitur online opsional; **C** degradasi visual; **D** development/dokumentasi/contoh saja. URL localhost, namespace SVG/W3C, data URI SVG inline, dan schema JSON bukan permintaan internet eksternal.

| Sumber / URL | Lokasi source | Tujuan | Kategori dan dampak offline | Penanganan |
| --- | --- | --- | --- | --- |
| `www.google.com/recaptcha/api.js`, `/recaptcha/api/siteverify` | components/auth-form.blade.php:18, RecaptchaService.php:24, config/recaptcha.php | Token browser dan verifikasi server untuk login/register/forgot password | **A kondisional**: konfigurasi aktif memakai enabled=true; tanpa internet alur auth gagal tertutup, browser menunggu token dan server timeout 8 detik | Salinan lokal memakai disabled flag eksplisit; produksi tetap fail closed |
| `accounts.google.com/o/oauth2/auth`, `www.googleapis.com/oauth2/v4/token`, `/oauth2/v3/userinfo`, `/oauth2/v3/certs` | vendor/laravel/socialite/src/Two/GoogleProvider.php, config/services.php, GoogleAuthController.php | OAuth redirect, token, profil/cert | **B**: login Google tidak dapat selesai | Manual email/password, akun terverifikasi; redirect URI harus cocok bila online |
| `fonts.googleapis.com/css2`, `fonts.gstatic.com` | layouts/public.blade.php:15–18, workspace.blade.php:158–160, auth.blade.php:12–14, base.blade.php:5, design-system/index.blade.php:15–16 | Plus Jakarta Sans dan JetBrains Mono | **C** pada app, **D/C** design system; fallback sistem tersedia, layout/metrics berubah; cold-load dapat menunggu kegagalan stylesheet | Pakai fallback; local font packaging hanya usulan setelah lomba, tidak diunduh/diedit lisensinya |
| `cdn.jsdelivr.net/npm/accessibility-widgets@latest/widget.js` | layouts/public.blade.php:60 | Widget aksesibilitas eksternal | **B**, widget tidak tersedia offline; script tanpa async/defer dapat menunda parse/DOMContentLoaded saat koneksi buruk | Terima batas fitur dan uji cold browser; versi latest juga tidak reproducible. Tidak otomatis mengunduh atau mengganti library |
| `www.google.com/maps/embed?...`, `www.google.com/maps?cid=...` | config/sipora.php:14–15, public/contact.blade.php iframe/link | Peta lokasi layanan kontak | **B**: iframe gagal/blank, konten kontak lainnya tetap server-rendered | Template kompetisi mengosongkan embed/Maps link. URL/alamat sumber pemilik project tidak diganti/ditebak |
| `geoservices.big.go.id/.../MapServer/0` | ecosystem-map/index.blade.php:43 | Link atribusi sumber geometri | **B** untuk klik sumber; **bukan** fetch geometri runtime | Tetap gunakan GeoJSON lokal, tidak mengunduh tile |
| Instagram/Facebook/LinkedIn/TikTok, website dan external opportunity URLs | YouthPortfolioService.php:225–229, form community, record DB, config/sipora contact | Tautan profil/community/pendaftaran eksternal | **B** ketika diklik; bukan gambar/script runtime remote | Demo listing/detail/bookmark lokal; jangan menjanjikan tujuan eksternal aktif offline |
| `https://demo.sipora.test/...`, `opportunity.sipora.test/...`, `example.test/...` | DemoEcosystemSeeder, OpportunitySeeder, OrganizationSeeder, QA seeders | URL dummy fiktif | **D/B** sample data, tidak boleh dianggap layanan nyata | Hindari navigasi keluar saat demo; mapping deterministic dipertahankan |
| `sqs.us-east-1.amazonaws.com/...`, S3/SES/Postmark/Resend/Slack config | config/queue.php, filesystems.php, services.php | Default scaffold integrasi yang dapat diaktifkan | **D** pada profil audit (queue database, disk lokal, mail log), menjadi online-only jika operator mengaktifkan driver cloud | Gunakan template lokal; tidak ada credential layanan dalam template |
| Dokumentasi MDN, Composer/npm schema/registry/source repos pada lock, SVG namespaces | config comments, composer/package files, docs, inline SVG | Referensi/build/install/metadata | **D**, tidak dibutuhkan per-request setelah vendor/build tersedia | Bawa dependencies, jangan install/update saat offline |
| URL `http://127.0.0.1:8000/...` | public/media/demo/08F_NOTIFICATIONS/index.html dan notification-mapping.json | Board/demo mapping statis dari mesin asal | Lokal, **D** terhadap UI native; port/host/ID snapshot bisa stale setelah pindah | Utamakan UI native yang memakai NotificationActionResolver dan route; board statis bukan tes notifikasi lintas host |

Pencarian source `app`, `bootstrap`, `config`, `resources`, `routes`, `database`, serta HTML/JS/CSS/JSON publik tidak menemukan CDN runtime lain untuk Alpine/GSAP/SweetAlert/Chart/Leaflet, external image source hardcoded untuk kartu inti, atau tile provider aktif. User-entered URL/asset masa depan tetap dapat memperkenalkan dependency baru; ini audit snapshot, bukan jaminan permanen.

### Google Fonts

CSS app mendefinisikan `--font-sans: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif` dan `--font-mono: 'JetBrains Mono', ui-monospace, monospace` (resources/css/app.css sekitar 317–320). Heading memakai stack yang diturunkan dari font tema. UI tetap punya font lokal sistem; ukuran line wrap bisa berbeda. Tidak ada font file yang didownload atau lisensi yang diubah. Self-host font dapat direncanakan terpisah setelah pemeriksaan lisensi; bukan bagian fase ini.

### OAuth, reCAPTCHA dan email

- Google credentials aktif saat audit (hanya keberadaan boolean dicatat, nilai tidak dicetak). OAuth membutuhkan internet serta exact redirect URI; .test/localhost/127.0.0.1 dan port harus dievaluasi sebagai URI yang berbeda. `GoogleAuthController` menangani konfigurasi kosong/kegagalan callback dan manual login tetap tersedia. Tidak ada OAuth yang dihapus.
- `AuthRequest` mengharuskan token bila enabled atau environment bukan local/testing. `RecaptchaService` hanya return tanpa Google jika **disabled + local/testing**. Production tetap memverifikasi/menolak meskipun flag false. Karena dukungan ini sudah ada, **tidak perlu perubahan kode atau bypass baru**. Template memakai APP_ENV=local/APP_DEBUG=false; tidak memberi nama environment baru yang tidak didukung. Jangan gunakan profil ini pada host publik.
- Mail aktif **log**, bukan SMTP; driver SMTP/log/array sudah ada pada config/mail.php. Link reset/verifikasi tetap dibuat dengan mekanisme signed token biasa, hanya tidak dikirim ke inbox. Pilih akun demo terverifikasi; 44 Youth, 1 Admin, 1 Verifier ditemukan terverifikasi. Verifikasi tidak dilemahkan; availability password/manual login per akun harus direhearsal privat.
- Template queue sync menghindari worker untuk profil demo lokal. Audit tidak menemukan job app `ShouldQueue`/dispatch yang menjadikan worker eksternal syarat inti saat ini. Jika konfigurasi diubah ke SMTP/cloud/queue database di masa depan, audit ulang.

### Ecosystem Maps dan kontak

`resources/js/ecosystem-map.js` mengimpor Leaflet/CSS dari npm, fetch hanya `canvas.dataset.geometryUrl` yang dirender menjadi `asset('data/pemalang-kecamatan.geojson')`. Tidak ada `L.tileLayer`, Google Maps API, basemap remote, atau API key map untuk peta agregat. Batas 14 kecamatan dan data agregat lokal cukup untuk peta interaktif. Error handler menampilkan pesan dan tabel bila geometri gagal. Google Maps **kontak** adalah iframe berbeda dan membutuhkan internet. Tidak ada tile dataset/koordinat baru yang diunduh/dibuat.

## 6. Validasi yang dijalankan

| Pemeriksaan | Hasil |
| --- | --- |
| `composer check-platform-reqs` | Semua constraint installed vendor lulus pada PHP 8.4.15 |
| `php artisan about` | Laravel 13.33.0, local, locale id, mail log, cache database, session binary-database; key ada; public/storage belum linked |
| `php artisan migrate:status` | **30 Ran**, tidak ada migrasi yang dijalankan |
| `npm run build` | Lulus; 89 modules, 15,13s |
| Build dependency graph | 14 manifest entries, tidak ada chunk/asset/import hilang, hot file tidak ada |
| `php artisan test` | **400 passed, 3.383 assertions**, 217,70s; hanya DB `sipora_regression_test` dan disk framework/testing |
| `node --test tests/Frontend/*.test.mjs tests/js/*.test.mjs` | **31 passed**, 0 failed |
| Tambahan auth smoke memakai service asli | **2 passed, 9 assertions**, 15,09s: manual login tanpa token Google pada local dan produksi tetap fail closed |
| Media DB dan manifest | 90 referensi file diperiksa, 0 hilang; 181 available valid, 115 planned, 0 ownership conflict |
| Server smoke pada snapshot demo | **18 GET/halaman endpoint status 200**; MySQL transaksi READ ONLY, session/cache array dari sebelum bootstrap, outbound Laravel HTTP diblokir |

Server smoke mencakup `/`, `/login`, Activity/Community/Program/Opportunity directories, Forum, Feed, Statistics, Ecosystem Maps, Contact, Youth Home/Passport, workspace pengelola C001, `/chat/rooms`, `/chat`, Admin dan Verifier dashboard. User untuk rendering diset pada guard dalam proses audit, **bukan bypass yang dipasang pada aplikasi**. POST login yang sebenarnya diperiksa terpisah pada DB tes dengan middleware CSRF dan RecaptchaService asli, bukan pada demo data.

Batas smoke: ini request kernel PHP, bukan browser dengan seluruh jaringan eksternal diputus. Browser belum diuji di komputer kedua, pengiriman chat tidak dilakukan pada demo snapshot, OAuth/SMTP nyata tidak dicoba offline, SQL belum diekspor/direstore, public/storage belum dibuat. Suite frontend Node memeriksa logika, bukan membuktikan pixel/layout dan kecepatan cold browser offline.

Satu percobaan harness chat semula ditolak MySQL READ ONLY karena rate limiter sudah terikat cache database saat bootstrap; harness diperbaiki dengan memilih array sebelum bootstrap dan seluruh endpoint kemudian 200. Satu percobaan harness auth semula 419 karena APP_ENV=local mengaktifkan CSRF nyata; token sesi disertakan dan tes lulus. Keduanya masalah konfigurasi harness, tidak ada kode produk yang diubah untuk meloloskan tes. DB demo dilindungi READ ONLY dan tidak direset/reseed.

Output diagnostik lokal ada di ignored `output/offline-audit/` (inventory/data/smoke JSON, hasil build/backend/frontend, harness audit). Tidak menjadi bagian paket runtime atau commit. Jangan menjalankan harness yang bergantung pada snapshot asal sebagai installer; gunakan checklist operasional.

## 7. Perubahan dan langkah sebelum kompetisi

Dibuat tiga dokumen audit/setup/quick start dan `.env.competition.example`; `.gitignore` hanya ditambah exception agar template tanpa secrets bisa dilacak. `.env` aktif, database bisnis, auth produksi, font, widget, peta, workflow dan mapping tidak diubah. Build output lokal diperbarui lewat Vite. Tidak ada commit/push otomatis.

Prioritas persiapan:

1. Siapkan akun manual terverifikasi untuk setiap role, selesaikan kewajiban ganti password melalui alur normal; simpan daftar kredensial privat.
2. Ekspor DB demo dengan hex-blob, freeze kode/SQL/media pada titik yang sama; copy vendor/build dan disk runtime lengkap. Planned assets tetap ditandai belum tersedia.
3. Siapkan installer offline PHP 8.4/MySQL 8.0.30/Laragon dan alat rebuild bila diinginkan. Pertahankan intended demo APP_KEY dalam konfigurasi privat; jangan bawa secrets online yang tidak diperlukan.
4. Gunakan template pada **salinan kompetisi**: local + reCAPTCHA false + mail log + URL/database benar. Jangan mengubah produksi atau memakai APP_ENV=competition.
5. Restore SQL ke DB baru di komputer kedua; clear cache, recreate link public, platform/migration/build checks, lalu uji seluruh alur dengan internet putus.
6. Catat waktu cold browser serta perbedaan font/widget; siapkan screenshot/video cadangan dan daftar fitur online opsional. Jika ini mengganggu presentasi, rencanakan perubahan terpisah dengan scope jelas.
7. Setelah rehearsal sukses, buat immutable ZIP freeze, SQL/media backup dan SHA256; verifikasi salinan flashdisk/komputer tujuan; bawa salinan kedua.

Risiko tersisa bukan error suite: restore/copy belum direhearsal pada hardware lain; media public cukup besar; symlink Windows perlu dukungan/izin; key snapshot wajib benar; request Google Fonts/CDN tetap dapat menunda cold load pada koneksi buruk; widget/OAuth/Maps kontak tidak tersedia offline; 115 planned assets dan beberapa kategori evidence DB memang belum terisi. Karena itu status yang tepat adalah **siap dikemas dengan langkah prasyarat yang terdokumentasi**, belum “sudah lulus demo offline pada komputer lain”.
