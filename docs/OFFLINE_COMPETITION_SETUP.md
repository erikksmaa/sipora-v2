# SIPORA — setup offline untuk lomba

Audit 17 Oktober 2026. Jalankan pada komputer demo terpisah. Jangan mengganti `.env` komputer pengembangan untuk sekadar mencoba panduan ini. Hasil dan batas verifikasi: [OFFLINE_COMPETITION_AUDIT.md](OFFLINE_COMPETITION_AUDIT.md). Checklist di lokasi: [COMPETITION_QUICK_START.md](COMPETITION_QUICK_START.md).

## 1. Siapkan paket sebelum berangkat

SIPORA dapat menjalankan fitur inti tanpa internet apabila PHP/MySQL tersedia, SQL demo sudah dipulihkan, `vendor/`, media, dan `public/build/` lengkap, serta reCAPTCHA dinonaktifkan secara eksplisit pada **APP_ENV=local**. Jangan memakai `APP_ENV=competition`: bypass yang sudah ada hanya menerima `local`/`testing`. Produksi tetap fail closed.

Struktur flashdisk yang direkomendasikan:

```text
SIPORA_COMPETITION/
├── project/sipora-v2/                 # working copy lengkap, termasuk .git
├── database/sipora_demo.sql           # ekspor DB demo, bukan DB tes
├── installers/                       # installer offline yang telah diuji
├── docs/                             # salinan tiga dokumen offline ini
├── backups/
│   ├── SIPORA_PRE_COMPETITION_FREEZE_2026-10-17.zip
│   └── media-storage.zip
└── checksums/SHA256.txt
```

Tidak ada SQL/arsip/installer yang dibuat atau diunduh otomatis oleh audit ini. Buat freeze setelah rehearsal berhasil. Simpan working copy, ZIP yang tidak diedit, SQL, dan backup media secara terpisah; GitHub menjadi cadangan saat online, bukan sumber media yang diabaikan Git. Gunakan flashdisk kedua bila tersedia. Pilih NTFS/exFAT untuk menghindari batas satu file 4 GiB FAT32. Sediakan ruang untuk working copy, arsip, dan SQL sekaligus.

### Isi project yang harus ikut

- `app/`, `bootstrap/`, `config/`, `database/` (termasuk fixtures, seeders/data manifest, migrations), `lang/`, `public/`, `resources/`, `routes/`, `tests/`, `docs/`.
- `artisan`, `composer.json`, `composer.lock`, `package.json`, `package-lock.json`, `vite.config.js`, `phpunit.xml`, seluruh file konfigurasi root lainnya, `.gitignore`, `.env.example`, `.env.competition.example`, dan `.git/` agar riwayat/diff tersedia. `git clone` saja tidak menyalin file ignored.
- **`vendor/`** untuk mode siap jalan, **`public/build/` beserta manifest dan semua chunk** untuk runtime, `public/data/`, `public/media/demo/`, logo dan aset publik lainnya.
- **`storage/app/public/`, `storage/app/private/`, `storage/app/portfolio-evidence/`, `storage/app/community-media/`, `storage/app/activity-media/`, `storage/app/proposal-documents/`, `storage/app/program-logbook-media/`, `storage/app/financial-receipts/`**. Pertahankan struktur/nama file; jangan hanya menyalin foto di `public/media/demo` karena database juga merujuk salinan pada disk privat.
- Struktur writable `storage/framework/cache/data/`, `storage/framework/sessions/`, `storage/framework/views/`, `storage/logs/`, dan `bootstrap/cache/`; isinya yang berupa cache/log lama tidak wajib. Pertahankan file `.gitignore` penjaga folder.
- `node_modules/` untuk mode edit/rebuild offline di Windows x64 yang kompatibel. Ukuran saat audit sekitar 64,95 MiB; ada paket native build, jadi jangan menyalin dari Linux/macOS atau arsitektur lain.
- Siapkan `.env` demo privat: isi template dengan koneksi lokal yang benar dan **APP_KEY demo yang sesuai**. Jangan menaruh secrets Google/SMTP asli di paket publik atau mengomit `.env`.

Yang tidak diperlukan: `output/`, `tmp/`, `storage/app/*-qa/`, `storage/app/tmp/`, `storage/framework/testing/`, `.phpunit.cache/`, `.phpunit.result.cache`, laporan tes, screenshot QA, log lama, sesi dan cache mesin asal. Jangan menghapusnya dari sumber; cukup tidak menyertakannya dalam paket tujuan. Jangan mengecualikan seluruh `storage/`.

