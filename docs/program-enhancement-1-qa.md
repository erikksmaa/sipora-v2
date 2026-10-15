# Program Enhancement 1 — Quota, Selection, Participant Lifecycle

Tanggal validasi: 14 Oktober 2026. Tidak ada commit otomatis.

## 1. Arsitektur

`ProgramIntakeService` menjadi pusat konfigurasi, eligibility, kuota, pendaftaran, seleksi, withdrawal, notifikasi, audit, dan ringkasan. Controller hanya menangani request/context. `ProgramIntakeRules` dipakai ulang pada form Program dan pengaturan intake.

Roster peserta diturunkan dari `ProgramApplication` berstatus `accepted` melalui `Program::participants()`. Tidak ada tabel peserta duplikat dan tidak ada pendaftaran Activity otomatis.

## 2. Schema

Tambahan kolom pada `programs`: `registration_enabled`, `selection_mode`, `quota`, `registration_open_at`, `registration_close_at`.

Tabel baru `program_applications`: UUID binary, Program, Youth, status, catatan review, reviewer, waktu review, timestamps. Unique `(program_id, user_id)` mempertahankan satu riwayat pendaftaran per Youth/Program. Index `(program_id, status)` dan `user_id` mendukung daftar serta pencarian riwayat. FK dan CHECK menjaga integritas status, mode, kuota, dan urutan tanggal. Application tidak memakai soft delete.

Kontrak SQL canonical ikut ditambahkan delta yang sama.

## 3. Konfigurasi Program

Pendaftaran default nonaktif sehingga Program existing tidak otomatis menerima pendaftar. Mode `open` atau `review`; kuota kosong berarti tanpa batas, angka harus positif. Batas tanggal opsional dan bisa diisi salah satunya saja. Input/tampilan WIB, penyimpanan UTC. Closing sebelum opening ditolak termasuk pembaruan parsial.

Pengelola boleh menyiapkan konfigurasi saat `planned` atau `running`. Pendaftaran hanya saat `running`, Proposal terakhir disetujui, dan Community aktif/publik menurut discovery existing. Scope publik existing tidak diperluas ke Program planned.

## 4. Status aplikasi

`submitted` (Diajukan), `under_review` (Ditinjau), `accepted` (Diterima), `waitlisted` (Daftar Tunggu), `rejected` (Tidak Lolos), `withdrawn` (Mengundurkan Diri).

Transisi review: submitted menuju under_review/accepted/waitlisted/rejected; under_review menuju accepted/waitlisted/rejected; waitlisted menuju under_review/accepted/rejected. Accepted/rejected/withdrawn tidak bisa diubah melalui tindakan reviewer. Youth boleh menarik status aktif saat Program planned/running. Riwayat tetap tersimpan; tidak dapat mendaftar ulang.

## 5. Kuota

Hanya accepted memakai kuota. Semua mutasi mengunci row Program dalam transaksi sebelum memeriksa kapasitas dan membuat/mengubah application. Constraint unique juga mencegah duplikasi. Optimistic `expected_status` menolak keputusan dari halaman yang sudah stale. Kuota tidak dapat diturunkan di bawah peserta accepted; mode tidak dapat diganti setelah ada riwayat pendaftaran. Withdrawal accepted melepaskan kapasitas.

## 6–7. Pendaftaran terbuka dan selektif

Open langsung menghasilkan accepted selama eligible dan kapasitas tersedia. Review menghasilkan submitted, termasuk ketika kuota accepted sudah penuh; pengelola bisa menempatkan pemohon dalam daftar tunggu. Penerimaan dari review tetap memeriksa kapasitas secara atomik.

## 8. Pengelolaan peserta

Halaman Pendaftar & Peserta memiliki ringkasan enam status, kuota/sisa, pencarian akun, filter status, dan pagination 15 record. Filter Diterima merupakan daftar peserta aktif. Informasi pemohon memakai identity/Portfolio projection existing; minat/skill hanya tampil bila visibilitas publik mengizinkan. NIK, dokumen identitas, dan evidence privat tidak ditampilkan.

## 9. Youth

Detail publik Program menampilkan jenis pendaftaran, periode, kuota, alasan blokir, tombol daftar, status milik Youth, catatan keputusan sendiri, dan tindakan withdrawal. Eligibility memakai onboarding existing. Tidak ada grant role, perubahan identity verification, atau enrollment Activity.

## 10. Notifikasi dan audit

Memakai kanal database SIPORA existing, tanpa dependency SMTP. Notifikasi dibuat saat mendaftar serta keputusan accepted/waitlisted/rejected; under_review tidak menimbulkan notifikasi tambahan. Data notifikasi hanya referensi Program/status. Link diverifikasi terhadap pemilik application dan public scope; identifier malformed tidak menyebabkan exception. Konfigurasi, pendaftaran, review, dan withdrawal tercatat melalui audit existing.

## 11. Authorization

Contextual Community leader/manager mengelola Program miliknya. Verifier dapat melakukan review atas Program dengan Proposal approved dan tidak menjadi pengelola Community yang sama. Member biasa, Youth lain, lintas Community/Program, dan global Admin tanpa hak contextual tidak memperoleh akses baru. Semua checks dilakukan server-side; route manager mempertahankan Community aktif dan shell Community, route Verifier memakai shell Verifier.

## 12. File fase ini

Baru:

