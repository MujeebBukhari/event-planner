<?php

namespace Tests\Feature;

use App\Models\GalleryItem;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicDynamicContentTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'is_admin' => true,
        ]);
    }

    // 1. SERVICES DYNAMIC INTEGRATION

    public function test_active_services_are_displayed_on_solutions_and_home_pages(): void
    {
        Service::factory()->create([
            'title' => 'Bespoke Event Production',
            'slug' => 'bespoke-event-production',
            'description' => 'Complete festival and corporate event orchestration.',
            'icon' => 'Events',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->get(route('solutions'))
            ->assertOk()
            ->assertSee('Bespoke Event Production')
            ->assertSee('Complete festival and corporate event orchestration.');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Bespoke Event Production');
    }

    public function test_inactive_services_are_excluded_from_public_display(): void
    {
        Service::factory()->create([
            'title' => 'Secret Underground Shows',
            'slug' => 'secret-underground-shows',
            'is_active' => false,
        ]);

        $this->get(route('solutions'))
            ->assertOk()
            ->assertDontSee('Secret Underground Shows');

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('Secret Underground Shows');
    }

    public function test_services_respect_sort_order(): void
    {
        Service::factory()->create([
            'title' => 'Second Service',
            'slug' => 'second-service',
            'sort_order' => 20,
            'is_active' => true,
        ]);

        Service::factory()->create([
            'title' => 'First Service',
            'slug' => 'first-service',
            'sort_order' => 5,
            'is_active' => true,
        ]);

        $response = $this->get(route('solutions'))->assertOk();
        $content = $response->getContent();

        $firstPos = strpos($content, 'First Service');
        $secondPos = strpos($content, 'Second Service');

        $this->assertTrue($firstPos !== false && $secondPos !== false && $firstPos < $secondPos);
    }

    public function test_services_empty_state_renders_gracefully(): void
    {
        $this->get(route('solutions'))
            ->assertOk()
            ->assertSee('No active services currently listed');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('No active services currently listed');
    }

    // 2. GALLERY DYNAMIC INTEGRATION

    public function test_active_gallery_items_are_displayed_on_work_and_home_pages(): void
    {
        Storage::fake('public');
        $image = UploadedFile::fake()->image('gala.jpg');
        $path = $image->store('gallery', 'public');

        GalleryItem::factory()->create([
            'title' => 'Gala Dinner 2026',
            'description' => 'A premier gathering of cultural leaders and philanthropists.',
            'image_path' => $path,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->get(route('work'))
            ->assertOk()
            ->assertSee('Gala Dinner 2026')
            ->assertSee('A premier gathering of cultural leaders and philanthropists.')
            ->assertSee(Storage::url($path));

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Gala Dinner 2026');
    }

    public function test_inactive_gallery_items_are_excluded_from_public_display(): void
    {
        GalleryItem::factory()->create([
            'title' => 'Draft Unfinished Case Study',
            'is_active' => false,
        ]);

        $this->get(route('work'))
            ->assertOk()
            ->assertDontSee('Draft Unfinished Case Study');

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('Draft Unfinished Case Study');
    }

    public function test_gallery_items_respect_sort_order(): void
    {
        GalleryItem::factory()->create([
            'title' => 'Later Event',
            'sort_order' => 50,
            'is_active' => true,
        ]);

        GalleryItem::factory()->create([
            'title' => 'Earlier Event',
            'sort_order' => 10,
            'is_active' => true,
        ]);

        $response = $this->get(route('work'))->assertOk();
        $content = $response->getContent();

        $earlierPos = strpos($content, 'Earlier Event');
        $laterPos = strpos($content, 'Later Event');

        $this->assertTrue($earlierPos !== false && $laterPos !== false && $earlierPos < $laterPos);
    }

    public function test_gallery_empty_state_renders_gracefully(): void
    {
        $this->get(route('work'))
            ->assertOk()
            ->assertSee('No showcase gallery items available at this time');
    }

    // 3. TESTIMONIALS DYNAMIC INTEGRATION

    public function test_active_testimonials_are_displayed_on_about_and_home_pages(): void
    {
        Testimonial::factory()->create([
            'client_name' => 'Sophia Laurent',
            'position' => 'Creative Director at LuxVibe',
            'message' => 'RK Vibes delivered an unforgettable brand experience.',
            'rating' => 5,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->get(route('about'))
            ->assertOk()
            ->assertSee('Sophia Laurent')
            ->assertSee('Creative Director at LuxVibe')
            ->assertSee('RK Vibes delivered an unforgettable brand experience.');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Sophia Laurent')
            ->assertSee('RK Vibes delivered an unforgettable brand experience.');
    }

    public function test_inactive_testimonials_are_excluded_from_public_display(): void
    {
        Testimonial::factory()->create([
            'client_name' => 'Unverified Customer',
            'message' => 'This review should not show publicly.',
            'is_active' => false,
        ]);

        $this->get(route('about'))
            ->assertOk()
            ->assertDontSee('Unverified Customer')
            ->assertDontSee('This review should not show publicly.');
    }

    public function test_testimonials_empty_state_falls_back_gracefully(): void
    {
        // When no testimonials in database, default founder quote shows without error
        $this->get(route('about'))
            ->assertOk()
            ->assertSee('RK Vibes, Founder');
    }

    // 4. ADMIN TO PUBLIC LIFECYCLE VERIFICATION

    public function test_admin_create_update_toggle_service_reflects_on_public_page(): void
    {
        // 1. Create via admin
        $this->actingAs($this->admin)->post(route('admin.services.store'), [
            'title' => 'VIP VIP Lounge Experience',
            'slug' => 'vip-lounge-experience',
            'description' => 'Exclusive backstage experience with catering.',
            'icon' => 'VIP',
            'sort_order' => 1,
            'is_active' => 1,
        ])->assertRedirect(route('admin.services.index'));

        // Appears on public
        $this->get(route('solutions'))
            ->assertOk()
            ->assertSee('VIP VIP Lounge Experience');

        $service = Service::where('slug', 'vip-lounge-experience')->first();
        $this->assertNotNull($service);

        // 2. Update via admin
        $this->actingAs($this->admin)->put(route('admin.services.update', $service), [
            'title' => 'Elite Backstage Pass',
            'slug' => 'vip-lounge-experience',
            'description' => 'Updated luxury tour and private suite.',
            'icon' => 'Elite',
            'sort_order' => 1,
            'is_active' => 1,
        ])->assertRedirect(route('admin.services.index'));

        $this->get(route('solutions'))
            ->assertOk()
            ->assertSee('Elite Backstage Pass')
            ->assertDontSee('VIP VIP Lounge Experience');

        // 3. Toggle inactive
        $this->actingAs($this->admin)
            ->from(route('admin.services.index'))
            ->patch(route('admin.services.toggle', $service))
            ->assertRedirect(route('admin.services.index'));

        $this->get(route('solutions'))
            ->assertOk()
            ->assertDontSee('Elite Backstage Pass');
    }

    public function test_admin_create_update_delete_gallery_item_reflects_on_public_page(): void
    {
        Storage::fake('public');
        $image = UploadedFile::fake()->image('festival.jpg');

        // 1. Create via admin
        $this->actingAs($this->admin)->post(route('admin.gallery.store'), [
            'title' => 'Neon Nights Fest 2026',
            'description' => 'Over 5000 attendees dancing till midnight.',
            'image' => $image,
            'sort_order' => 1,
            'is_active' => 1,
        ])->assertRedirect(route('admin.gallery.index'));

        $this->get(route('work'))
            ->assertOk()
            ->assertSee('Neon Nights Fest 2026');

        $item = GalleryItem::where('title', 'Neon Nights Fest 2026')->first();
        $this->assertNotNull($item);

        // 2. Update via admin
        $this->actingAs($this->admin)->put(route('admin.gallery.update', $item), [
            'title' => 'Neon Nights: The Resurgence',
            'description' => 'Updated festival headline recap.',
            'sort_order' => 1,
            'is_active' => 1,
        ])->assertRedirect(route('admin.gallery.index'));

        $this->get(route('work'))
            ->assertOk()
            ->assertSee('Neon Nights: The Resurgence')
            ->assertDontSee('Over 5000 attendees dancing till midnight.');

        // 3. Disable via update
        $this->actingAs($this->admin)->put(route('admin.gallery.update', $item), [
            'title' => 'Neon Nights: The Resurgence',
            'description' => 'Updated festival headline recap.',
            'sort_order' => 1,
            'is_active' => 0,
        ])->assertRedirect(route('admin.gallery.index'));

        $this->get(route('work'))
            ->assertOk()
            ->assertDontSee('Neon Nights: The Resurgence');

        // 4. Delete via admin
        $this->actingAs($this->admin)->delete(route('admin.gallery.destroy', $item))
            ->assertRedirect(route('admin.gallery.index'));

        $this->assertDatabaseMissing('gallery_items', ['id' => $item->id]);
    }

    public function test_admin_create_update_delete_testimonial_reflects_on_public_page(): void
    {
        // 1. Create testimonial
        $this->actingAs($this->admin)->post(route('admin.testimonials.store'), [
            'client_name' => 'Alexander Hayes',
            'position' => 'Festival Co-Founder',
            'message' => 'The production quality blew our entire board away.',
            'rating' => 5,
            'sort_order' => 1,
            'is_active' => 1,
        ])->assertRedirect(route('admin.testimonials.index'));

        $this->get(route('about'))
            ->assertOk()
            ->assertSee('Alexander Hayes')
            ->assertSee('Festival Co-Founder');

        $testimonial = Testimonial::where('client_name', 'Alexander Hayes')->first();
        $this->assertNotNull($testimonial);

        // 2. Update testimonial
        $this->actingAs($this->admin)->put(route('admin.testimonials.update', $testimonial), [
            'client_name' => 'Alexander Hayes MBE',
            'position' => 'Lead Festival Director',
            'message' => 'Updated message about excellence.',
            'rating' => 5,
            'sort_order' => 1,
            'is_active' => 1,
        ])->assertRedirect(route('admin.testimonials.index'));

        $this->get(route('about'))
            ->assertOk()
            ->assertSee('Alexander Hayes MBE')
            ->assertSee('Lead Festival Director');

        // 3. Disable testimonial
        $this->actingAs($this->admin)->put(route('admin.testimonials.update', $testimonial), [
            'client_name' => 'Alexander Hayes MBE',
            'message' => 'Updated message about excellence.',
            'rating' => 5,
            'sort_order' => 1,
            'is_active' => 0,
        ])->assertRedirect(route('admin.testimonials.index'));

        $this->get(route('about'))
            ->assertOk()
            ->assertDontSee('Alexander Hayes MBE');
    }
}
