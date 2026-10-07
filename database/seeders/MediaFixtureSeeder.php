<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\FinancialItem;
use App\Models\Organization;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

final class MediaFixtureSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        // Enrich only demo-owned records; never replace manually uploaded media.
        foreach (Organization::whereHas('creator', fn ($q) => $q->where('email', 'like', '%@sipora.test'))->get() as $record) {
            if (! $this->replaceable($record->logo_path)) {
                continue;
            }
            $variant = (hexdec(substr(hash('sha256', $record->slug), 0, 6)) % 3) + 1;
            $path = 'phase23c/'.$record->uuid().'/logo.svg';
            Storage::disk('community_media')->put($path, file_get_contents(database_path("fixtures/media/organizations/logo-{$variant}.svg")));
            $record->forceFill(['logo_path' => $path])->saveQuietly();
        }
        foreach (UserProfile::whereHas('user', fn ($q) => $q->where('email', 'like', '%@sipora.test'))->get() as $record) {
            if (! $this->replaceable($record->profile_photo_path)) {
                continue;
            }
            $variant = $record->gender === 'female' ? 2 : 1;
            $path = 'phase23c/profiles/'.$record->uuid().'/photo.png';
            Storage::disk('public')->put($path, file_get_contents(database_path("fixtures/media/youth/portrait-{$variant}.png")));
            $record->forceFill(['profile_photo_path' => $path])->saveQuietly();
        }
        foreach (Activity::whereHas('creator', fn ($q) => $q->where('email', 'like', '%@sipora.test'))->get() as $record) {
            if (! $this->replaceable($record->poster_path)) {
                continue;
            }
            $variant = (hexdec(substr(hash('sha256', $record->slug), 0, 6)) % 3) + 1;
            $svg = file_get_contents(public_path("media/phase23c/activities/scene-{$variant}.svg"));
            $svg = str_replace('RUANG BELAJAR &amp; BERKARYA', htmlspecialchars(mb_strimwidth($record->title, 0, 65, '...'), ENT_XML1, 'UTF-8'), $svg);
            $path = 'phase23c/'.$record->uuid().'/poster.svg';
            Storage::disk('activity_media')->put($path, $svg);
            $record->forceFill(['poster_path' => $path])->saveQuietly();
        }
        foreach (FinancialItem::whereHas('report.program.creator', fn ($q) => $q->where('email', 'like', '%@sipora.test'))->get() as $record) {
            $existing = $record->receipt_path;
            if (! $existing) {
                continue;
            }
            if (! str_starts_with($existing, 'phase23c/') && ! str_contains($existing, '/development-')) {
                continue;
            }
            $path = 'phase23c/'.$record->uuid().'/receipt.png';
            Storage::disk('financial_receipts')->put($path, $this->receipt($record));
            $record->forceFill(['receipt_path' => $path])->saveQuietly();
        }
    }

    private function replaceable(?string $path): bool
    {
        return ! $path || str_starts_with($path, 'phase23c/');
    }

    private function receipt(FinancialItem $item): string
    {
        // Deterministic document fixture, not a merchant invoice or valid payment proof.
        $image = imagecreatetruecolor(760, 540);
        $paper = imagecolorallocate($image, 250, 248, 243);
        $ink = imagecolorallocate($image, 39, 63, 79);
        $accent = imagecolorallocate($image, 254, 119, 67);
        imagefill($image, 0, 0, $paper);
        imagefilledrectangle($image, 25, 25, 735, 95, $ink);
        imagestring($image, 5, 45, 45, 'SIPORA - BUKTI TRANSAKSI DEMO', $paper);
        imagestring($image, 4, 45, 120, 'FIKTIF / HANYA UNTUK PENGUJIAN LOKAL', $ink);
        imagestring($image, 4, 45, 175, 'Tanggal: '.$item->transaction_date->format('Y-m-d'), $ink);
        foreach (explode("\n", wordwrap($item->description, 65)) as $index => $line) {
            imagestring($image, 4, 45, 215 + $index * 23, $line, $ink);
        }
        imageline($image, 45, 345, 715, 345, $ink);
        imagestring($image, 5, 45, 370, 'TOTAL Rp '.number_format((float) $item->amount, 2, ',', '.'), $ink);
        imagefilledrectangle($image, 45, 440, 715, 490, $accent);
        imagestring($image, 4, 60, 455, 'PRIVAT - BUKAN DOKUMEN TRANSAKSI NYATA', $ink);
        ob_start();
        imagepng($image);
        $bytes = ob_get_clean();
        imagedestroy($image);

        return $bytes;
    }
}
