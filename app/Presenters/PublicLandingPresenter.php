<?php

namespace App\Presenters;

/**
 * Temporary presentation data for the public landing page.
 *
 * These values are not domain records and must be replaced by read-model queries
 * when the Activity, Community, Opportunity, Program, and analytics domains exist.
 */
final class PublicLandingPresenter
{
    /** @return array<string, mixed> */
    public function present(): array
    {
        return [
            'presentation' => ['is_placeholder' => true, 'source' => 'temporary_landing_presentation'],
            'interests' => [
                ['icon' => '💻', 'name' => 'Teknologi'], ['icon' => '🎨', 'name' => 'Seni & Kreatif'],
                ['icon' => '🏃', 'name' => 'Olahraga'], ['icon' => '🌱', 'name' => 'Lingkungan'],
                ['icon' => '📚', 'name' => 'Pendidikan'], ['icon' => '🤝', 'name' => 'Sosial'],
                ['icon' => '💼', 'name' => 'Kewirausahaan'], ['icon' => '🎭', 'name' => 'Budaya'],
            ],
            'activities' => [
                ['category' => 'Teknologi', 'title' => 'Workshop Web Development untuk Pemula', 'date' => '18 Oktober 2026', 'location' => 'Gedung Pemuda Pemalang', 'quota' => '32 dari 40 peserta', 'progress' => 80, 'image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=900&q=80', 'badge' => 'Pendaftaran Dibuka'],
                ['category' => 'Lingkungan', 'title' => 'Aksi Pemuda Hijau: Tanam 1.000 Mangrove', 'date' => '26 Oktober 2026', 'location' => 'Pantai Widuri, Pemalang', 'quota' => '76 dari 100 peserta', 'progress' => 76, 'image' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=900&q=80', 'badge' => 'Pendaftaran Dibuka'],
                ['category' => 'Kreatif', 'title' => 'Kelas Fotografi dan Cerita dari Pemalang', 'date' => '2 November 2026', 'location' => 'Pendopo Kabupaten Pemalang', 'quota' => '21 dari 35 peserta', 'progress' => 60, 'image' => 'https://images.unsplash.com/photo-1452780212940-6f5c0d14d848?auto=format&fit=crop&w=900&q=80', 'badge' => 'Kuota Terbatas'],
            ],
            'communities' => [
                ['initials' => 'KP', 'name' => 'Komunitas Programmer Pemalang', 'category' => 'Teknologi', 'members' => '286 anggota', 'activities' => '18 kegiatan'],
                ['initials' => 'PP', 'name' => 'Pemuda Peduli Pemalang', 'category' => 'Sosial & Lingkungan', 'members' => '194 anggota', 'activities' => '26 kegiatan'],
                ['initials' => 'SK', 'name' => 'Sanggar Kreatif Ikhlas', 'category' => 'Seni & Budaya', 'members' => '128 anggota', 'activities' => '14 kegiatan'],
            ],
            'opportunities' => [
                ['type' => 'Beasiswa', 'title' => 'Beasiswa Pemuda Berprestasi 2026', 'provider' => 'Dindikpora Kabupaten Pemalang', 'deadline' => '30 Oktober 2026', 'icon' => '🎓'],
                ['type' => 'Relawan', 'title' => 'Relawan Festival Literasi Pemalang', 'provider' => 'Forum Literasi Daerah', 'deadline' => '5 November 2026', 'icon' => '🙌'],
                ['type' => 'Inkubasi', 'title' => 'Inkubasi Wirausaha Muda Pemalang', 'provider' => 'Dinas Koperasi dan UMKM', 'deadline' => '12 November 2026', 'icon' => '🚀'],
            ],
            'programs' => [
                ['title' => 'Pemuda Digital 2026', 'description' => 'Pelatihan keterampilan digital dan pendampingan karier untuk pemuda Pemalang.', 'participants' => '742 peserta', 'progress' => 74, 'icon' => '⌘'],
                ['title' => 'Wirausaha Muda Mandiri', 'description' => 'Penguatan ide usaha, mentoring, dan akses jejaring bagi wirausaha muda.', 'participants' => '386 peserta', 'progress' => 58, 'icon' => '↗'],
                ['title' => 'Pemuda Pelopor Desa', 'description' => 'Ruang kolaborasi bagi pemuda yang menggerakkan perubahan di desa.', 'participants' => '214 peserta', 'progress' => 43, 'icon' => '✦'],
            ],
            'statistics' => [
                ['value' => '8.450+', 'label' => 'Pemuda Terdaftar'], ['value' => '142', 'label' => 'Komunitas Terverifikasi'],
                ['value' => '385', 'label' => 'Kegiatan Terlaksana'], ['value' => '14', 'label' => 'Kecamatan Terjangkau'],
            ],
            'stories' => [
                ['quote' => 'SIPORA membantu saya menemukan komunitas yang tepat dan menyusun pengalaman menjadi portofolio yang bisa dipercaya.', 'name' => 'Erik Kusuma Rais', 'role' => 'Pemuda · Pemalang', 'initials' => 'EK'],
                ['quote' => 'Sekarang informasi kegiatan pemuda ada di satu tempat. Saya lebih mudah belajar, berjejaring, dan ikut berkontribusi.', 'name' => 'Nabila Azzahra', 'role' => 'Relawan · Taman', 'initials' => 'NA'],
                ['quote' => 'Sertifikat dan riwayat aktivitas yang terverifikasi membuat pengalaman organisasi saya lebih bermakna.', 'name' => 'Rizky Pratama', 'role' => 'Penggerak komunitas · Petarukan', 'initials' => 'RP'],
            ],
        ];
    }
}
