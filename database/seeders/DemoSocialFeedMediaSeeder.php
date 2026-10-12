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
            $org = $entry['organization_slug'] ? Organization::where('slug', $entry['organization_slug'])->firstOrFail() : null;
            $author = $org ? $org->creator : User::where('name', $entry['youth_name'])->where('email', 'like', '%@sipora.test')->firstOrFail();
            if (! $author) {
                throw new RuntimeException('Author demo belum tersedia.');
            }
            $path = null;
            if ($entry['file']) {
                $source = $root.'/'.$entry['file'];
                if (! is_file($source)) {
                    throw new RuntimeException('Media feed belum tersedia: '.$entry['file']);
                }
                $path = 'social/demo-batch-8a/'.hash_file('sha256', $source).'.jpg';
                if (! Storage::disk('private')->exists($path)) {
                    Storage::disk('private')->put($path, file_get_contents($source));
                }
            }
            $records[] = [$entry, $org, $author, $path];
        }
        DB::transaction(function () use ($records): void {
            foreach ($records as $i => [$entry, $org, $author, $path]) {
                $post = SocialPost::firstOrCreate([
                    'author_id' => $author->getKey(), 'organization_id' => $org?->getKey(),
                    'body' => $entry['caption']."\n\nDATA DEMO SIPORA",
                ], ['image_path' => $path, 'visibility' => 'public', 'is_hidden' => false, 'is_removed' => false]);
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
