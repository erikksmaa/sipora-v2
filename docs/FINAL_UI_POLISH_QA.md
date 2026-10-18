# Final UI/UX polish — SIPORA v2

Tanggal: 18 Oktober 2026. Tidak ada commit/push, perubahan skema, workflow bisnis, atau aturan otorisasi.

## Hasil implementasi

1. **Pratinjau identitas:** halaman yang sebenarnya berada pada `admin/identity-verifications/show` (Verifier tidak berhak mengakses dokumen identitas). Gambar memakai `w-full max-w-xl h-auto max-h-[65vh] object-contain`, terpusat tanpa crop. PDF tetap memakai iframe dengan lebar terbatas dan tinggi 60vh. Tautan dokumen privat yang sudah ada dipertahankan; tidak membuat viewer baru.
2. **Badge Admin:** `AdminQueueSummaryService` menjalankan empat COUNT tetap: `UserIdentityVerification.status=pending`, `SocialReport.status=pending`, `ForumReport.status=pending`, dan `ChatMessageReport.status=pending`. Data identitas tetap hanya tersedia untuk Admin. Tidak membuat badge akun internal karena tidak ada antrean peninjauan yang perlu diciptakan.
3. **Badge Verifier:** mempertahankan sumber `VerifierQueueSummaryService`: Community/Activity `pending_review`, Proposal/Logbook/E-LPJ `submitted`, Program yang memenuhi prasyarat evaluasi, dan aplikasi `submitted/under_review` pada Program layak tanpa konflik pengelola. Tidak mengubah default direktori semua status. Badge nol disembunyikan, 1–99 angka asli, >=100 `99+`, dan titik indikator tersedia saat sidebar diciutkan.
4. **Jarak form:** label pada profil Youth, komponen profil bersama, dan keamanan akun menjadi block sehingga `space-y-5` bekerja. Area aksi tidak menempel pada input sebelumnya. Ruang gulir bawah workspace mobile ditambah agar aksi terakhir dapat digulir melewati floating controls; tidak menambahkan margin global pada tombol.
5. **Kartu Feed:** area noninteraktif membuka detail lewat mouse atau Enter. Kartu fokusable dengan role link, label yang mempertahankan privasi nama penulis, dan focus ring. Link profil, tombol suka/komentar/follow, form, menu, dan media mempertahankan aksi sendiri. Seleksi teks dan modified click tidak memicu perpindahan. Tidak ada anchor bersarang.
6. **Pagination:** override Tailwind dan simple-Tailwind memakai komponen tema bersama untuk previous/next, nomor, aktif, disabled, hover dan fokus. Surface terang/gelap, teks, border memakai token SIPORA; aktif memakai primary dengan penanda accent tambahan dalam dark mode. Berlaku pada semua pemanggil pagination default, termasuk direktori dan domain publik.
7. **Floating Chat:** komponen tetap dirender pada shared workspace shell dan halaman publik terautentikasi; tamu tetap tidak menerima launcher. Backend, routes, polling, keanggotaan, serta fallback halaman penuh tidak diubah.
8. **Navigasi Chat:** menu sidebar Chat Global untuk Youth/Admin/Verifier dan Chat Community pada pengelola dihapus. Moderasi Chat Admin tetap ada. Floating Chat tetap menyediakan ruang Global dan Community sesuai izin.
9. **Auth:** login/register memakai header publik dua baris yang sudah ada dan komponen navigasi bawah publik yang sama pada mobile. Padding bawah menjaga konten terakhir tetap terjangkau. Saat digulir sampai akhir, paragraf terakhir berada di atas navigasi bawah. Badge reCAPTCHA diposisikan di atas navigasi bawah pada auth mobile.
10. **CTA daftar:** login menampilkan “Belum memiliki akun? Daftar sekarang” langsung setelah form, menggunakan route register yang sudah ada.
11. **Password:** komponen Blade `password-input` memakai Alpine lokal, default password, toggle text, tombol type button, aria-label Indonesia dan aria-pressed. Dipakai pada login, register+konfirmasi, reset+konfirmasi, keamanan Youth, perubahan password internal, konfirmasi admin, serta tampilan password sementara. Constraint/autocomplete/name/value tidak diubah. Penyalinan dan pengosongan password sementara tetap bekerja pada elemen input di section yang sama.
12. **Google:** ikon G SVG kecil inline lokal pada login dan register; tidak ada URL/CDN baru dan OAuth tidak diubah.
13. **Bahasa:** label login, ingat saya, lupa kata sandi, Google dan penjelasan autentikasi menjadi Indonesia. Label kata sandi di register/reset/lupa diselaraskan; nama request dan route tetap sama.

## QA browser

