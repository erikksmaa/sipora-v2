# SIPORA v2 — Critical Flow Regression & Debugging

Tanggal: 17 Oktober 2026, Asia/Bangkok. Branch: `feature/global-community-chat`.

## Hasil dan batas interpretasi

Laporan ini membedakan tes integrasi HTTP/service pada MySQL terisolasi, tes JavaScript dengan DOM/browser stubs, pemeriksaan browser nyata, dan audit data read-only. Lulus test case tidak berarti seluruh kombinasi role, viewport, browser, dan perangkat fisik telah diuji manual.

Hasil akhir backend: **400 lulus, 3.383 assertions, 0 failures/errors/skipped**, durasi 406,66 detik. Hasil frontend: **31 lulus**, tanpa kegagalan. Vite build berhasil, 89 modules, 35,88 detik. Daftar route: 301. Tidak ada migration aplikasi baru. Tidak ada commit/push.

Total coverage yang dihitung: 400 backend test cases dan 31 Node test cases; 99 observasi browser terstruktur tersimpan, ditambah checkpoint interaksi yang dijelaskan pada matriks. Angka ini bukan klaim 400 perjalanan pengguna manual. Semua lima ukuran viewport yang diminta tercakup pada sampel representatif; tidak ada overflow horizontal pada observasi yang disimpan.

## 1. Baseline dan keamanan pengujian

- Worktree sudah memiliki banyak perubahan dan file baru dari pekerjaan sebelumnya. Tidak ada reset Git, checkout, stash, commit, atau push.
- Seluruh migration development sudah berstatus `Ran`.
- Ditemukan `phpunit.xml` memaksa database `sipora`; `Tests\TestCase` justru menolak database selain database demo itu. Menjalankan Feature suite tanpa perbaikan akan membuat `RefreshDatabase`/`DatabaseMigrations` mereset data demo.
- Diperbaiki sebelum full suite: database baru `sipora_regression_test`, guard environment/driver/database, storage fixture lokal terpisah, dan worker concurrency menggunakan database yang sama dengan parent.
- Snapshot development sebelum tes: 69 tabel dan 7.595 file media lokal. Snapshot hanya menyimpan jumlah/checksum, bukan isi dokumen atau kredensial.
- Baseline frontend: 29/29 lulus. Unit baseline pertama dalam sandbox tidak dapat membuka bootstrap; dicatat sebagai kendala lingkungan, bukan product failure. Proses PHP/database berikutnya dijalankan dengan akses yang disetujui tool.
- Putaran backend pertama yang aman: **377 test cases, 5 failures + 1 error, 0 skipped**. Tujuh case guard keselamatan sudah termasuk pada baseline ini. Lima failure adalah assertion UI/privacy lama; satu error berasal dari unit test yang tanpa sengaja mengakses tabel permissions.
- Setelah perbaikan awal: 61 tes terarah lulus; 9 tes evaluation lulus; kemudian satu putaran penuh **398 lulus / 3.374 assertions**. Dua edge case avatar ditemukan sesudah putaran tersebut dan dimasukkan ke putaran akhir.

## 2. Temuan berdasarkan severity

