# Konsolidasi pendaftaran dan identitas SIPORA

Tanggal: 18 Oktober 2026. Tidak ada commit/push, reset database pengembangan, perubahan UUID, atau perubahan izin akses dokumen.

## Audit arsitektur lama

Pendaftaran email sebelumnya hanya membuat `users` dan role Youth. Setelah verifikasi email, pengguna mengisi Biodata, mengajukan dokumen, menunggu peninjauan Admin, lalu melengkapi profil serta portfolio/minat. OAuth Google merupakan jalur bootstrap akun tersendiri.

- `user_profiles`: nama, tanggal/tempat lahir, gender, telepon dan presentasi profil. Gender berupa string, sehingga nilai historis yang lebih luas tetap dapat disimpan.
- `user_addresses.administrative_area_id`: kecamatan domisili, ditambah alamat privat, RT/RW dan kode pos. `administrative_areas` sudah mempunyai hierarki dan 14 kecamatan Pemalang.
- `interests` / `user_interests`: taksonomi minat dan relasi kanonik yang dipakai Development Pathway.
- `user_identities`: identitas per pengguna, status/metode verifikasi, `national_id_ciphertext` dan `national_id_hash`. Indeks UNIQUE untuk hash sudah ada sebelum fase ini.
- `user_identity_verifications`: riwayat pengajuan, nomor dokumen terenkripsi/hash, path dan checksum berkas privat, keputusan serta catatan review.
- Nomor sebelumnya diminta saat upload. Jalur lama mencoba mengisi fingerprint dengan mass assignment yang tidak mengizinkan kedua kolom sensitif tersebut. Penulisan anchor sekarang dilakukan secara eksplisit oleh layanan server, bukan memperluas mass assignment.

## Alur dan penyimpanan baru

1. **Data Diri:** nama, NIK, tanggal lahir, jenis kelamin, email, kata sandi dan konfirmasi. Tempat lahir/telepon tidak diduplikasi pada langkah ini; kontak tetap tersedia pada Biodata yang sudah ada.
2. **Alamat:** Kabupaten Pemalang tetap, kecamatan lokal, desa/kelurahan yang sesuai, alamat detail/RT/RW/kode pos opsional. Kode wilayah NIK tidak dipakai untuk menebak domisili.
3. **Profiling:** 1–5 minat dari `interests`, dengan kartu, counter, feedback pilihan keenam dan navigasi keyboard.
4. **Konfirmasi:** ringkasan nama, NIK tersamarkan, DOB, gender, email, domisili dan minat. Kata sandi tidak ditampilkan. Stepper dapat dibuka untuk meninjau isian; pengiriman akhir tetap memvalidasi seluruh langkah, sehingga preview langkah tidak membuat akun atau melewati validasi.
5. **Akun dibuat secara atomik:** User, role Youth, profil privat, domisili, minat dan anchor NIK dalam satu transaksi. Kegagalan write tidak meninggalkan akun/relasi parsial.
6. **Verifikasi email:** controller/event/signed URL/resend throttle/intended URL yang sudah ada tetap dipakai. Pengiriman email tetap setelah transaksi berhasil; kegagalan transport mempunyai jalur pemulihan yang lama.
7. **Bukti identitas:** pilih KTP/KIA/Kartu Pelajar, unggah JPG/PNG/PDF maksimal 4 MB dan setujui acknowledgement. Tidak meminta nomor dokumen kembali. Berkas tetap di disk privat dengan nama acak dan akses server terotorisasi.
8. **Review:** otorisasi proyek yang sebenarnya tetap **Admin**. Fase ini tidak memberikan akses dokumen kepada Verifier. Tampilan review menampilkan NIK tersamarkan, DOB, gender, kecamatan/desa, jenis bukti, waktu pengajuan dan aksi yang sudah ada.

Status akun/email dan status identitas tidak digabung. Menyimpan pendaftaran atau masuk melalui Google tidak menandai identitas sebagai verified.

