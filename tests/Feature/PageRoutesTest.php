<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageRoutesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test all converted pages return 200 OK.
     */
    public function test_all_pages_return_successful_response(): void
    {
        $routes = [
            '/' => 'We Build Brands',
            '/about' => 'One team. Four disciplines',
            '/solutions' => 'Four ways we grow you',
            '/work' => 'Recent proof, not promises',
            '/tickets' => 'RK Vibes Presents — Fall Gathering 2026',
            '/insights' => 'Notes on growth, brand, and events',
            '/contact' => 'Send a Message',
        ];

        foreach ($routes as $url => $expectedContent) {
            $response = $this->get($url);
            $response->assertStatus(200);
            $response->assertSee($expectedContent);
        }
    }

    /**
     * Test named route generation.
     */
    public function test_named_routes_resolve(): void
    {
        $this->assertEquals(url('/'), route('home'));
        $this->assertEquals(url('/about'), route('about'));
        $this->assertEquals(url('/solutions'), route('solutions'));
        $this->assertEquals(url('/work'), route('work'));
        $this->assertEquals(url('/tickets'), route('tickets'));
        $this->assertEquals(url('/insights'), route('insights'));
        $this->assertEquals(url('/contact'), route('contact'));
    }

    /**
     * Test public asset files exist on disk.
     */
    public function test_public_assets_exist(): void
    {
        $this->assertFileExists(public_path('assets/css/style.css'));
        $this->assertFileExists(public_path('assets/js/main.js'));
        $this->assertFileExists(public_path('assets/images/logo.png'));
        $this->assertFileExists(public_path('assets/images/logo.jpg'));
    }
}
