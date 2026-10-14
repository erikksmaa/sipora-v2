<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class FrontendPolishTest extends TestCase
{
    use RefreshDatabase;

    public function test_author_identity_links_and_photo_respect_public_visibility(): void
    {
        $author = User::factory()->create();
        $author->profile()->create(['public_slug' => 'public-author-demo', 'full_name' => 'Nama Privat Contoh', 'birth_date' => '2003-01-01', 'profile_photo_path' => 'portraits/example.png']);
        $author->profileVisibility()->create(['is_profile_public' => false, 'show_photo' => true]);
        $render = fn () => Blade::render('<x-public.author-identity :author="$author" />', ['author' => $author->fresh()]);
        $private = $render();
        $this->assertStringNotContainsString('Nama Privat Contoh', $private);
        $this->assertStringNotContainsString('public-author-demo', $private);
        $this->assertStringNotContainsString('<img', $private);
        $this->assertStringNotContainsString('<a ', $private);
        $author->profileVisibility()->update(['is_profile_public' => true, 'show_photo' => false]);
        $public = $render();
        $this->assertStringContainsString(route('portfolio.show', 'public-author-demo'), $public);
        $this->assertStringContainsString('Nama Privat Contoh', $public);
        $this->assertStringNotContainsString('<img', $public);
        $author->profileVisibility()->update(['show_photo' => true]);
        $this->assertStringContainsString(route('portfolio.photo', 'public-author-demo'), $render());
    }
}