- `app/Services/Program/ProgramIntakeService.php`, `ProgramIntakeRules.php`
- `app/Models/ProgramApplication.php`
- `app/Notifications/ProgramApplicationChanged.php`
- `app/Http/Controllers/ProgramApplicationController.php`
- `app/Http/Controllers/Manager/ProgramIntakeController.php`
- `database/migrations/2026_10_14_000001_add_program_intake.php`
- `resources/views/components/program/application-status.blade.php`
- `resources/views/manager/programs/_intake-fields.blade.php`, `intake.blade.php`
- `resources/views/programs/applications.blade.php`, `intake-index.blade.php`
- `resources/views/public/programs/_intake.blade.php`
- `tests/Feature/ProgramIntakeTest.php`, `ProgramIntakeConcurrencyTest.php`
- `tests/Support/ProgramIntakeFixtures.php`, `program-intake-worker.php`
- Laporan ini.

Diubah:

- `app/Actions/Manager/SaveProgramAction.php`
- `app/Http/Requests/Manager/StoreProgramRequest.php`
- `app/Http/Controllers/PublicProgramController.php`
- `app/Models/Program.php`, `User.php`
- `app/Policies/ProgramPolicy.php`
- `app/Services/NotificationActionResolver.php`
- `routes/manager.php`, `verifier.php`, `youth.php`
- `resources/views/layouts/workspace.blade.php`
- `resources/views/manager/programs/_form.blade.php`, `show.blade.php`
- `resources/views/public/programs/show.blade.php`
- `docs/database/sipora_v2_mysql_backend_driven_clean_v2.sql`

## 13. Migration dan routes

Migration sudah diterapkan pada development database `sipora` (batch 2, Ran). Rollback juga dijalankan melalui isolated concurrency test database.

Routes tambahan: Youth apply/withdraw; manager intake GET/PATCH dan applications GET/review POST; Verifier catalog serta applications GET/review POST. `route:list` memverifikasi empat route pengelolaan applications dan tiga intake routes. View cache berhasil dikompilasi.

## 14–16. Tests dan build

- Targeted Program/domain regression: **53 passed, 413 assertions** (sebelum tambahan terakhir untuk notification resolver).
- Full regression terakhir: **309 passed, 2.567 assertions, 282,15 detik**.
- Concurrency nyata memakai dua proses PHP dan koneksi MySQL independen: penerimaan seat terakhir, open registration seat terakhir, dan pendaftaran simultan oleh Youth yang sama. Ketiganya menjaga kapasitas/keunikan.
- Syntax PHP pada 21 file fase ini: lulus. `git diff --check`: lulus.
- `npm run build`: lulus, 79 module, 12,29 detik; tanpa dependency baru.
- `php artisan view:cache`: lulus.

Pengujian database dijalankan dengan temporary override database/guard ke `sipora_attendance_test`, lalu `tests/TestCase.php` dan `phpunit.xml` dikembalikan byte-for-byte. Worker concurrency sengaja menolak development database. Jangan menjalankan migration reset/test destruktif terhadap data development `sipora`.

Log: `storage/logs/program-intake-regression.log` dan `storage/logs/program-intake-build.log`.

## 17. Manual QA

| Skenario | Hasil |
| --- | --- |
| A. Open Program, kapasitas tersedia | Youth diterima, status sendiri tampil |
| B. Kuota penuh | Pendaftaran/accept diblokir dengan alasan yang jelas |
| C. Selektif | Youth mendapat status Diajukan |
| D. Accept | Peserta aktif bertambah, sisa kuota berkurang |
| E. Waitlist | Daftar Tunggu tampil, tidak memakai kuota |
| F. Reject | Tidak Lolos tampil, tidak memakai kuota |
| G. Status Youth | Status/catatan sendiri tersedia; akun lain tidak melihat application privat |
| H. Unauthorized | Guest ke login; member biasa mendapat 403; Verifier berhasil review tanpa berganti ke shell Community/Youth |
| I. Government workflow | Halaman Program existing tetap menampilkan Proposal, Logbook, E-LPJ, Evaluasi; tindakan domain dicakup regression existing |

Public open/review diperiksa di desktop 1440, tablet 768, mobile 390 dalam light/dark. Halaman manager juga diperiksa di ketiga ukuran; tidak ada overflow halaman, tabel mobile dapat digeser horizontal. Tab dari pencarian mencapai Status lalu Terapkan; keduanya `:focus-visible`. Komponen form/status memiliki label tekstual dan menggunakan theme/font existing.

Bukti screenshot dan ukuran halaman disimpan di `storage/app/program-intake-qa/`. QA memakai akun demo existing; tidak membuat akun atau mengubah password/role/identity. Setelah selesai tiga Program DATA QA milik fase ini dibersihkan bersama 20 application, 26 notifikasi fixture, dan 28 audit fixture. User, Community, dan Program existing dipertahankan; browser logout dan viewport dikembalikan.

## 18. Regression

Program core, Proposal, Logbook, FinancialReport/E-LPJ, Final Evaluation, Activity, Passport, Certificate, Statistics/Maps, pathway, Forum, Feed, onboarding, privacy, dan workspace berada dalam full suite yang lulus. Tidak ada assessment/scoring, gamification, messaging, atau Activity auto-enrollment yang ditambahkan.

## 19. Batas dan masalah tersisa

Tidak ditemukan kegagalan fungsional pada pemeriksaan di atas. Review hanya ketika Program running; planned hanya untuk persiapan konfigurasi sesuai public discovery existing. Mode terkunci setelah application history untuk menjaga lifecycle yang konsisten.

Formatter Pint pada pengecekan terakhir melaporkan file controller yang sedang dipakai tidak writable di Windows; syntax, diff check, full tests, build, dan kompilasi Blade lulus. Tidak ada commit atau merge otomatis.
