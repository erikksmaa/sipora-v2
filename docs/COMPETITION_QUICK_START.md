# SIPORA — checklist cepat di lokasi lomba

Untuk salinan kompetisi; panduan lengkap: [OFFLINE_COMPETITION_SETUP.md](OFFLINE_COMPETITION_SETUP.md).

1. Start PHP/MySQL Laragon. Buka terminal di `C:\laragon\www\sipora-v2`.
2. Pastikan SQL demo sudah direstore ke schema baru `sipora_competition`, media lengkap, `vendor/` dan `public/build/manifest.json` tersedia. Jangan seed ulang.
3. `.env` privat: **APP_ENV=local**, **RECAPTCHA_ENABLED=false**, `MAIL_MAILER=log`, DB tujuan benar, APP_URL cocok, intended demo APP_KEY dipertahankan. Jangan gunakan profil ini pada server publik.
4. Jalankan pemeriksaan:

```powershell
php -v
php -m
mysql --version
composer --version
composer check-platform-reqs
git --version
php artisan optimize:clear
php artisan about
php artisan migrate:status
php artisan storage:link
```

Target: PHP 8.4.x, MySQL 8.0.30, platform lulus termasuk `pdo_mysql`/`bcmath`, migrasi `Ran`, writable storage/cache. Periksa/rebuat broken link dengan panduan lengkap; jangan menimpa direktori media asli. Jangan membawa `public/hot` atau cache konfigurasi mesin asal.

5. **Hanya jika perlu rebuild dan node_modules lengkap:**

```powershell
node -v
npm -v
npm run build
```

Baseline Node 24.x/npm 11.x. Mode run only tidak memerlukan Node/npm. Jangan install/update dependencies di lokasi bila paket siap jalan sudah lengkap.

6. Buka virtual host Laragon dengan document root `public/`, atau fallback:

```powershell
php artisan serve --host=127.0.0.1 --port=8000
```

7. Putus internet dan uji cold browser pada URL APP_URL:

- Beranda, Activity/Community/Program/Opportunity, Forum, Feed, foto dan PDF.
- Login manual akun terverifikasi: Youth, pengelola C001, Admin, Verifier.
- Youth Passport/portfolio; C001 → P001 → A001/A002/A003/A005; proposal/logbook/LPJ.
- Floating chat, global/community; statistik dan 14 batas kecamatan di Ecosystem Maps.
- Navigasi mobile, page-up, tampilan dengan font fallback.

Google login, font Google, widget CDN dan iframe Google Maps kontak membutuhkan internet. Ecosystem Maps internal sudah lokal, tidak memakai tile online. Mail log tidak masuk inbox; gunakan akun terverifikasi untuk demo utama.

**Jangan menjalankan migrate:fresh, migrate:fresh --seed, reset, rollback, atau db:seed pada prepared demo DB. Jangan regenerate APP_KEY yang sudah benar.**

Backup: SQL + media + ZIP freeze + daftar kredensial demo privat + screenshot cadangan; verifikasi SHA256. Full backend test hanya pada `sipora_regression_test` terisolasi sesuai [QA_TESTING_GUIDE.md](QA_TESTING_GUIDE.md), bukan pada database demo.


Golden journey Program pemerintah terbaru dan perintah refresh terarah: [DEMO_GOLDEN_JOURNEY.md](DEMO_GOLDEN_JOURNEY.md).