| ID | Severity | Reproduksi / dampak | Perbaikan dan bukti |
| --- | --- | --- | --- |
| R01 | P0, risiko pengujian | Reset suite diarahkan ke DB demo `sipora`; bukan temuan auth bypass produksi. | Konfigurasi dan guard sekarang hanya menerima MySQL `sipora_regression_test` dalam environment `testing`; 7 case guard mencakup penolakan DB development, driver/environment salah, nama mirip, dan null. Storage seeder juga diisolasi. |
| R02 | P2 | `/activities?q[]=web` menghasilkan 500 `Array to string conversion`, terkonfirmasi browser; pola serupa ada pada filter lain. | Middleware memvalidasi bentuk scalar untuk filter yang memang scalar. JSON mendapat 422 + field errors; HTML mendapat halaman Indonesia dengan tautan menghapus filter. 17 input malformed + 1 case pencarian normal/karakter khusus. Pemulihan tautan diperiksa di browser. |
| R03 | P2, accessibility/responsive | Header memakai desktop `xl`/1280 px, tetapi drawer mengakhiri scroll lock/focus trap pada 1024 px. Tes awal gagal pada 1024. | Samakan public drawer ke 1280; workspace tetap 1024. Tes 390/768/1024/1100/1279 dan resize ke 1280. Browser 1100: scroll lock aktif, Escape mengembalikan fokus. |
| R04 | P2, performance | Query antrean evaluasi naik dari 20 menjadi 29 saat jumlah Program naik dari 1 ke 4. | Eager load latest Proposal/E-LPJ dan `withExists` Logbook approved; assessment memakai data preload, dengan fallback query untuk evaluasi transaksi yang baru dibaca. Tes pertumbuhan query dan seluruh aturan evaluasi lulus. Tidak menambahkan cache. |
| R05 | P2 | Foto privat pemilik sendiri diarahkan ke onboarding (302), meskipun header menyediakan URL foto akun tersebut. | Pembacaan avatar sendiri tidak lagi mensyaratkan tahap profil. Auth, verified email, role Youth, dan pemilihan file dari `request->user()` tetap berlaku. Edit profil masih terkunci; pengunjung/akun lain tidak memperoleh foto. |
| R06 | P2 | Endpoint avatar dengan path file yang sudah hilang masih memulai respons stream 200. | Cek keberadaan file sebelum streaming; sekarang 404. Tes regresi file hilang lulus. |
| R07 | P3 | Console SweetAlert2 memperingatkan `heightAuto` tidak kompatibel dengan toast setiap login/logout. | Hapus opsi itu khusus toast, pertahankan untuk dialog. Tes config/fire toast lulus; login invalid pada bundle terbaru tidak menghasilkan warning/error console. |

Tidak ditemukan P0 auth bypass, eskalasi privilege, atau P1 state transition failure dalam cakupan yang dijalankan. Ini bukan jaminan ketiadaan semua kerentanan.

### Kegagalan test harness yang diperbaiki

- Unit navigation mengandalkan permissions database: mock `checkPermissionTo` pada aktor unit; uji policy/permission nyata tetap ada di Feature suite.
- Forum/Feed privacy menggunakan halaman pemilik yang sudah login, lalu melarang nama pemilik pada seluruh HTML. Sekarang proyeksi publik diperiksa sebagai pengunjung; kontrol akses edit akun lain tetap diuji.
- Conflict queue test ikut membaca Community yang sah di menu akun manager. Sekarang memeriksa isi paginator `communities`, disertai pengujian GET/POST conflict yang tetap ditolak.
- Assertion markup public/workspace diperbarui untuk atribut motion dan disclosure navigation yang sekarang dipakai. Tidak mengubah otorisasi untuk membuat tes lulus.

## 3. Matriks cakupan fungsi

Semua baris otomatis berikut merupakan tes request/service nyata terhadap MySQL terisolasi, kecuali yang secara eksplisit ditulis Node atau browser. Fixtures dibangun ulang oleh suite; tidak memakai record demo existing untuk mutation workflow.

