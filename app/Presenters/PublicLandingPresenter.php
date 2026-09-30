<?php

namespace App\Presenters;

use App\Models\Interest;
use App\Services\Discovery\ActivityDiscoveryService;
use App\Services\Discovery\CommunityDiscoveryService;
use App\Services\Discovery\OpportunityDiscoveryService;

/**
 * Mixed public landing read model. Activity, Community, and Interest data come
 * from approved domain tables. Future domains remain isolated placeholders.
 */
final class PublicLandingPresenter
{
    public function __construct(
        private readonly ActivityDiscoveryService $activities,
        private readonly CommunityDiscoveryService $communities,
        private readonly OpportunityDiscoveryService $opportunities,
    ) {}

    /** @return array<string, mixed> */
    public function present(): array
    {
        $interestIcons = ['💻', '🎨', '🏃', '🌱', '📚', '🤝', '💼', '🎭'];
        $databaseInterests = Interest::query()->orderBy('name')->limit(8)->get();
        $interests = $databaseInterests->isNotEmpty()
            ? $databaseInterests->values()->map(fn (Interest $interest, int $index): array => [
                'icon' => $interestIcons[$index % count($interestIcons)],
                'name' => $interest->name,
            ])->all()
            : [
                ['icon' => '💻', 'name' => 'Teknologi'], ['icon' => '🎨', 'name' => 'Seni & Kreatif'],
                ['icon' => '🏃', 'name' => 'Olahraga'], ['icon' => '🌱', 'name' => 'Lingkungan'],
                ['icon' => '📚', 'name' => 'Pendidikan'], ['icon' => '🤝', 'name' => 'Sosial'],
                ['icon' => '💼', 'name' => 'Kewirausahaan'], ['icon' => '🎭', 'name' => 'Budaya'],
            ];

        return [
            'presentation' => [
                'is_placeholder' => true,
                'source' => 'mixed_landing_presentation',
                'database_domains' => ['interests', 'activities', 'communities', 'opportunities'],
                'placeholder_domains' => ['programs', 'statistics', 'stories'],
                'interests_source' => $databaseInterests->isNotEmpty() ? 'database' : 'placeholder_fallback',
            ],
            'interests' => $interests,
            'activities' => $this->activities->featured(),
            'communities' => $this->communities->featured(),
            'opportunities' => $this->opportunities->featured(),
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
