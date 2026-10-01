# SIPORA v2 — QA Testing Guide

Dokumen ini menjelaskan dataset QA lokal, urutan pengujian end-to-end, dan area risiko yang perlu diperiksa sebelum Phase 23A.

## Reset dataset

Database pengembangan kanonis adalah `sipora`.

```bash
php artisan migrate:fresh --seed
```

Seluruh akun demo memakai password `password`. Seeder modular dan `QualityAssuranceSeeder` tidak berjalan pada environment `production`.

## Cakupan minimal sepuluh baris

Seeder QA menjamin minimal sepuluh baris pada seluruh tabel aplikasi yang layak menerima fixture: akun dan profil Youth, enrichment, identity, Community, membership, Activity, review, session, participation, attendance, certificate, Program, Proposal, Logbook, E-LPJ, evaluation, Opportunity, bookmark, notification, dan audit.

Tabel berikut sengaja tidak dipaksa menjadi sepuluh baris:

| Tabel | Alasan |
| --- | --- |
| `roles` | Keputusan proyek mengunci tepat tiga global roles: `youth`, `verifier`, `admin`. |
| `model_has_permissions` | SIPORA menggunakan permission melalui role; direct user permission sengaja kosong. |
| `migrations` | Diisi Laravel berdasarkan migration yang benar-benar dijalankan. |
| `cache`, `cache_locks` | Data runtime sementara, bukan fixture produk. |
| `jobs`, `job_batches`, `failed_jobs` | Data operasional queue; membuat job palsu dapat memicu worker. |
| `sessions` | Sesi login runtime; fixture akan menyerupai sesi pengguna aktif. |
| `password_reset_tokens` | Token keamanan sekali pakai tidak boleh menjadi data demo. |

`permissions`, `role_has_permissions`, dan `model_has_roles` tetap mengikuti konfigurasi keamanan nyata dan secara alami memiliki lebih dari sepuluh baris setelah akun QA dibuat.

## Akun dan keadaan identitas

| Akun | Peran utama untuk QA | Identity state |
| --- | --- | --- |
| `admin@sipora.test` | Admin identity, Opportunity, analytics | N/A |
| `verifier@sipora.test` | Review Community, Activity, Proposal, Logbook, E-LPJ, evaluation | N/A |
| `youth1@sipora.test` | Leader Community utama; Portfolio kaya | verified |
| `youth2@sipora.test` | Manager; privacy checks | pending |
| `youth3@sipora.test` | Leader Community kedua; cross-context checks | revision |
| `youth4@sipora.test` | Pending/rejected flow | rejected |
| `youth5@sipora.test` | Left/cancelled flow | verified |
| `youth6@sipora.test` | Additional membership/participation QA | pending |
| `youth7@sipora.test` | Additional membership/participation QA | revision |
| `youth8@sipora.test` | Additional membership/participation QA | rejected |
| `youth9@sipora.test` | Additional membership/participation QA | verified |
| `youth10@sipora.test` | Private Portfolio and empty/public boundary | pending |

Nomor identitas, dokumen, kontak, prestasi, sertifikat eksternal, dan organisasi dengan label QA adalah data fiktif lokal.

## Urutan pengujian yang efektif

Gunakan browser terpisah atau incognito untuk setiap role agar session tidak tercampur. Catat URL, akun, langkah, hasil yang diharapkan, hasil aktual, dan screenshot untuk setiap kegagalan.

### 1. Baseline read-only

1. Reset database.
2. Jalankan `php artisan test`, `npm.cmd run build`, dan `php vendor/bin/pint --test`.
3. Sebagai Guest, buka landing, Activity, Community, Opportunity, Program, search, Portfolio publik, dan certificate verification.
4. Pastikan draft, revision, rejected, archived, dan evidence privat tidak muncul.

Kerjakan baseline ini dahulu karena tidak mengubah state dan memberi titik pembanding yang stabil.

### 2. Authentication dan account security

1. Uji registrasi manual, email verification, login gagal, rate limit, password reset, dan logout.
2. Pastikan login Google hanya metode autentikasi dan tidak mengubah identity menjadi verified.
3. Pastikan email verification juga tidak mengubah SIPORA identity.
4. Periksa redirect per role: Youth, Verifier, dan Admin harus menuju workspace masing-masing.

### 3. Youth profile dan identity submission

1. Gunakan `youth1` untuk profil lengkap dan Portfolio kaya.
2. Gunakan `youth2` untuk memastikan section privat hanya terlihat oleh pemilik.
3. Gunakan `youth10` untuk memastikan Portfolio privat mengembalikan 404 kepada Guest.
4. Gunakan akun pending/revision untuk upload, replacement, dan resubmission dokumen.
5. Coba URL submission akun lain dan pastikan IDOR ditolak.

### 4. Admin identity review

1. Login sebagai Admin dan buka antrean identity.
2. Uji approve, revision, dan reject pada akun berbeda.
3. Pastikan alasan wajib untuk revision/reject.
4. Pastikan dokumen hanya dapat dibuka lewat route privat terotorisasi.
5. Logout lalu buka ulang URL dokumen sebagai Guest, Youth, dan Verifier; semuanya harus ditolak.

Reset database setelah rangkaian ini agar skenario role berikut tetap deterministik.

### 5. Community application dan verification