## NIK, keunikan, DOB dan gender

- ID teknis tetap UUIDv7/BINARY(16); NIK tidak menjadi primary key.
- NIK wajib berupa string 16 digit pada registrasi email. Nilai terenkripsi menggunakan Laravel Crypt di kolom yang sudah ada. SHA-256 deterministik memakai indeks UNIQUE lama untuk pencarian dan perlindungan race.
- `IdentityAnchorService` mengunci pemilik identitas, menjaga satu anchor per pengguna, memeriksa fingerprint termasuk identitas yang soft-deleted, serta mengubah unique-constraint collision menjadi pesan Indonesia yang aman. Anchor yang sudah valid tidak dapat diganti melalui form biasa.
- Pesan duplikat tidak mengungkap email, nama, status akun atau hasil verifikasi. Tidak ada endpoint lookup NIK baru. Rate limit dan reCAPTCHA auth yang sudah ada dipertahankan.
- NIK dan nomor dokumen dimasukkan daftar `dontFlash`. Model menyembunyikan hash/ciphertext, ringkasan hanya `************1234`, dan audit tidak menerima NIK/path berkas. Tidak ada NIK pada URL atau payload analytics baru.
- Parser PHP/JS hanya membaca tanggal. Offset hari perempuan dikurangi 40 untuk tanggal saja; **gender tidak diturunkan dari NIK**.
- Abad dipilih dari tanggal yang masuk rentang usia 16–30 tahun, berdasarkan tanggal server hari ini. Tanggal mustahil/tidak sesuai usia tidak menyebabkan crash. DOB tetap dapat diisi manual.
- Prefill hanya mengubah DOB yang belum diedit. Koreksi manual bertahan ketika NIK berubah. Perbedaan DOB memberi bantuan/warning, bukan penolakan; backend tetap memeriksa format tanggal dan usia.
- UI baru hanya menampilkan Laki-laki/Perempuan. Kolom DB dan nilai historis `other`/`prefer_not_to_say` tidak dihapus. Biodata mempertahankan nilai lama bila belum diganti, tanpa menampilkan pilihan tambahan pada UI baru.

## Sumber alamat lokal

