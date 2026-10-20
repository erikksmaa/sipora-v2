# SIPORA — Mobile Screenshot QA

Tanggal: 19 Oktober 2026. Aplikasi asli: `http://127.0.0.1:8000`.

Paket selesai: **130 PNG**, **67 rute unik**, **103 label halaman/state**, **23 full-page**, **7 tema gelap**. Semua file final ditampilkan dan diperiksa secara visual saat pengambilan. PNG diverifikasi melalui signature dan dimensi IHDR setelah konversi format; tidak ada resize, blur, mockup, atau manipulasi konten.

Viewport CSS: **390×844** untuk 128 screenshot, **412×915** untuk 2 screenshot pembanding. Role: Guest, Youth demo, Community Manager, Verifier, Admin. Peran Leader terlihat pada keanggotaan Youth; workspace Leader tidak diklaim diuji terpisah.

[Indeks lengkap 130 file](../output/mobile-screenshots/README.md). [Manifest](../output/mobile-screenshots/manifest.json). [Dimensi ekspor asli](../output/mobile-screenshots/image-metadata.json).

## Cakupan

| Kelompok | Yang diwakili |
|---|---|
| Publik | Home, direktori/detail Activity, Community, Program, Opportunity; direktori Pemuda dan Portfolio publik |
| Auth | Login, lupa password, toggle password kosong, empat langkah registrasi sampai Konfirmasi |
| Youth | Dashboard, Profil, Biodata dengan NIK tersamarkan, Pathway, keanggotaan, partisipasi, Passport, sertifikat, notifikasi, keamanan |
| Community | Dashboard Manager, anggota demo, Activity, peserta, completion, sesi, presensi, Chat Community |
| Program | Kuota dan status diterima, aplikasi/peserta, Proposal, monitoring, Logbook, E-LPJ, evaluasi, Program selesai |
| Sosial | Feed/post, Forum/thread/balasan, tombol chat tertutup, daftar ruang, Chat Global dan Community dalam workspace |
| Intelligence | Overview statistik, minat, skill, partisipasi, kartu cakupan kecamatan, empat lapisan peta, Intelligence operasional Admin |
| Verifier | Dashboard, badge actionable, riwayat antrean Activity, Proposal dan review, aplikasi Program dan kartu keputusan, monitoring/Logbook, E-LPJ, evaluasi |
| Admin | Dashboard, overview identitas, moderasi Feed/Forum/Chat, Opportunity, akun internal, empat direktori, Intelligence, peta |
| Special | Header dan bottom nav pada gambar publik, menu publik, drawer workspace, dropdown akun, tujuh dark-mode, dua viewport 412×915 |

## Temuan dan status perbaikan

P2 = mengganggu keterbacaan/interaksi; P3 = kosmetik atau keterbatasan bagian tambahan. Screenshot di tabel adalah hasil final; untuk tiga perbaikan, gambar diambil ulang setelah patch.

