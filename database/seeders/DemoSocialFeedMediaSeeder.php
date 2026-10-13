<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\SocialPost;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserProfileVisibility;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use RuntimeException;
use App\Support\Demo\DemoAssetManifest;

final class DemoSocialFeedMediaSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local') || DB::connection()->getDatabaseName() !== 'sipora') {
            throw new RuntimeException('Social feed demo hanya untuk database local sipora.');
        }
        $root = public_path('media/demo/08A_SOCIAL_FEED');
        $manifest = json_decode(file_get_contents($root.'/manifest.json'), true, flags: JSON_THROW_ON_ERROR);
        // Separate asset identities from earlier development fixture names.
        foreach ([
            ['Y002', 'Aulia Rahma Putri', '2006-05-12', 'female', 'aulia-rahma-putri'],
            ['Y007', 'Dimas Pratama', '2004-07-19', 'male', 'dimas-pratama'],
            ['Y004', 'Nabila Putri Anjani', '2005-03-10', 'female', 'nabila-putri-anjani'],
        ] as [$id, $name, $birthDate, $gender, $slug]) {
            $user = User::firstOrCreate(['email' => 'asset.'.strtolower($id).'@sipora.test'], [
                'name' => $name, 'password' => Hash::make(UserSeeder::PASSWORD), 'email_verified_at' => now(),
            ]);
            $user->assignRole('youth');
            $source = public_path('media/demo/youth/'.$id.'-'.$slug.'.png');
            $avatar = 'demo-asset-profiles/'.$id.'.png';
            if (! Storage::disk('public')->exists($avatar)) {
                Storage::disk('public')->put($avatar, file_get_contents($source));
            }
            UserProfile::firstOrCreate(['user_id' => $user->getKey()], [
                'public_slug' => 'sipora-demo-'.$slug, 'full_name' => $name, 'birth_place' => 'Pemalang',
                'birth_date' => $birthDate, 'gender' => $gender, 'occupation_status' => 'student',
                'bio' => 'Karakter fiktif dataset visual SIPORA.', 'profile_photo_path' => $avatar,
            ]);
            UserProfileVisibility::firstOrCreate(['user_id' => $user->getKey()], [
                'is_profile_public' => true, 'show_photo' => true, 'show_bio' => true,
            ]);
        }
        $records = [];
        foreach (array_merge($manifest['media'], $manifest['text_posts']) as $entry) {
            $originalCaption = $entry['caption'];
            $canonicalFeed = null;
            if (DemoAssetManifest::enabled() && str_starts_with($entry['id'], 'FEED')) {
                $fid = 'F'.substr($entry['id'], 4);
                $canonicalFeed = DemoAssetManifest::data()['social_feed'][$fid];
                $owner = $canonicalFeed['author_entity'] ?? null;
                $related = $canonicalFeed['related_entity'] ?? null;
                if (! $owner && $related) {
                    $owner = match ($related[0]) {
                        'C' => $related,
                        'A' => DemoAssetManifest::data()['activities'][$related]['community_id'],
                        'O' => null, default => null,
                    };
                }
                if ($owner && $owner[0] === 'C') {
                    $entry['organization_slug'] = DemoAssetManifest::community($owner)->slug;
                    $entry['youth_name'] = null;
                } elseif ($owner && $owner[0] === 'Y') {
                    $entry['organization_slug'] = null;
                    $entry['youth_name'] = DemoAssetManifest::data()['youth'][$owner]['name'];
                } elseif ($related && $related[0] === 'O') {
                    $opportunity = \App\Models\Opportunity::where('slug', DemoAssetManifest::opportunitySlug($related))->firstOrFail();
                    $entry['organization_slug'] = $opportunity->organization->slug;
                    $entry['youth_name'] = null;
                }
                $copy = json_decode(file_get_contents(public_path('media/demo/08D_SOCIAL_CONTENT/content-mapping.json')), true, flags: JSON_THROW_ON_ERROR);
                $entry['caption'] = collect($copy['posts'])->firstWhere('source_asset_id', $entry['id'])['caption'];
                // Canonical F012/F013 are shared by Erik; the photos depict other fictional Youth.
                if ($fid === 'F012') $entry['caption'] = 'Berbagi momen teman-teman setelah sesi belajar desain. Senang melihat mereka saling memberi masukan dan mencoba hal baru.';
                if ($fid === 'F013') $entry['caption'] = 'Melihat proses pengerjaan proyek visual teman membuat saya ingin lebih rajin mendokumentasikan proses belajar sendiri.';
                if ($fid === 'F007') $entry['caption'] = 'Satu ide bisa punya banyak cara untuk disampaikan. Dalam sesi ini kami mencoba beberapa versi desain, lalu saling memberi masukan.';
                if ($fid === 'F015') $entry['caption'] = 'Ada kesempatan Beasiswa Pemuda Digital di Opportunity Hub. Baca detail dan persyaratannya sebelum memilih peluang yang sesuai.';
            }
            $org = $entry['organization_slug'] ? Organization::where('slug', $entry['organization_slug'])->firstOrFail() : null;
            $author = $org ? $org->creator : User::where('name', $entry['youth_name'])->where('email', 'like', '%@sipora.test')->firstOrFail();
            if (! $author) {
                throw new RuntimeException('Author demo belum tersedia.');
            }
            $path = null;
            if ($entry['file']) {
                $source = $canonicalFeed ? DemoAssetManifest::source($canonicalFeed['media']) : $root.'/'.$entry['file'];
                if (! is_file($source)) {
                    throw new RuntimeException('Media feed belum tersedia: '.$entry['file']);
                }
                $path = 'social/demo-batch-8a/'.hash_file('sha256', $source).'.jpg';
                if (! Storage::disk('private')->exists($path)) {
                    Storage::disk('private')->put($path, file_get_contents($source));
                }
            }
            $records[] = [$entry, $org, $author, $path, $originalCaption];
        }
        DB::transaction(function () use ($records): void {
            foreach ($records as $i => [$entry, $org, $author, $path, $originalCaption]) {
                $post = ($path ? SocialPost::where('image_path', $path)->first() : null)
                    ?? SocialPost::where('body', $originalCaption."\n\nDATA DEMO SIPORA")->first()
                    ?? SocialPost::firstOrCreate([
                    'author_id' => $author->getKey(), 'organization_id' => $org?->getKey(),
                    'body' => $entry['caption']."\n\nDATA DEMO SIPORA",
                ], ['image_path' => $path, 'visibility' => 'public', 'is_hidden' => false, 'is_removed' => false]);
                $post->forceFill(['author_id' => $author->getKey(), 'organization_id' => $org?->getKey(),
                    'body' => $entry['caption']."\n\nDATA DEMO SIPORA", 'image_path' => $path])->save();
                if ($post->wasRecentlyCreated) {
                    $minutes = $entry['file'] ? 20 + $i * 37 : 15 + ($i - 15) * 100;
                    $post->forceFill(['created_at' => now()->subMinutes($minutes)])->save();
                } elseif ($post->image_path !== $path) {
                    $post->forceFill(['image_path' => $path])->save();
                }
            }
        });
        $this->command?->info('Batch 8A: 15 post bergambar + 5 post teks. Media 5 Community / 5 Activity / 3 Youth / 2 Opportunity.');
    }
}
