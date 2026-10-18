# Referensi lokal Pemalang

Diambil 18 Oktober 2026 dari https://github.com/cahyadsn/wilayah/blob/master/db/wilayah.sql (kode/nama wilayah, MIT). Subset Kabupaten Pemalang: 14 kecamatan, 223 desa/kelurahan. SHA256 sumber ada di JSON. Tidak menjalankan SQL upstream; hanya pasangan kode/nama diekstrak. Tidak ada API runtime.

Seeder mencocokkan nama kecamatan ke master SIPORA yang sudah ada, mempertahankan UUID dan kode kecamatan lama. Kode desa upstream disimpan pada child administrative_areas; tidak membuat master kecamatan kedua.

MIT License

Copyright (c) 2025 cahya dsn

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
