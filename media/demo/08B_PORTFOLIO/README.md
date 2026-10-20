# SIPORA Batch 8B — Portfolio supporting media

12 JPG: Y001 memiliki 4 bukti; Y002, Y003, Y004, Y007, Y010, Y012, Y014, dan Y017 masing-masing 1.

Lima gambar proyek merupakan screenshot browser dari HTML dummy asli di projects/.
Halaman tersebut adalah preview statis yang bisa diedit, bukan aplikasi produksi dengan backend.
Seluruh copy, metrik, dan proyek adalah fiktif. Screenshot ditangkap dari browser lalu dikonversi ke JPEG tanpa mengubah komposisi.
Enam dokumentasi memakai binary existing yang sama persis. Foto kegiatan umum menunjukkan konteks kegiatan,
bukan bukti bahwa wajah tertentu adalah pemilik portfolio. Mapping mencatat pembatasan tersebut.
Foto penghargaan Y001 dibuat dengan built-in imagegen memakai profil Erik sebagai referensi wajah.
Prompt final tersimpan di generation-prompts.json. Sertifikat pada foto sengaja tanpa tulisan.
Galeri fotografi Y012 adalah kurasi foto demo, bukan klaim pemotretan nyata oleh Luthfi.

Integrasi: Paket aset dan mapping disiapkan; belum diimpor ke database. Model PortfolioEvidence saat ini
hanya menerima education dan organization; achievement mempunyai evidence_path tersendiri.
Tidak ada jenis project generik pada schema tersebut. Hindari memasukkan screenshot proyek sebagai
sertifikat/pendidikan palsu. Gunakan mapping sebagai sumber seeder ketika dukungan project tersedia.

Verifikasi: 12 JPEG valid; ukuran, checksum, serta enam pasangan reuse diperiksa.
Lima HTML dan screenshot browser diperiksa secara visual. Buka index.html untuk meninjau seluruh aset.
