# SIPORA Batch 8C — Miscellaneous demo assets

13 aset sesuai nama yang diminta: 1 avatar, 4 cover, 5 empty state, 3 file preview.
Semua dibuat melalui built-in imagegen dengan palet referensi dari resources/css/app.css:
Cream #FAF8F3, Slate #273F4F, Flame #FE7743. Warna raster dapat sedikit bervariasi akibat rendering.
Ilustrasi memakai outline slate, aksen orange, ruang kosong dan detail pixel yang ringan.

Ukuran:
- Avatar serta thumbnail dokumen/sertifikat/PDF: PNG 512 x 512.
- Empty states: PNG 640 x 640.
- Cover: JPG 1600 x 900 (16:9).
Semua berlatar cream opaque; tidak transparan, sesuai permintaan.
Master resolusi asli disimpan terpisah di output/demo-assets/misc-masters/.
Ekspor hanya resize/crop tengah dan konversi format; tidak menambahkan tulisan pada ilustrasi.

Pemakaian:
Manifest JSON dan asset-mapping.csv memetakan URL public serta konteks penggunaan.
Paket ini belum mengubah fallback komponen aplikasi. Gunakan URL pada manifest ketika
cover/foto kosong. Untuk avatar dan file preview gunakan object-fit: contain;
cover memakai object-fit: cover dengan object-position: center.
Empty states idealnya ditampilkan dengan lebar 160–240px dan copy HTML terpisah.
Jika ilustrasi hanya dekoratif, gunakan alt=""; bila menjadi informasi utama, gunakan alt yang bermakna.
Thumbnail PDF tidak memakai tulisan PDF pada gambar; label tipe file diberikan sebagai HTML.

Verifikasi:
13 nama file, format dan dimensi diperiksa; semua gambar ditinjau secara visual.
Manifest menyertakan ukuran file dan SHA256. Prompt final disertakan.
Buka index.html untuk melihat semua aset. Semua aset bersifat demo.
