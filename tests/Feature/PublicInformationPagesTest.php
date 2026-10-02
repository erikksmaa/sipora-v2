<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicInformationPagesTest extends TestCase
{
    public function test_about_page_is_public_and_describes_verification_accurately(): void
    {
        $this->get(route('about'))->assertOk()
            ->assertSee('Ekosistem pemuda dalam satu ruang')
            ->assertSee('Tercatat mandiri')->assertSee('Diverifikasi SIPORA')
            ->assertSee('Skill atau keahlian')->assertSee('Partisipasi Activity yang dinyatakan selesai')
            ->assertDontSee('semua pemuda terverifikasi');
    }

    public function test_contact_page_handles_missing_optional_official_data_without_a_form(): void
    {
        config(['sipora.contact' => [
            'organization' => 'Dindikpora Kabupaten Pemalang', 'address' => null, 'email' => null,
            'phone' => null, 'website' => null, 'service_hours' => null,
        ]]);

        $this->get(route('contact'))->assertOk()->assertSee('Dindikpora Kabupaten Pemalang')
            ->assertSee('Belum dikonfigurasi.')->assertDontSee('<form', false)
            ->assertDontSee('Lorem Ipsum')->assertDontSee('WhatsApp');
    }

    public function test_public_navigation_and_footer_link_to_all_real_destinations(): void
    {
        $response = $this->get(route('about'))->assertOk();
        foreach (['youth-directory.index', 'about', 'contact', 'activities.index', 'communities.index', 'opportunities.index', 'programs.index', 'search.index'] as $route) {
            $response->assertSee(route($route), false);
        }
        $response->assertSee('Tentang SIPORA')->assertSee('Kontak')->assertSee('Pemuda');
    }

    public function test_public_shell_exposes_keyboard_and_mobile_navigation_hooks(): void
    {
        $this->get(route('about'))->assertOk()
            ->assertSee('x-data="publicNavigation"', false)
            ->assertSee('x-ref="mobileTrigger"', false)
            ->assertSee('x-ref="mobileClose"', false)
            ->assertSee('aria-haspopup="menu"', false)
            ->assertSee('<main id="main" tabindex="-1">', false);
    }
}