1. Buat draft Community sebagai Youth, simpan, edit, lalu submit.
2. Coba edit saat `pending_review`; perubahan harus ditolak.
3. Login sebagai Verifier untuk approve/revision/reject.
4. Pastikan approval mengaktifkan Community dan membuat contextual leader tanpa role global baru.
5. Uji Manager Community A terhadap Community B; akses harus ditolak.

### 6. Membership dan Manager workspace

1. Ajukan join sebagai Youth biasa.
2. Accept dan reject menggunakan leader/manager yang tepat.
3. Uji perubahan contextual role, remove member, leave, dan perlindungan last leader.
4. Pastikan Admin dan Verifier tidak otomatis mendapat akses Manager workspace.

### 7. Activity end-to-end

1. Sebagai Manager: buat draft, edit, tambah session, lalu submit.
2. Sebagai Verifier: request revision, resubmit dari Manager, kemudian approve.
3. Sebagai Manager: publish Activity.
4. Sebagai Youth: register pada mode open dan approval-required.
5. Sebagai Manager: accept/reject participant, catat attendance per session, selesaikan Activity, lalu tentukan completion/no-show.
6. Pastikan Attendance tidak otomatis mengubah completion.
7. Terbitkan certificate hanya untuk accepted + completed participation.

### 8. Program end-to-end

1. Sebagai contextual Manager: buat Program dan tautkan Activity dalam Community yang sama.
2. Buat Proposal draft, upload dokumen privat, submit, lalu review sebagai Verifier.
3. Mulai execution hanya setelah Proposal terbaru approved dan composition valid.
4. Buat Logbook, tambah media privat, submit, dan review.
5. Buat E-LPJ, tambah income/expense dan receipt privat, submit, dan review.
6. Lakukan final evaluation setelah semua prasyarat terpenuhi.
7. Pastikan hanya keputusan approved yang mengubah Program menjadi completed.
8. Pastikan completed Program mengunci edit normal, Logbook, E-LPJ, dan relinking Activity.

### 9. Opportunity, notification, dan analytics

1. Admin membuat draft Opportunity, mengedit, publish, dan archive.
2. Guest hanya melihat published Opportunity.
3. Youth bookmark/unbookmark dan tidak dapat melihat bookmark akun lain.
4. Periksa notification ownership serta mark-one/mark-all-read.
5. Cocokkan angka analytics dengan query database; tidak boleh ada ranking atau score implisit.

### 10. Regression lintas role

Setelah seluruh mutation flow selesai:

1. Jalankan ulang full test suite.
2. Reset dan seed kembali database.
3. Ulangi smoke test Guest, Youth, Manager, Verifier, dan Admin.
4. Periksa desktop serta mobile untuk halaman paling panjang dan tabel antrean.

## Area yang perlu perhatian lebih

1. **Authorization dan IDOR** — selalu ganti UUID/slug pada URL dengan record milik akun atau Community lain.
2. **State transition** — uji replay, double approval, terminal decision, stale form, dan edit setelah submit.
3. **Private storage** — identity document, Proposal, Logbook media, dan receipt tidak boleh memiliki public URL atau path di HTML/log/notifikasi.
4. **Pemisahan role** — Admin menangani personal identity; Verifier menangani Community/Activity/Program; leader/manager/member tetap contextual.
5. **Identity semantics** — email verification, Google OAuth, dan student card tidak boleh disalahartikan sebagai national identity.
6. **Program completion** — Proposal approval, Logbook approval, dan E-LPJ approval tidak boleh sendiri-sendiri menyelesaikan Program.
7. **Attendance vs completion** — kehadiran adalah evidence; Manager tetap membuat keputusan completion/no-show.
8. **Public boundary** — hanya Community aktif, Activity approved+published, Opportunity published, dan Program yang lolos rule publik yang boleh tampil.
9. **Portfolio privacy** — hidden section, kontak, tanggal lahir, alamat detail, identity, dan private download tidak boleh muncul kepada Guest.
10. **UUIDv7 `BINARY(16)`** — periksa route binding, foreign key, serialization, queue, dan malformed identifier.
11. **Nilai uang** — uji desimal, total income/expense, over-budget warning, dan bukti receipt tanpa floating-point drift.
12. **Concurrency/replay** — kirim keputusan review yang sama dua kali atau dari dua tab; keputusan terminal tidak boleh tertimpa.
13. **Notifikasi dan audit** — pastikan actor, subject, decision, dan waktu benar tanpa NIK, path dokumen, receipt, atau payload sensitif.
14. **Responsive dan accessibility** — fokus keyboard, label, status nonwarna, overflow tabel, nama panjang, dan CTA mobile.
15. **External Opportunity** — SIPORA hanya mengkurasi dan menyimpan bookmark; jangan menampilkan seolah aplikasi eksternal dilakukan di SIPORA.

## Query audit jumlah data

Gunakan query berikut setelah seed untuk melihat seluruh jumlah aktual:

```sql
SELECT table_name, table_rows
FROM information_schema.tables
WHERE table_schema = 'sipora'
ORDER BY table_name;
```

Untuk angka pasti gunakan `SELECT COUNT(*)` pada tabel yang sedang diperiksa; `information_schema.table_rows` dapat berupa estimasi pada InnoDB.