Jangan membawa `public/hot` (penunjuk Vite dev server mesin asal), `bootstrap/cache/config.php`, cache route/event/view lama. **Jangan menyalin `public/storage` sebagai folder berisi berkas privat atau tautan ke mesin asal**; buat ulang tautannya di tujuan.

### Dua mode frontend dan Composer

**Run only:** `vendor/` + `public/build/` tersedia; Node, npm, Composer CLI, dan `node_modules/` tidak diperlukan oleh request web. Jangan menjalankan `npm run dev` atau `composer run dev` untuk mode ini. Dynamic import GSAP tetap menjadi chunk lokal hasil Vite.

**Edit/rebuild:** bawa Node/npm dan `node_modules/`. Alternatif `npm ci --offline` hanya berlaku bila SELURUH paket dan optional dependencies sudah ada di npm cache; jangan mengandalkannya tanpa uji. `npm ci` akan mengganti `node_modules`, jadi jangan menjalankannya di lokasi hanya untuk berjaga-jaga. Fresh install Composer menggunakan `composer install --prefer-dist --no-interaction` dari lock, memerlukan cache lengkap/internet. Jangan memakai `composer update`, `--ignore-platform-reqs`, atau `composer run setup` pada demo yang sudah disiapkan; script setup menjalankan key generation dan npm install.

## 2. Ekspor database demo di mesin sumber

Hentikan perubahan data sementara (tutup sesi demo/worker penulis) supaya SQL dan salinan media berasal dari snapshot yang konsisten. Pemeriksaan audit menemukan MySQL **8.0.30**, 69 tabel InnoDB, **0 trigger, 0 routine, 0 event** yang terlihat oleh akun audit. Tidak diperlukan opsi ekspor routines/events; `--triggers` tetap disertakan untuk keamanan bila nanti ditambahkan.

Di terminal Laragon/PowerShell yang mengenali MySQL, setelah memastikan `DB_DATABASE` sumber adalah **sipora**:

```powershell
New-Item -ItemType Directory -Force C:\SIPORA_COMPETITION\database
mysqldump --host=127.0.0.1 --port=3306 --user=root --password --single-transaction --quick --default-character-set=utf8mb4 --hex-blob --triggers --skip-routines --skip-events --no-tablespaces --set-gtid-purged=OFF --column-statistics=0 --result-file=C:\SIPORA_COMPETITION\database\sipora_demo.sql sipora
if ($LASTEXITCODE -ne 0) { throw 'Ekspor gagal; jangan gunakan SQL ini.' }
Get-Item C:\SIPORA_COMPETITION\database\sipora_demo.sql
Get-FileHash C:\SIPORA_COMPETITION\database\sipora_demo.sql -Algorithm SHA256
```

Ganti user/port sesuai instalasi; password dimasukkan lewat prompt, bukan argumen/riwayat shell. Jangan mengekspor `sipora_regression_test`. `--hex-blob` menjaga UUID BINARY(16); `--single-transaction` konsisten untuk InnoDB tetapi jangan jalankan DDL/migrasi bersamaan. `--result-file` menghindari perubahan encoding akibat redirect PowerShell. Tidak memakai `--databases`, sehingga restore dapat diarahkan ke nama database tujuan baru. SQL berisi akun/hash password dan data aplikasi: perlakukan sebagai backup privat.

Gunakan mysqldump MySQL 8 yang kompatibel dengan server. Bila memilih HeidiSQL: pilih schema demo yang tepat, ekspor struktur+data ke SQL UTF-8, pastikan nilai binary utuh, lalu uji restore. Jangan menganggap ekspor sukses sebelum restore rehearsal sukses.

## 3. Instal komputer Windows baru