| Bagian permintaan | Bukti otomatis utama | Pemeriksaan browser / hasil tambahan |
| --- | --- | --- |
| 1 Preflight | Guard database/environment; migrations; routes; snapshot | Development tetap tersedia selama tes berjalan. |
| 2 Auth/recovery | `AuthenticationTest`, `AuthenticationReliabilityTest`, `GoogleAuthenticationTest`, `RecaptchaTest` | Login Youth/Verifier/Admin dan logout; login salah menampilkan pesan Indonesia. Registration, signed verification/tampering/expiry, resend throttle, reset/reuse/expiry diuji otomatis. |
| 3 Role/IDOR | Tests per domain + government directory/internal account/Chat | Manager ke Community lain: 403. Verifier ke Admin: 403. |
| 4 Youth profile/portfolio | `YouthProfileTest`, `YouthPortfolioTest`, enrichment/onboarding/identity/correction tests | Profile, privacy, participation, Passport, certificate, membership, bookmarks, notifications dibuka dalam Youth shell. |
| 5 Community | `CommunityApplicationTest`, `CommunityMembershipTest`, `CommunityManagerWorkspaceTest`, `CommunityDiscoveryTest` | Switch public → manager melalui menu akun; dashboard dan Program C001 diperiksa. |
| 6 Activity | Workflow/session/registration/attendance/completion/discovery tests | Public listing dan empat Activity yang terhubung pada P001 terlihat. |
| 7 Opportunity | `OpportunityTest` | Listing publik dan pengelolaan Admin, draft/published filters. External apply tetap eksternal. |
| 8 Program | `ProgramCoreTest`, `ProgramIntakeTest`, `ProgramIntakeConcurrencyTest`, `PublicProgramTest` | P001 completed menampilkan rangkaian dan laporan; tidak dimutasi. Race MySQL nyata menguji acceptance/quota, open registration, duplicate application. |
| 9 Proposal | `ProgramProposalTest` | P001 Proposal approved, Rp7 juta, tautan dokumen tersedia untuk manager. |
| 10 Logbook | `ProgramLogbookTest` | P001 6 catatan, progres 100%, tautan detail/evidence. |
| 11 E-LPJ | `FinancialReportTest` | P001 approved; realisasi Rp6,6 juta dan selisih Rp400 ribu ditampilkan. |
| 12 Final evaluation | `ProgramEvaluationTest` | Queue Verifier terbuka; completion/replay/conflict/latest-version rules diuji otomatis. |
| 13 Passport/certificate | `ActivityPassportTest`, `CertificateTest`, attendance/completion | Youth memiliki 5 Activity selesai dan 4 sertifikat pada dataset yang diperiksa. Self-report tidak diubah menjadi verified participation. |
| 14 Development Pathway | `DevelopmentPathwayTest`, `PersonalizationTest` | Publication/privacy-safe eligibility dan fallback minim data diuji otomatis. |
| 15 Statistics | `YouthStatisticsTest`, `AdminAnalyticsTest` | Statistik publik dan dashboard Admin; threshold `<5` ditampilkan, bukan skor rekaan. |
| 16 Maps | `EcosystemMapTest` | Keempat radio lapisan dan pilihan Kecamatan Pemalang berfungsi; peta memakai renderer canvas, bukan SVG paths. Geometri 14 kecamatan divalidasi otomatis. |
| 17 Forum | `CommunityForumTest`, author identity rendering | Listing publik desktop/mobile/dark. Create/reply/helpful/report/moderation/XSS/ownership diuji otomatis. |
| 18 Feed | `SocialFeedTest`, frontend like tests | Feed publik di tiga ukuran mobile/tablet/desktop yang direkam; media tidak rusak pada pemeriksaan. |
| 19 Global Chat | `ChatTest`, Node chat/floating-chat | Dua sesi host terpisah: kirim dan terima via polling; pesan QA di-soft-delete. |
| 20 Community Chat | `ChatTest` + dua test directory/bootstrap baru | Dua anggota Community pada sesi berbeda menerima pesan; draft bertahan close/reopen; tombstone diterima penerima. |
| 21 Admin | Analytics/directory/internal account/notification tests | Semua URL unik kartu dashboard, moderation Chat/Feed/Forum, internal users dan audit log dibuka. |
| 22 Verifier | `VerifierListNavigationTest`, directory/review tests | Sepuluh tujuan kartu dashboard dibuka; chat discovery hanya Global ketika bukan anggota Community. |
| 23 Internal users | `InternalAccountManagementTest` | Directory/audit log diperiksa. Temporary password, forced change, stale sessions, last/self Admin protections diuji otomatis tanpa mengubah password demo. |
| 24 Notifications | `NotificationCenterTest`, domain notification assertions | Halaman Youth terbuka; ownership, untrusted URLs, read/unread diuji otomatis. |
| 25 Search/filter/page | Discovery/global search/government directory tests + scalar query regression | Invalid query → halaman pemulihan → listing normal berhasil; query string pagination diuji otomatis. |
| 26 Localization | `IndonesianValidationTest`, Node validation | Login invalid dan filter invalid memberi feedback Indonesia. Label lama bahasa Inggris belum diterjemahkan menyeluruh. |
| 27 SweetAlert | `AlertRenderingTest`, confirmation Node tests, toast Node test | Toast login invalid pada build terbaru tanpa warning console. Cancel/confirm/submitter/repeated-submit diuji Node. |
| 28 Motion | Public-motion/ui-motion Node tests | Main content tetap terlihat setelah load/navigasi; tidak ditemukan map collapse pada halaman yang diperiksa. Reduced motion diuji otomatis, tidak lewat preferensi OS fisik. |
| 29 Bottom nav | Unit public navigation + browser | Tetap memakai keputusan terbaru: Beranda, Pemuda, Komunitas, Program, Forum. Lima item; bukan daftar alternatif Jelajah/Chat/Profil dalam prompt generik. Workspace tidak mendapat bottom nav publik. |
| 30 Responsive | DOM measurements + screenshots | Lima ukuran yang diminta tercatat. Tidak ada horizontal overflow pada observasi yang disimpan. Bukan seluruh halaman × lima ukuran. |
| 31 Dark | Theme CSS dan inspeksi browser | Public, Youth, manager, Admin, Verifier, Feed, Forum, maps, statistics, floating chat; tema dipulihkan ke light. |
| 32 Accessibility | Navigation/validation/chat Node tests + browser | Drawer Escape/focus return; 1100 px scroll lock; nama kontrol chat dan keyboard focus diperiksa. Tidak dilakukan audit WCAG menyeluruh dengan screen reader. |
| 33 Files/storage | Identity/portfolio/Proposal/Logbook/finance/certificate/media tests | Private-file authorization diuji server-side; tidak mempublikasikan evidence. Avatar hilang sekarang 404. |
| 34 Database integrity | Audit read-only metadata/FK/domain | 107 FK, 164 kolom BINARY(16), UUID primary version/variant; ownership, duplicate participation/application/certificate/room. Tidak menghapus orphan/record existing. |
| 35 Performance | Chat bounded eager-loading test; directory bounded queries; queue regression | Pertumbuhan query queue terukur sebelum fix 20→29. Tidak melakukan load test atau menambahkan cache kompleks. |
| 36 Client errors | Console inspection + negative input reproduction | 500 malformed query diperbaiki; SweetAlert warning diperbaiki. HTTP 403/422/419/429 yang memang diuji bukan error tak terduga. |
| 37 Automated | Full backend, Node, build | Artefak XML/log disimpan di `output/regression/`. |
| 38 Severity | Tabel temuan di atas | P0 risiko keselamatan suite ditangani sebelum reset apa pun. |
| 39 Final validation | Full suite diulang, build, routes, Node | Browser repeat mencakup login invalid, drawer, invalid-filter recovery, role denial, chat dua sesi. |
| 40 Report | Dokumen ini | Tidak commit/push. |