| ID | Page | Route | Role | Viewport | Screenshot | Severity | Fixed / not fixed | Temuan / tindakan |
|---|---|---|---|---|---|---|---|---|
| M01 | Detail Activity Academy | `/activities/academy-workshop-full-stack-web-development-untuk-pemuda` | Guest / Youth | 390×844 | [Activity](../output/mobile-screenshots/05_activity/activity_detail_mobile.png) | P2 | **Fixed** | Badge judul Program memperlebar ukuran minimum kolom grid sehingga judul, penyelenggara dan lokasi terpotong. Tambah `min-w-0`, batasi badge ke lebar parent dan izinkan pembungkusan. |
| M02 | Ecosystem Maps | `/ecosystem-map`, `/admin/ecosystem-map` | Guest / Admin | 390×844 | [Light](../output/mobile-screenshots/08_intelligence/map_youth_mobile.png), [dark](../output/mobile-screenshots/11_special/map_dark_mobile.png) | P2 | **Fixed** | Zoom kanan bawah bertumpuk dengan tombol back-to-top/chat/aksesibilitas ketika digulir. Pada mobile, kontrol zoom dipindah ke kiri bawah melalui CSS khusus peta. |
| M03 | Passport detail Academy | `/youth/passport/01a14db5-a40e-707e-9442-deb1d8547fbd` | Youth demo | 390×844 | [Passport](../output/mobile-screenshots/05_activity/passport_contribution_mobile.png) | P2 | **Fixed** | Judul mewarisi warna gelap di panel biru. Tambah warna putih eksplisit pada judul. |
| M04 | Top Skill | `/statistics` | Guest | 390×844 | [Skill](../output/mobile-screenshots/08_intelligence/skills_mobile.png) | P2 | **Not fixed** | Label panjang pada sumbu grafik skill terpotong di sisi kiri. Nama lengkap masih tersedia pada rincian di bawah grafik. Perlu penyesuaian layout chart terpisah. |
| M05 | Dashboard Community | `/manage/komunitas-pemuda-digital-pemalang` | Community Manager | 390×844 | [Dashboard](../output/mobile-screenshots/04_community/dashboard_mobile.png) | P2 | **Not fixed** | Logo tampil sebagai fallback “Visual tidak tersedia”, sedangkan profil publik komunitas memuat logonya. View memakai endpoint logo pengajuan Youth yang melakukan otorisasi `view`; dugaan ketidaksesuaian endpoint/peran perlu diperiksa. Tidak mengubah izin akses pada tugas screenshot. Gambar ini dipertahankan sebagai bukti QA, bukan contoh hero yang sudah bersih. |
| M06 | Evaluasi akhir Program selesai | `/verifier/program-evaluations/pemalang-youth-digital-empowerment-2026` | Verifier | 390×844 | [Evaluasi](../output/mobile-screenshots/09_verifier/final_evaluation_mobile.png) | P2 | **Not fixed** | Banner “Program belum siap dievaluasi” beralasan “Program sudah selesai”, walaupun terdapat riwayat approved. Ini masalah penjelasan status, bukan responsivitas; tidak mengubah logika evaluasi dalam tugas ini. |
| M07 | Portfolio publik, panel penjelasan | `/portfolio/demo-pemuda-01` | Guest | 390×844 | [Full Portfolio](../output/mobile-screenshots/01_public/portfolio_mobile_full.png) | P3 | **Not fixed** | Judul kecil “Tentang validasi Portfolio” kurang kontras pada panel biru paling bawah. Isi penjelasan tetap terbaca. |
| M08 | Home, embed lokasi eksternal | `/` | Guest / Youth | 390×844 | [Full Home](../output/mobile-screenshots/01_public/home_mobile_full.png) | P3 | **Not fixed — external** | Kotak embed Google Maps di bagian kontak tetap kosong pada lingkungan browser ini. Peta ekosistem lokal, empat lapisan dan batas kecamatan termuat. Tidak mengganti iframe dengan mockup. |

## Perubahan kecil yang dibuat

- `resources/views/public/activities/show.blade.php`: batas lebar grid/badge header Activity.
- `resources/views/youth/passport/show.blade.php`: warna judul Passport.
- `resources/css/ecosystem-map.css`: posisi zoom pada lebar mobile.

Patch lama pada `resources/views/programs/applications.blade.php` dan laporan freeze sebelumnya telah ada sebelum tugas ini dan dipertahankan. Tidak ada perubahan skema, kebijakan otorisasi, kredensial, seeder, keputusan seleksi, kehadiran, atau data bisnis untuk menghasilkan screenshot.

## Halaman/state dilewati atau terbatas

| Page / state | Route / konteks | Alasan |
|---|---|---|
| Verifikasi email dan resend | state setelah akun baru dibuat | Akun yang dipakai untuk capture sudah memiliki email terverifikasi. Tidak membuat akun final atau mengubah flag verifikasi demi screenshot. |
| Google pending onboarding | `/auth/google` / onboarding pending | Tidak ada sesi OAuth pending yang tersedia dalam akun capture. Tidak menghubungkan akun Google atau merekayasa state. Empat langkah registrasi manual telah diambil. |
| Opportunity tersimpan | `/youth/opportunities/bookmarks` | Dashboard akun demo menunjukkan 0 tersimpan. Tidak membuat bookmark hanya untuk mengisi gambar. |
| Permintaan bergabung Community | `/manage/komunitas-pemuda-digital-pemalang/join-requests` | Halaman dibuka dan menampilkan 0 pending. Tidak membuat permintaan baru. |
| Antrean verifikasi Community | `/verifier/community-verifications` | Dibuka dengan “Semua status”, tetapi tidak ada pengajuan pada hasil. Direktori/profil komunitas tetap terwakili. |
| Detail dokumen identitas | `/admin/identity-verifications/{submission}` | Overview menunjukkan 0 menunggu dan 1 disetujui. Hanya jumlah status dicapture; tidak membuka NIK, berkas, atau detail privat pengguna nyata. |
| Notifikasi Admin | `/notifications` saat Admin | Dibuka dan menampilkan “Belum ada notifikasi”. Notifikasi Youth yang berisi data demo dicapture. |
| Sertifikat Program | Program | Tidak membuat klaim sertifikat Program. Sertifikat yang tersedia berasal dari Activity dan sudah didokumentasikan. |
| CRUD berulang, About, Contact, log teknis | Berbagai | Dilewati karena tidak menambah informasi visual penting; bagian kontak Home tetap ada pada full-page. |
| Native print / PDF download | Cetak daftar hadir, Unduh PDF | Bukan cakupan pengambilan screenshot mobile ini. Tombol tersedia pada gambar peserta/sertifikat; tidak membuka dialog cetak OS. |