1. Gunakan Windows 10/11 x64. Instal/start Laragon dari installer offline yang telah diuji. PHP dan MySQL bisa dipasang terpisah, tetapi Laragon memudahkan layanan/PATH. Siapkan runtime Microsoft Visual C++ yang cocok bila distribusi PHP/Apache memerlukannya; jangan menunggu unduhan installer web di lokasi.
2. Pilih PHP **8.4.x x64** yang kompatibel dengan mode server. Baseline CLI audit 8.4.15 NTS VS17; untuk Apache module gunakan paket TS yang sesuai, atau gunakan server bawaan PHP untuk NTS. Pastikan `where.exe php` menunjuk versi yang benar dan extension PHP aktif. CLI dan web server harus memakai konfigurasi yang sama/kompatibel.
3. Pilih/start **MySQL 8.0.30** untuk rehearsal yang identik. Jangan mengganti dengan MariaDB: schema memakai `utf8mb4_0900_ai_ci`, JSON expression defaults dan CHECK constraints. Inferensi minimum fitur MySQL 8.0.16; versi tersebut belum diuji, jadi gunakan baseline yang telah lulus. Jangan asal mengganti collation dump.
4. Instal Composer 2 untuk pemeriksaan/reinstall; API Composer runtime `^2.2` diperlukan oleh lock. Baseline 2.10.3. `vendor/` yang lengkap tetap dapat melayani web tanpa Composer CLI.
5. Untuk rebuild, instal Node.js **24.x Windows x64** beserta npm (baseline 24.21.0/npm 11.19.0). Vite/plugin mengharuskan Node `^20.19.0 || >=22.12.0`; project tidak mendeklarasikan engines/npm minimum sendiri. Gunakan baseline yang sama untuk menghindari incompatibility native modules.
6. Instal Git untuk status/history di lokasi (opsional runtime; direkomendasikan). Browser modern wajib; VS Code opsional untuk edit. Tidak ada Redis, Docker, WebSocket server, API map key, atau Python yang diwajibkan oleh profil lokal ini.
7. Salin `project/sipora-v2` ke **`C:\laragon\www\sipora-v2`**. Jalankan semua perintah berikut dari direktori itu. Lokasi pengembangan audit lebih dalam (`projects\lomba kabupaten\...`), sehingga virtual host mesin asal tidak otomatis berlaku di komputer baru.

```powershell
Set-Location C:\laragon\www\sipora-v2
php -v
php -m
composer --version
node -v
npm -v
git --version
mysql --version
```

Node/npm boleh tidak tersedia dalam run only. PATH yang salah: buka terminal Laragon baru atau perbaiki PATH, jangan install ulang dependency yang sudah dibawa.

## 4. Pulihkan SQL dan konfigurasi lokal

8. Buat database **baru/kosong**, bukan schema demo mesin pengembangan atau database legacy. Contoh berikut sengaja tidak memakai `IF NOT EXISTS`; bila nama sudah ada, hentikan dan periksa sebelum mengimpor.

```powershell
mysql --host=127.0.0.1 --user=root --password --execute="CREATE DATABASE sipora_competition CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;"
```

9. Import SQL ke schema tujuan baru:

```powershell
mysql --host=127.0.0.1 --user=root --password --default-character-set=utf8mb4 --database=sipora_competition --execute="SOURCE C:/SIPORA_COMPETITION/database/sipora_demo.sql"
if ($LASTEXITCODE -ne 0) { throw 'Import gagal; periksa sebelum melanjutkan.' }
```

Sesuaikan lokasi SQL sesungguhnya. HeidiSQL alternatif: sambung ke MySQL lokal, buat/pilih `sipora_competition`, jalankan file SQL UTF-8 dan periksa error, jumlah tabel, serta data. Jangan import menimpa database berisi pekerjaan yang belum dibackup.

10. Jika `.env` belum ada, salin template dengan guard berikut. Jika sudah ada, edit secara manual setelah mencadangkannya secara privat; jangan menimpanya otomatis.

```powershell
if (Test-Path .env) { throw '.env sudah ada; periksa dan backup sebelum mengubah.' }
Copy-Item .env.competition.example .env
```

Isi `DB_USERNAME`/`DB_PASSWORD`, `DB_HOST`, `DB_PORT`, dan `DB_DATABASE=sipora_competition` sesuai tujuan. Hapus `DB_URL` lama bila menyalin profil lain agar tidak mengalahkan DB_*. Pilih **satu** URL: `http://127.0.0.1:8000` untuk fallback atau `http://sipora-v2.test` untuk virtual host. Gunakan hostname yang sama terus selama demo agar cookie/sesi konsisten. `SESSION_SECURE_COOKIE=false` hanya untuk HTTP lokal; jangan gunakan profil ini pada layanan publik.