## 4. Role dan otorisasi

Role aplikasi global tetap `youth`, `verifier`, `admin`; member/manager/leader adalah peran membership Community. Feature suite mencakup Guest, Youth, active/pending/rejected/removed member, contextual manager/leader, Verifier, Admin, dan konflik role. Pemeriksaan langsung route tetap dilakukan dalam Feature suite, bukan hanya melihat menu tersembunyi.

Room discovery floating chat tidak memberi Verifier/Admin daftar seluruh Community. Akses moderasi Admin yang memang didukung native policy tetap terpisah. Endpoint bootstrap JSON memakai otorisasi room yang sama; revoked membership tidak dapat membuka ulang JSON room.

## 5. Data, privasi, dan audit

- Audit awal/akhir: tidak ada orphan FK, konflik owner Activity–Program, duplicate Activity participation, Program application, sertifikat SIPORA per participation, atau Community room; tepat satu Global room.
- Tidak ada primary UUID dengan version/variant tidak valid atau BINARY UUID dengan panjang salah dalam audit yang dilakukan.
- Snapshot/checksum akhir: seluruh 7.595 file media memiliki fingerprint yang sama. Dari 69 tabel, 64 memiliki checksum identik. Lima tabel berubah sesuai aktivitas QA: `activity_log` 47→49, `chat_messages` 28→30, `chat_rooms` 2→2 (cursor/timestamp), `cache` 63→63, dan `sessions` 6→2. Tabel users, profile, Community, Activity, Program, Proposal, Logbook, E-LPJ, evaluation, certificate, membership, dan application tidak berubah. Tidak melakukan reset atau re-seed `sipora`.
- Dua pesan baru diberi prefix `DATA QA SIPORA — regression`; keduanya di-soft-delete setelah penerimaan dikonfirmasi. Pesan sebelum sesi QA tidak dihapus. Draft QA tidak dikirim dan telah dikosongkan. Kedua akun browser di-logout.
- Identitas, dokumen privat, dan isi password/reset token tidak diekspor ke laporan. SMTP, OAuth, signed links, dan policy diuji dengan fake/provider terkontrol atau array mail transport sesuai test fixture.