Tidak ada rute yang ditargetkan menghasilkan screenshot halaman 404/500 dalam paket final. Batas state di atas tidak ditutup dengan mockup. Bagian embed eksternal Home tidak berhasil menampilkan isi, dan fallback logo Community tetap dicatat sebagai temuan.

## Pemeriksaan final dan batas ekspor

- Seluruh **130** file final telah diperiksa visual: konten, clipping, tema, modal, nav, gambar dan data yang tampak. Retake dilakukan untuk animasi/lazy images yang belum selesai, posisi scroll yang salah, dan hasil setelah tiga patch.
- Full-page publik dimuat melalui guliran seluruh dokumen dan pemeriksaan animasi/gambar; tidak menghilangkan elemen halaman lewat script. Gambar viewport merupakan acuan utama untuk nav fixed. Dalam full-page, nav fixed dapat menutup sedikit konten pada posisi viewport asal: karakteristik ekspor, bukan bukti halaman tidak bisa digulir.
- Tidak ada horizontal overflow tingkat dokumen yang terukur positif pada capture final. Angka negatif kecil di manifest berasal dari area scrollbar/ekspor. Overflow yang sengaja dapat digeser pada tab navigasi tidak sama dengan overflow halaman.
- Browser menormalisasi sebagian ekspor (misalnya **377×815** untuk viewport CSS **390×844**). Full-page memiliki lebar ekspor **376 px**. Paket mempertahankan piksel asli, tidak memperbesar gambar. Dimensi aktual tercatat per file; hanya viewport CSS yang dijamin sesuai permintaan.
- API screenshot menghasilkan data gambar terkompresi; semua hasil ditranscode lossless ke kontainer PNG dan signature `89 50 4E 47 0D 0A 1A 0A` diverifikasi. Transcoding tidak memulihkan detail yang telah dikompresi oleh browser.
- Observasi awal gambar di manifest dapat mencakup lazy images di luar viewport; field tersebut diberi nama `offscreenOrUnloadedImageObservation` dan tidak dihitung sebagai bukti broken image yang terlihat. Temuan visual nyata dicatat pada M05 dan M08.
- Akun demo memakai email contoh, NIK pada Biodata/Konfirmasi disamarkan, form password terlihat kosong/tertutup; tidak ada password login, dokumen identitas, atau kontak pribadi nyata pada gambar final.
- Registrasi hanya sampai Konfirmasi; tidak mengirim akun baru. Chat/review/presensi/moderasi hanya dilihat. Verifier login berhasil memakai kredensial yang diberikan pengguna tanpa perubahan password. Browser ditinggalkan logout, tema terang, viewport dipulihkan, server pengguna tetap berjalan.

## Validasi kode

- `npm run build`: **PASS** (build akhir 23,60 detik).
- `node --test tests/Frontend/*.test.mjs tests/js/*.test.mjs`: **45 PASS**, 0 gagal.
- `php artisan test --compact --filter='ActivityDiscoveryTest|ActivityPassportTest|EcosystemMapTest'`: **8 PASS**, 56 assertions, 39,33 detik, pada database regresi melalui guard tes proyek.
- `git diff --check`: **PASS**.
- Tidak menjalankan ulang seluruh suite backend pada tugas screenshot ini; perubahannya terbatas pada Blade/CSS dan diverifikasi dengan tes terfokus serta browser.
- Tidak commit dan tidak push.

## Daftar rute yang terwakili

Jumlah di bawah menghitung PNG, termasuk full-page, detail bagian, tema dan viewport tambahan. Query filter lengkap ada pada indeks.

