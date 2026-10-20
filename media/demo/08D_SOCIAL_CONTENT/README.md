# SIPORA Batch 8D — Social Feed Content Mapping

20 caption siap pakai: 15 post dengan media, 5 post tanpa gambar.
Komposisi media tetap 5 Community, 5 Activity, 3 Youth, 2 Opportunity.
Panjang bervariasi; hanya satu caption memakai satu emoji. Tidak ada foto baru.
POST001–004 mengikuti empat contoh user. Media Creative Hub memakai fallback
feed-003-creative-hub.jpg karena community-002-gallery-02.jpg belum tersedia.
Penggantian tersebut tercatat di JSON/CSV, tanpa membuat file gallery yang menyesatkan.

content-mapping.json: mapping terstruktur dengan author, caption, source asset ID,
UUID post Batch 8A, relasi, sumber media, checksum, serta label demo.
content-mapping.csv: format siap dibaca spreadsheet/seeder.
captions.md: format editorial yang mudah disalin.
index.html: preview seluruh caption beserta media dan label demo.

Status: mapping belum diterapkan ke database. UUID berasal dari mapping hasil Batch 8A
dan berfungsi sebagai target update lokal, bukan jaminan UUID di database setelah reset.
Gunakan source_asset_id untuk relasi dataset lintas batch; nomor POST adalah urutan editorial.
Jika diterapkan di kemudian hari, update post yang sama agar tidak menambah duplikat.
Caption natural disimpan terpisah dari label demo; import_body menyertakan DATA DEMO SIPORA.
Kategori activity/opportunity adalah kategori editorial dan tidak mengubah filter feed native.

ZIP menyertakan salinan 15 gambar dalam media/ agar preview offline dapat dibuka.
Folder aplikasi mereferensikan aset existing secara langsung, tanpa duplikasi binary baru.
Nama author Community adalah identitas organisasi; pemilik database tetap creator organisasi.
Semua karakter, kegiatan, kesempatan, dan narasi adalah fiktif untuk demonstrasi.