`APP_ENV=local`, `RECAPTCHA_ENABLED=false`, `MAIL_MAILER=log`, `FILESYSTEM_DISK=private`, `CACHE_STORE=database`, `SESSION_DRIVER=binary-database`. Profil menggunakan queue `sync` agar tidak memerlukan worker terpisah. Aplikasi saat ini tidak mendefinisikan job ShouldQueue; bila nanti memakai queue database, jalankan worker lokal dan siapkan tabelnya. Google client ID/secret boleh kosong; manual email/password menjadi jalur utama. Template mengosongkan embed Google Maps kontak; tidak mengganti alamat atau koordinat.

11. **APP_KEY:** salin intended demo key dari konfigurasi demo yang disiapkan secara privat. Jangan mencetak/commit key. Jika membawa prepared `.env` yang benar, pertahankan key tersebut. Hanya untuk instalasi yang benar-benar belum memiliki key dan tidak perlu membaca ciphertext dari snapshot lama:

```powershell
php artisan key:generate
```

Mengganti key bisa membuat data terenkripsi lama tidak terbaca dan membatalkan sesi/cookie (session encryption aktif). Jika key snapshot hilang, cari backup key dahulu; jangan menganggap key baru memperbaiki dekripsi. Jangan menyalin cookie/sesi aktif mesin asal.

12. Setelah DB benar dan layanan berjalan, bersihkan cache konfigurasi/path mesin asal:

```powershell
php artisan optimize:clear
```

Ini menghapus cache aplikasi (termasuk database cache), bukan data bisnis. Jalankan pada salinan kompetisi, bukan sembarang environment. Pastikan `storage/framework` dan `bootstrap/cache` writable oleh user PHP. Jangan membawa compiled views/path absolut dari mesin asal.

13. Buat ulang symlink publik pada komputer tujuan:

```powershell
php artisan storage:link
```

Jika sudah ada broken link, periksa `Get-Item public/storage -Force | Format-List FullName,LinkType,Target` terlebih dahulu. Pada salinan tujuan, `php artisan storage:unlink` lalu `php artisan storage:link` boleh dipakai setelah memastikan objek itu tautan, bukan direktori media asli. Windows mungkin memerlukan Developer Mode/terminal elevated untuk symbolic link. **Jangan menautkan disk private ke public.** Controller foto sendiri memeriksa izin dan mengalirkan file; tautan ini hanya untuk disk public dan URL `/storage` yang mungkin dipakai.

## 5. Verifikasi dan jalankan

14. Periksa platform:

```powershell
composer check-platform-reqs
php artisan about
```

Aktifkan `pdo_mysql` dan `bcmath` meski tidak muncul pada cek Composer: aplikasi memakainya langsung. Lihat daftar extension audit. `vendor/` harus lengkap; cek platform bukan perintah download. Bila CLI lolos tetapi web gagal, periksa PHP/SAPI/php.ini web.

15. Periksa migrasi tanpa mengubah schema:

```powershell
php artisan migrate:status
```

Semua migration snapshot yang dibawa harus `Ran`. Bila ada pending, kemungkinan kode/SQL tidak satu versi; selaraskan snapshot dan rehearsal dahulu. **Jangan menjalankan `php artisan migrate:fresh`, `php artisan migrate:fresh --seed`, reset, rollback, atau db:seed pada database demo yang sudah dipulihkan.** Manifest deterministik memuat 181 aset tersedia dan 115 planned saat audit; seeding ulang bukan cara memperbaiki file salinan hilang.

16. Pastikan `public/build/manifest.json` dan semua chunk ada, serta `public/hot` tidak ada. Jika perlu dan dependency rebuild tersedia:

```powershell
npm run build
```

Tidak perlu rebuild pada mode run only. Jangan menghapus `node_modules` untuk menguji mode ini; source `@vite` memakai manifest ketika hot file tidak ada. Simpan build yang seversi dengan source/lock.

17. Untuk Laragon, pilih document root `C:\laragon\www\sipora-v2\public`, aktifkan auto virtual hosts bila didukung, lalu Reload/Restart Apache/Nginx. `http://sipora-v2.test` hanyalah konvensi Laragon, bukan domain yang dipastikan sudah dibuat. Pastikan hosts lokal/resolver benar. Alternatif yang telah sesuai dengan APP_URL default template:

```powershell
php artisan serve --host=127.0.0.1 --port=8000
```