| Route | Role | PNG |
|---|---|---:|
| `/` | Guest, Youth demo | 3 |
| `/activities` | Guest | 2 |
| `/activities/academy-workshop-full-stack-web-development-untuk-pemuda` | Youth demo | 2 |
| `/admin/chat/reports` | Admin | 1 |
| `/admin/dashboard` | Admin | 4 |
| `/admin/directory/activities` | Admin | 1 |
| `/admin/directory/communities` | Admin | 1 |
| `/admin/directory/programs` | Admin | 1 |
| `/admin/directory/youth` | Admin | 2 |
| `/admin/ecosystem-map` | Admin | 1 |
| `/admin/feed/reports` | Admin | 1 |
| `/admin/forum/reports` | Admin | 1 |
| `/admin/identity-verifications` | Admin | 1 |
| `/admin/internal-users` | Admin | 2 |
| `/admin/opportunities` | Admin | 1 |
| `/communities` | Guest | 2 |
| `/communities/komunitas-pemuda-digital-pemalang` | Guest, Youth demo | 2 |
| `/ecosystem-map` | Guest, Youth demo | 7 |
| `/feed` | Guest, Youth demo | 3 |
| `/feed/01a13471-534d-7130-b634-fc899ed0681c` | Guest | 2 |
| `/forgot-password` | Guest | 1 |
| `/forum` | Guest | 2 |
| `/forum/01a13471-4d37-70b3-a623-35361d76c4d0` | Guest | 2 |
| `/login` | Guest | 4 |
| `/manage/komunitas-pemuda-digital-pemalang` | Community Manager | 1 |
| `/manage/komunitas-pemuda-digital-pemalang/activities` | Community Manager | 2 |
| `/manage/komunitas-pemuda-digital-pemalang/activities/academy-workshop-full-stack-web-development-untuk-pemuda` | Community Manager | 1 |
| `/manage/komunitas-pemuda-digital-pemalang/activities/academy-workshop-full-stack-web-development-untuk-pemuda/participants` | Community Manager | 2 |
| `/manage/komunitas-pemuda-digital-pemalang/activities/academy-workshop-full-stack-web-development-untuk-pemuda/sessions` | Community Manager | 1 |
| `/manage/komunitas-pemuda-digital-pemalang/activities/academy-workshop-full-stack-web-development-untuk-pemuda/sessions/01a14db5-a664-7278-ab8f-4cfcd7d40f3c/attendance` | Community Manager | 2 |
| `/manage/komunitas-pemuda-digital-pemalang/members` | Community Manager | 1 |
| `/manage/komunitas-pemuda-digital-pemalang/programs` | Community Manager | 1 |
| `/manage/komunitas-pemuda-digital-pemalang/programs/pemalang-digital-youth-academy-2026/applications` | Community Manager | 3 |
| `/manage/komunitas-pemuda-digital-pemalang/programs/pemalang-youth-digital-empowerment-2026` | Community Manager | 4 |
| `/manage/komunitas-pemuda-digital-pemalang/programs/pemalang-youth-digital-empowerment-2026/financial-reports/01a13471-4e7a-70f9-91c7-37f1ccd1983c` | Community Manager, Youth / Manager, Youth demo | 4 |
| `/manage/komunitas-pemuda-digital-pemalang/programs/pemalang-youth-digital-empowerment-2026/logbooks/01a13471-4e68-700c-8076-a2fe704d973b` | Community Manager | 1 |
| `/notifications` | Youth demo | 1 |
| `/opportunities` | Guest | 2 |
| `/opportunities/demo-internship-frontend-developer` | Guest | 2 |
| `/portfolio/demo-pemuda-01` | Guest | 3 |
| `/programs` | Guest | 2 |
| `/programs/pemalang-digital-youth-academy-2026` | Guest, Youth demo | 3 |
| `/register` | Guest | 8 |
| `/statistics` | Guest | 6 |
| `/verifier/activity-verifications` | Verifier | 1 |
| `/verifier/dashboard` | Verifier | 2 |
| `/verifier/financial-reports` | Verifier | 1 |
| `/verifier/financial-reports/01a13471-4e7a-70f9-91c7-37f1ccd1983c` | Verifier | 1 |
| `/verifier/program-evaluations` | Verifier | 1 |
| `/verifier/program-evaluations/pemalang-youth-digital-empowerment-2026` | Verifier | 1 |
| `/verifier/program-intake` | Verifier | 1 |
| `/verifier/program-monitoring` | Verifier | 1 |
| `/verifier/program-monitoring/logbooks/01a13471-4e68-700c-8076-a2fe704d973b` | Verifier | 1 |
| `/verifier/program-proposals` | Verifier | 1 |
| `/verifier/program-proposals/01a13471-4760-7280-a8fa-c63a5a891424` | Verifier | 1 |
| `/verifier/programs/pemalang-digital-youth-academy-2026/applications` | Verifier | 2 |
| `/youth` | Guest | 2 |
| `/youth/account/security` | Youth demo | 1 |
| `/youth/activities` | Youth demo | 1 |
| `/youth/biodata` | Youth demo | 1 |
| `/youth/certificates/01a14db5-a74f-704e-a853-e35cd8bd959f` | Youth demo | 2 |
| `/youth/communities` | Youth demo | 1 |
| `/youth/development-pathway` | Youth demo | 2 |
| `/youth/home` | Youth demo | 4 |
| `/youth/passport` | Youth demo | 1 |
| `/youth/passport/01a14db5-a40e-707e-9442-deb1d8547fbd` | Youth demo | 1 |
| `/youth/profile` | Youth demo | 1 |