| Pemeriksaan | Hasil |
|---|---|
| Login + Register 360×800, 390×844, 412×915 | Navigasi bawah grid; tidak ada overflow horizontal; masing-masing satu/dua toggle; guest tidak memiliki chat |
| Tablet 768×1024 dan desktop 1440×1000 | Tidak ada overflow; navigasi bawah tersembunyi; login card desktop terpusat, lebar 448px |
| Password login | Toggle benar-benar mengubah password → text → password tanpa submit atau perubahan nilai |
| Auth dark mode | Register, header, field dan navigasi bawah terbaca; ikon Google lokal terlihat |
| Pagination Admin light/dark | Terang: surface putih, teks navy; gelap: surface slate, teks terang; tautan halaman berikutnya benar |
| Admin | Badge Moderasi Feed 2 dan Forum 3 sesuai data lokal; badge nol tidak muncul; Moderasi Chat tetap ada; floating chat membuka daftar ruang |
| Youth | Floating chat tersedia; profil mobile memiliki jarak sekitar 20px antarfield |
| Community workspace | Floating chat tersedia dan memuat Global serta kedua Community yang diizinkan; menu chat halaman penuh tidak muncul |
| Feed | Like tetap di URL feed, status/count diperbarui; like QA dikembalikan ke keadaan semula; klik teks dan Enter pada kartu membuka detail; tautan Community membuka Community |
| Posisi floating publik mobile | Launcher di atas navigasi bawah, berjarak dari widget aksesibilitas; offset workspace tetap terpisah dari public nav |
| Console | Tidak terlihat error JS/Alpine pada halaman yang diperiksa |

Keterbatasan QA manual: akun demo Verifier menolak kredensial yang tersedia; tidak mengubah/reset akun tersebut. Data lokal tidak memiliki pengajuan dokumen identitas, sehingga pratinjau diverifikasi lewat fixture rendering dan tes otorisasi, tanpa membuat data identitas baru. Tidak mengganti kredensial, menjalankan keputusan review, atau mengirim pesan chat selama QA.

## Tes dan validasi

- `tests/Feature/FinalUiPolishTest.php`: auth/nav/Google/toggle, sumber badge pending dan nol, batas 99+, floating chat Youth/Verifier/Admin, serta pagination.
- `tests/Feature/AdminIdentityVerificationTest.php`: assertion responsive preview dan otorisasi yang ada tetap dijalankan.
- `tests/Feature/ManagerWorkspaceRenderingTest.php`: launcher dan penghapusan sidebar chat.
- `tests/Feature/SocialFeedTest.php`: rendering target kartu, privasi dan perilaku like tetap diuji.
- `tests/Frontend/social-card.test.mjs`: klik/Enter, kontrol anak, seleksi teks, modifier dan tombol keyboard lain.
- Tes backend memakai `sipora_regression_test` dengan guard pengujian proyek; database pengembangan tidak di-refresh.
- Run lengkap pertama: 401 pass / 2 fail. Kedua kegagalan diperbaiki: fixture report membutuhkan post_id; shorthand binding Alpine pada komponen Blade harus memakai x-bind.
- Pengujian ulang terarah: **38 pass, 430 assertions**.
- Node frontend: **33 pass, 0 fail**.
- Build Vite: **lulus**.
- Blade cache: **lulus**.
- `git diff --check`: **lulus**.
- Regresi lengkap akhir: **404 passed (3430 assertions)**. Exit code 0.

Bukti visual: `storage/app/final-ui-login-mobile.png`. Log: `storage/app/final-ui-targeted.log`, `final-ui-regression.log`, `final-ui-frontend.log`, `final-ui-build.log`.

## File implementasi

- Baru: `app/Services/AdminQueueSummaryService.php`, `resources/js/social-card.js`, `resources/views/components/password-input.blade.php`, `google-icon.blade.php`, `resources/views/vendor/pagination/{tailwind,simple-tailwind}.blade.php`.
- Diubah: `resources/css/app.css`, `resources/js/app.js`, `resources/views/layouts/{auth,workspace}.blade.php`, `resources/views/auth/{login,register,google-link,forgot-password,reset-password}.blade.php`, `resources/views/components/{auth-input,ui/icon,youth/profile-form}.blade.php`, `resources/views/admin/identity-verifications/show.blade.php`, `resources/views/admin/internal-users/{admin-password,form,temporary}.blade.php`, `resources/views/government/account/password.blade.php`, `resources/views/youth/{profile/edit,account/security}.blade.php`, `resources/views/social/post-card.blade.php`.
- Perubahan navbar milik pengguna dipertahankan; hanya whitespace kosong dibersihkan untuk diff check. File setup kompetisi dan .gitignore yang sudah berubah sebelumnya dipertahankan.