Buka `http://127.0.0.1:8000`. Biarkan terminal hidup. Port lain perlu APP_URL yang cocok dan cache konfigurasi dibersihkan. Jangan melayani root project: `.env` dan file private harus tetap di luar document root.

18. Masuk melalui `/login` memakai akun demo email/password yang sudah diverifikasi. Siapkan daftar kredensial secara privat, bukan dalam dokumentasi publik; pastikan setiap akun yang akan dipakai memang memiliki password manual (akun OAuth-only mungkin belum). Verifier/Admin harus aktif dan menyelesaikan ganti password pertama sesuai alur normal sebelum hari lomba. Audit menemukan 44 Youth, 1 Admin, 1 Verifier terverifikasi; ini tidak otomatis membuktikan semua password masih sama dengan seeder.

19. Rehearsal dengan **internet benar-benar terputus**, tetapi localhost/MySQL tetap berjalan. Jalankan checklist di bawah dan simpan screenshot cadangan. Jangan menonaktifkan jaringan laptop utama saat audit sedang berlangsung.

## 6. Smoke test pada komputer kompetisi

| Alur | Lokasi/hal yang diperiksa |
| --- | --- |
| Publik | `/`, `/activities`, `/communities`, `/programs`, `/opportunities`, `/forum`, `/feed` tampil beserta foto |
| Auth manual | `/login`, logout/login ulang pada cold browser; tanpa Google atau token reCAPTCHA |
| Youth | `/youth/home`, profil/foto, `/youth/passport`, portfolio dan bukti privat sesuai izin |
| Community | `/manage/{organization}` melalui switcher komunitas C001, peserta dan evidence |
| Government | `/admin/dashboard`, `/verifier/dashboard`, queue proposal dan LPJ |
| Chat | Tombol floating, daftar room, global/community polling; kirim hanya pesan demo pada salinan kompetisi |
| Analytics | `/statistics`, `/ecosystem-map`: 14 batas kecamatan lokal dan tabel; klik wilayah/filter |
| Golden journey | C001 → P001 → A001/A002/A003/A005; proposal, logbook, LPJ/evidence cocok dengan snapshot |
| Dokumen | Preview/download PDF, avatar, logo, activity cover, feed image; tidak hanya thumbnail |
| Keyboard/mobile | Menu, sidebar, bottom nav, page-up, focus dan layout tanpa font online |

`MAIL_MAILER=log` menulis verification/reset link ke log lokal, **tidak mengirim inbox**. Gunakan akun terverifikasi sebagai skenario utama. Bila mendemokan registrasi/reset, operator dapat membuka link log lokal yang dihasilkan untuk akun demo pada host yang benar; jangan menayangkan log lengkap atau membagikan token. `array` hanya menyimpan email pada proses berjalan dan tidak cocok untuk mengambil link antar-request. Verifikasi email produksi tidak diubah.

Google OAuth butuh internet, credentials valid, dan redirect URI **identik** (scheme/host/port/path) terdaftar di Google; URI localhost dan `sipora-v2.test` tidak dapat dipertukarkan. Uji kompatibilitas domain dengan provider sebelum lomba bila OAuth dipakai; jangan menjanjikan custom `.test` pasti diterima. Google login tetap ada, tetapi lewati saat offline.

Ecosystem Maps sudah memakai Leaflet bundled + `public/data/pemalang-kecamatan.geojson`; **tidak ada basemap tile online**. Link sumber BIG hanya atribusi. Jika geometri hilang, ada error message dan tabel tetap tersedia. Google Maps kontak adalah iframe online terpisah yang bisa dikosongkan dengan profil kompetisi.

## 7. Troubleshooting