[Dataset wilayah sumber](https://github.com/cahyadsn/wilayah/blob/master/db/wilayah.sql), MIT, disalin sekali sebagai subset Pemalang: **14 kecamatan, 223 desa/kelurahan**. Sumber, atribusi, lisensi dan checksum upstream tercantum pada `database/data/pemalang-villages.json` serta `PEMALANG_SOURCE.md`.

Tidak menjalankan SQL upstream. Seeder hanya menambahkan child desa dengan mencocokkan kecamatan yang sudah ada; UUID dan kode master kecamatan SIPORA dipertahankan. Runtime memakai query lokal dan filtering Alpine, tanpa wilayah API. Server memverifikasi level area, induk kecamatan, dan Kabupaten Pemalang; mengganti kecamatan menghapus desa yang tidak cocok di UI.

Migrasi tunggal `2026_10_18_000001_add_village_to_user_addresses.php` menambah `village_id` nullable beserta FK ke `administrative_areas`. Kolom kecamatan tetap sama untuk kompatibilitas statistik/peta. Domisili lama tidak diberi desa secara tebakan.

Pada workspace lain/komputer kedua, jalankan migrasi biasa dan `php artisan db:seed --class=PemalangVillageSeeder` untuk menambahkan referensi tanpa reset. Entry point seeder development reguler juga memanggil referensi tersebut.

## Profiling, OAuth dan kompatibilitas akun lama

Minat pendaftaran disimpan langsung ke relasi Youth Interest yang sama, sehingga langsung terbaca oleh Development Pathway dan discovery. Tidak ada kategori profiling baru.

Bootstrap OAuth Google tidak dirombak. Pengguna Google/akun lama yang belum memiliki NIK melengkapi Biodata, desa dan minat sebelum mengajukan bukti. Bantuan DOB yang sama juga dipakai di Biodata. Kontak yang memang diperlukan alur lama tetap berada pada langkah Kontak. Penyimpanan minat menggunakan `SyncUserInterestsAction` yang sudah ada.

Akun lama tetap dapat login dan mengakses halaman publik sesuai izin semula. Status verifikasi historis tidak dihapus. NIK yang belum ada diberi prompt pelengkapan ketika diperlukan untuk pengajuan bukti. Pengguna dengan pilihan minat lama lebih dari lima melihat seluruh pilihan lama beserta instruksi untuk mengurangi pilihan sebelum menyimpan, tanpa pemotongan diam-diam.

Kolom nomor dokumen lama dan seluruh riwayat tetap disimpan. Pengajuan baru memakai fingerprint NIK sebagai konteks tanpa menyimpan nomor input tambahan. Admin masih dapat meninjau riwayat; nomor historis ditampilkan tersamarkan. Kartu Pelajar disebut dokumen pendukung, bukan identitas nasional.

Area progres onboarding yang lama kini juga menunjukkan akun dibuat, email diverifikasi, data pendaftaran dilengkapi, identitas diajukan, dan identitas terverifikasi. Gate bisnis dan izin role tidak diubah. Minat yang sudah dipilih lebih awal memenuhi bagian minat pada baseline portfolio; menghapusnya tetap mengunci ulang fitur yang mensyaratkannya.

## Data demo

`DemoIdentityAnchorSeeder` hanya bekerja di local/testing pada akun Youth beralamat `@sipora.test`. Ia mengisi anchor yang belum valid dengan **NIK sintetis 16 digit berawalan 000000**, menggunakan tanggal fixture dan serial unik. Prefiks tersebut sengaja tidak mewakili wilayah NIK nyata. Tidak mengklaim keaslian Dukcapil dan tidak membuat dokumen identitas palsu untuk pengguna nyata.

NIK valid yang sudah ada, UUID, status verifikasi, relasi golden demo, dokumen dan riwayat dipertahankan. Seeder ini dipanggil setelah entry point Database/Competition demo; standalone fixture lama tetap dapat merepresentasikan akun legacy. Pengujian memastikan fingerprint unik dan tidak berubah saat seeder kompetisi diulang.

Pada database pengembangan saat fase ini, hanya migrasi tambahan, `PemalangVillageSeeder` dan `DemoIdentityAnchorSeeder` yang dijalankan. Tidak menggunakan migrate:fresh, truncate, atau reseed demo penuh.

## QA browser

- Keempat langkah diperiksa pada **1440×1000, 768×1024, 390×844, 360×800, 412×915**: tidak ada overflow horizontal. Navigasi bawah hanya tampil pada mobile.
- Prefill NIK sintetis bekerja; DOB tetap editable dan enabled. Koreksi manual bertahan; gender tetap pilihan manual.
- Moga memuat desa Moga. Mengganti ke Taman membersihkan pilihan Banyumudal dan memuat daftar desa Taman.
- Pilihan keenam ditolak dengan “Pilih maksimal 5 bidang minat.” Counter tetap lima.
- Konfirmasi menampilkan hanya NIK tersamarkan dan tidak menampilkan password. Percobaan kirim dengan password kosong kembali ke Data Diri, tanpa pembuatan akun.
- Tombol kirim mobile dapat digulir sampai berada di atas navigasi bawah. Offset back-to-top auth dirapikan agar terpisah dari badge reCAPTCHA.
- Pendaftaran dan Profiling Biodata diperiksa dalam dark mode. Stepper Biodata mobile dirapikan menjadi lima penanda kompak.
- Akun demo dengan identitas unverified dapat membuka upload bukti; terlihat NIK tersamarkan dan tidak ada input nomor dokumen. Tidak mengunggah/mengirim bukti nyata atau mengambil keputusan review selama QA.
- Sesi demo hanya dipakai untuk pemeriksaan; isian uji Biodata tidak disimpan. Registrasi manual tidak membuat kredensial/akun melalui browser. Pengiriman penuh, rollback, email dan review diuji pada database pengujian terisolasi.
- Konsol pada pemeriksaan akhir: tidak ditemukan error JS/Alpine.

## Tes dan hasil

Baru:
- `tests/Feature/RegistrationConsolidationTest.php`: delapan kasus mencakup format/duplikat/privasi NIK, DOB/usia/gender/alamat/minat, atomic rollback, tiga jenis bukti tanpa nomor ulang, riwayat/gender legacy, parser dan referensi lokal, serta pelengkapan akun Google/legacy.
- `tests/Frontend/registration.test.mjs`: delapan kasus perilaku parser, koreksi manual, gender manual, max-minat, desa dependen, navigasi, summary masking dan guard pengiriman akhir.

Diperbarui: fixture autentikasi/reliabilitas, pengajuan identitas, validasi Indonesia, landing pendaftaran, alur Youth dan demo seeder. Fixture onboarding mempertahankan NIK ketika dipakai berulang. Assertion lama bahwa semua demo tidak mempunyai NIK diganti dengan pemeriksaan sintetis/unik/idempotent. Assertion minat baru setelah verifikasi disesuaikan karena minat sekarang tersimpan sejak registrasi/Biodata.

- **Backend lengkap: 412 passed, 3.536 assertions**, database `sipora_regression_test`, guard TestEnvironmentGuard tetap aktif. Tidak ada RefreshDatabase pada database development.
- **Frontend Node: 41 passed, 0 failed**.
- Build final: **lulus**, Vite selesai dalam 13,74 detik.
- `git diff --check`: **lulus**. Blade view cache juga lulus.

Log lokal: `storage/app/registration-regression-final.log`, `registration-frontend.log`, `registration-build.log`. Bukti visual: `registration-desktop-dark.png`, `registration-mobile-confirmation.png`.

## File utama fase ini

- Backend baru: `config/youth.php`, `app/Support/Nik.php`, `app/Rules/{YouthAge,UniqueNik,PemalangAddress}.php`, `app/Services/Youth/{IdentityAnchorService,RegistrationReferenceService}.php`.
- Backend diubah: `RegisterUser`, `SubmitIdentityVerificationAction`, `UpdateDomicileAction`, controller/request Auth/Biodata/Identity terkait, model UserIdentity/UserAddress, YouthOnboardingService/YouthEligibilityService, routes/auth, bootstrap/app dan label atribut Indonesia.
- Referensi: migrasi village_id, dua seeder baru, DatabaseSeeder/CompetitionDemoSeeder, komentar DemoYouthSeeder, subset JSON dan dokumen lisensi.
- UI: `resources/js/registration.js`, `resources/js/app.js`, CSS app, auth/register, youth/biodata, youth/identity-verification, youth/onboarding, dan review Admin identitas. Pilihan gender pada komponen profil bersama juga diselaraskan.
- Tests: TestCase helper, feature/frontend baru serta fixture/asersi pada AuthenticationTest, AuthenticationReliabilityTest, IdentitySubmissionTest, IndonesianValidationTest, PublicLandingPageTest, YouthExperienceCorrectionTest, YouthProgressiveOnboardingTest dan CompetitionDemoSeederTest.
- Perubahan UI dan setup kompetisi yang sudah ada sebelum fase ini dipertahankan.

## Batas yang tetap berlaku

Tidak ada integrasi Dukcapil. Parser adalah bantuan format/tanggal, bukan pembuktian identitas. Dataset alamat merupakan snapshot lokal, bukan sinkronisasi real-time. Akun historis dan status verified fixture tidak otomatis mendapat dokumen baru. Review identitas tetap berada di Admin sesuai otorisasi aplikasi yang sudah ada.