## 6. Perubahan file pada fase ini

Product/runtime:

- `app/Http/Middleware/ValidateScalarQueryParameters.php` (baru), `bootstrap/app.php`.
- `resources/views/errors/invalid-filters.blade.php` (baru).
- `app/Http/Controllers/Verifier/ProgramEvaluationController.php`, `app/Services/ProgramEvaluationEligibilityService.php`.
- `app/Http/Controllers/Youth/ProfileController.php`, `routes/youth.php`.
- `resources/js/app.js` (hanya breakpoint public drawer pada fase ini), `resources/js/alerts.js` (opsi toast).

Harness/regression:

- `phpunit.xml`, `tests/TestCase.php`, `tests/Support/TestEnvironmentGuard.php` (baru), `tests/Support/program-intake-worker.php`, `tests/Feature/ProgramIntakeConcurrencyTest.php`.
- `tests/Unit/TestEnvironmentGuardTest.php` (baru), `tests/Unit/PublicNavigationTest.php`.
- `tests/Feature/ScalarQueryValidationTest.php` (baru), `ProgramEvaluationTest.php`, `ChatTest.php`, `YouthProfileTest.php`.
- Assertion existing pada `CommunityApplicationTest.php`, `CommunityForumTest.php`, `SocialFeedTest.php`, `PublicInformationPagesTest.php`, `YouthWorkspaceRenderingTest.php`.
- `tests/Frontend/public-navigation.test.mjs`, `tests/Frontend/alerts.test.mjs` (keduanya baru).
- `docs/QA_TESTING_GUIDE.md`, laporan ini. Log, screenshot, snapshot, dan script audit lokal ada di ignored `output/regression/`.

Perubahan sebelumnya pada file yang sama tetap dipertahankan. Tidak ada migration atau dependency baru. Penambahan coverage: 30 backend test cases (termasuk data provider), dua Node cases; lima assertion UI lama diperbaiki tanpa mengurangi pengujian otorisasi server.

## 7. Keterbatasan dan tindak lanjut

- OAuth Google live, provider outage nyata, dan pengiriman email ke inbox eksternal tidak diuji end-to-end. Socialite/reCAPTCHA denial cases dan mail/broker diuji lewat fixture terkontrol; login lokal lewat reCAPTCHA normal berhasil.
- Browser desktop yang tersedia digunakan untuk lima viewport. Belum menguji Safari/iOS/Android fisik, keyboard virtual fisik, notch/safe-area nonzero, atau background-tab timer throttling OS. Hidden-tab pause/backoff/reduced-motion dibuktikan melalui Node lifecycle tests.
- Tidak melakukan load/penetration test, pemeriksaan kontras otomatis menyeluruh, atau pengujian screen reader lengkap. Query count sanity bukan benchmark kapasitas produksi.
- Tidak semua alur mutation diulang secara manual di database development; state transitions/authorization/upload/reset/review diuji oleh backend Feature suite pada database terisolasi. Ini menjaga golden demo.
- Pemeriksaan lima viewport bersifat representatif, bukan Cartesian product semua route × semua role × semua theme.
- Sesudah kompetisi: tambahkan CI dengan database/user tes khusus, browser E2E terisolasi dengan mail capture/OAuth fixture, aksesibilitas otomatis dan perangkat fisik, serta pin/audit widget aksesibilitas eksternal. Ini rekomendasi, tidak diimplementasikan pada fase ini.

## 8. Artefak

Di `output/regression/`: `backend-baseline.xml`, `backend-final.xml` (398-pass checkpoint), `backend-complete.xml` (putaran akhir), `frontend-final.txt`, `build-complete.txt`, `routes-final.json`, `migrations-final.txt`, `browser-evidence.json`, `database-integrity.json`, `database-prepare.json`, `database-after.json`, serta subfolder `screenshots/`.

Screenshot terpilih: `invalid-filter-recovery-final.png` (halaman pemulihan pada build akhir, console bersih), `map-tablet-dark.png`, `admin-mobile-dark.png`, `community-chat-mobile-dark.png`. PHP lint untuk runtime yang diubah dan kompilasi Blade juga berhasil; `git diff --check` tidak menemukan whitespace errors pada perubahan yang diperiksa.