| Gejala | Tindakan praktis |
| --- | --- |
| PHP salah / platform mismatch | `where.exe php`, `php --ini`, pilih PHP 8.4 Laragon dan buka terminal baru. Selaraskan PHP CLI/web; jangan ignore platform requirements. |
| Missing extension | Aktifkan extension di php.ini yang ditunjuk `php --ini`, restart layanan, ulang `php -m` dan platform check. `pdo_mysql` untuk DB, `bcmath` untuk LPJ keuangan. |
| DB connection refused | Start MySQL, cocokkan host/port/username, periksa konflik layanan di 3306 dan akses loopback/firewall. |
| Unknown database | Buat schema tujuan baru, import SQL yang benar, cocokkan DB_DATABASE; periksa DB_URL lama/config cache. |
| Import gagal / collation unknown | Pastikan MySQL 8, bukan MariaDB; SQL/hash utuh, ruang cukup, privilege schema benar, UUID memakai hex-blob. Hentikan dan pulihkan ke schema baru setelah diagnosis; jangan lanjut dengan import parsial. |
| APP_KEY missing / decryption error | Pasang intended demo key dari backup privat; generate hanya bila memenuhi syarat langkah 11. Bersihkan cookie/sesi lokal setelah perubahan key/host yang disengaja. |
| Foto/evidence 404 | Cocokkan database dan storage snapshot; periksa semua disk privat/public, izin baca, public/media/demo. `storage:link` tidak menggantikan file yang hilang. |
| Storage link rusak | Periksa target link di mesin tujuan dan buat ulang seperti langkah 13; jangan recursive delete folder media. |
| Vite manifest/build hilang | Salin seluruh public/build hasil build yang cocok atau rebuild dengan dependency tersedia. Pastikan public/hot mesin asal tidak ikut. |
| Browser mencoba port 5173 | Hot file atau konfigurasi dev tertinggal. Stop dev server; singkirkan hot file hanya pada salinan tujuan setelah pemeriksaan. |
| Node/npm tidak ditemukan | Run only tetap bisa berjalan. Untuk rebuild gunakan installer offline Node 24 x64 dan terminal baru; jangan npm ci tanpa cache/internet. |
| Storage/cache permission | Pastikan user PHP punya hak tulis pada storage/framework, storage/logs, bootstrap/cache; buat folder struktur yang belum ada. |
| Google login offline | Gunakan manual login. Jangan mengubah verifikasi email atau role demi melewati error. |
| reCAPTCHA gagal / submit menunggu Google | Pada salinan laptop lokal periksa APP_ENV=local dan RECAPTCHA_ENABLED=false, lalu optimize:clear. `APP_ENV=competition`/production tetap fail closed. Jangan menonaktifkan pada server publik. |
| Email tidak masuk | Log transport memang tidak mengirim inbox; gunakan akun terverifikasi atau link log lokal untuk akun demo. SMTP membutuhkan server terjangkau. |
| Peta kosong | Periksa request lokal GeoJSON dan chunk Leaflet; tidak perlu mengunduh tile. Tabel agregat adalah fallback. Google Maps kontak perlu internet. |
| Domain .test tidak resolve | Reload Laragon/virtual host, periksa hosts/document root. Gunakan artisan serve di 127.0.0.1 dan samakan APP_URL. |
| Font berbeda / widget aksesibilitas hilang | Google Fonts/CDN gagal offline; font sistem tetap digunakan dan widget eksternal tidak tersedia. Simpan screenshot cadangan. Jangan mengunduh font/tile tanpa perencanaan terpisah. |
| First load lambat tanpa internet | Font stylesheet dan script CDN masih melakukan percobaan koneksi; ukur cold browser rehearsal. Audit ini tidak menghapus layanan eksternal atau menjamin waktu muat cold browser. |

## 8. Integritas salinan dan backup

Hash beberapa artefak besar, bukan ribuan file tanpa kebutuhan:

```powershell
Get-FileHash C:\SIPORA_COMPETITION\database\sipora_demo.sql -Algorithm SHA256
Get-FileHash C:\SIPORA_COMPETITION\backups\SIPORA_PRE_COMPETITION_FREEZE_2026-10-17.zip -Algorithm SHA256
Get-FileHash C:\SIPORA_COMPETITION\backups\media-storage.zip -Algorithm SHA256
```

Simpan hash sumber di `checksums/SHA256.txt`, hitung ulang dari flashdisk dan komputer tujuan, bandingkan nilai identik sebelum extract/import. Hash ZIP membuktikan salinan arsip utuh, bukan otomatis membuktikan semua media aplikasi telah terpaket; itu tetap perlu smoke test/restore rehearsal.

Urutan freeze yang disarankan: validasi kode → tutup aktivitas penulis → ekspor SQL → salin semua media runtime → siapkan profil lokal privat dengan key yang sesuai → arsipkan → hash → restore di komputer kedua → putus internet → uji semua role/dokumen → simpan screenshot cadangan → salin ke flashdisk kedua. Jangan mengubah ZIP freeze; edit hanya working copy.
