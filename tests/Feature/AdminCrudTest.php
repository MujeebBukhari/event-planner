<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\EventBooking;
use App\Models\GalleryItem;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $normalUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->admin()->create();
        $this->normalUser = User::factory()->create(['is_admin' => false, 'role' => 'user']);
    }

    // ==========================================
    // 1. AUTHORIZATION TESTS
    // ==========================================

    public function test_guest_cannot_access_any_admin_crud_routes(): void
    {
        $this->get(route('admin.bookings.index'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.contacts.index'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.services.index'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.gallery.index'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.testimonials.index'))->assertRedirect(route('admin.login'));
    }

    public function test_normal_user_cannot_access_any_admin_crud_routes(): void
    {
        $this->actingAs($this->normalUser)->get(route('admin.bookings.index'))->assertStatus(403);
        $this->actingAs($this->normalUser)->get(route('admin.contacts.index'))->assertStatus(403);
        $this->actingAs($this->normalUser)->get(route('admin.services.index'))->assertStatus(403);
        $this->actingAs($this->normalUser)->get(route('admin.gallery.index'))->assertStatus(403);
        $this->actingAs($this->normalUser)->get(route('admin.testimonials.index'))->assertStatus(403);
    }

    public function test_admin_can_access_all_admin_crud_index_pages(): void
    {
        $this->actingAs($this->admin)->get(route('admin.bookings.index'))->assertOk();
        $this->actingAs($this->admin)->get(route('admin.contacts.index'))->assertOk();
        $this->actingAs($this->admin)->get(route('admin.services.index'))->assertOk();
        $this->actingAs($this->admin)->get(route('admin.gallery.index'))->assertOk();
        $this->actingAs($this->admin)->get(route('admin.testimonials.index'))->assertOk();
    }

    // ==========================================
    // 2. BOOKINGS CRUD TESTS
    // ==========================================

    public function test_admin_can_view_bookings(): void
    {
        $booking = EventBooking::factory()->create([
            'name' => 'Michael Scott',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.bookings.index'));

        $response->assertOk();
        $response->assertSee('Michael Scott');
        $response->assertSee('pending');
    }

    public function test_admin_can_view_booking_details(): void
    {
        $booking = EventBooking::factory()->create([
            'name' => 'Jim Halpert',
            'email' => 'jim@dundermifflin.com',
            'event_type' => 'Corporate Gathering',
            'total_amount' => 120.00,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.bookings.show', $booking));

        $response->assertOk();
        $response->assertSee('Jim Halpert');
        $response->assertSee('jim@dundermifflin.com');
        $response->assertSee('Corporate Gathering');
        $response->assertSee('$120.00');
    }

    public function test_admin_can_update_booking_status(): void
    {
        $booking = EventBooking::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($this->admin)->put(route('admin.bookings.update', $booking), [
            'status' => 'confirmed',
        ]);

        $response->assertRedirect();
        $this->assertEquals('confirmed', $booking->fresh()->status);
    }

    public function test_booking_status_update_fails_on_invalid_status(): void
    {
        $booking = EventBooking::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($this->admin)->put(route('admin.bookings.update', $booking), [
            'status' => 'invalid-status-xyz',
        ]);

        $response->assertSessionHasErrors('status');
        $this->assertEquals('pending', $booking->fresh()->status);
    }

    public function test_admin_can_delete_booking(): void
    {
        $booking = EventBooking::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.bookings.destroy', $booking));

        $response->assertRedirect(route('admin.bookings.index'));
        $this->assertDatabaseMissing('event_bookings', ['id' => $booking->id]);
    }

    // ==========================================
    // 3. CONTACT MESSAGES TESTS
    // ==========================================

    public function test_admin_can_view_contact_messages(): void
    {
        $message = ContactMessage::factory()->create([
            'name' => 'Pam Beesly',
            'subject' => 'Art Exhibition Event',
            'status' => 'unread',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.contacts.index'));

        $response->assertOk();
        $response->assertSee('Pam Beesly');
        $response->assertSee('Art Exhibition Event');
    }

    public function test_admin_can_view_contact_message_details(): void
    {
        $message = ContactMessage::factory()->create([
            'name' => 'Dwight Schrute',
            'email' => 'dwight@beetfarm.com',
            'message' => 'Need event planning for annual beet festival.',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.contacts.show', $message));

        $response->assertOk();
        $response->assertSee('Dwight Schrute');
        $response->assertSee('dwight@beetfarm.com');
        $response->assertSee('Need event planning for annual beet festival.');
    }

    public function test_admin_can_change_message_read_unread_status(): void
    {
        $message = ContactMessage::factory()->create(['status' => 'unread']);

        $response = $this->actingAs($this->admin)->patch(route('admin.contacts.status', $message), [
            'status' => 'read',
        ]);

        $response->assertRedirect();
        $this->assertEquals('read', $message->fresh()->status);

        // Toggle back to unread
        $this->actingAs($this->admin)->patch(route('admin.contacts.status', $message), [
            'status' => 'unread',
        ]);
        $this->assertEquals('unread', $message->fresh()->status);
    }

    public function test_admin_can_delete_contact_message(): void
    {
        $message = ContactMessage::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.contacts.destroy', $message));

        $response->assertRedirect(route('admin.contacts.index'));
        $this->assertDatabaseMissing('contact_messages', ['id' => $message->id]);
    }

    // ==========================================
    // 4. SERVICES CRUD TESTS
    // ==========================================

    public function test_admin_can_create_service(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.services.store'), [
            'title' => 'VIP Concierge & Talent Booking',
            'slug' => 'vip-concierge-talent-booking',
            'description' => 'Dedicated concierge and live entertainment management.',
            'icon' => '05 — Concierge',
            'sort_order' => 5,
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.services.index'));
        $this->assertDatabaseHas('services', [
            'title' => 'VIP Concierge & Talent Booking',
            'slug' => 'vip-concierge-talent-booking',
            'sort_order' => 5,
            'is_active' => 1,
        ]);
    }

    public function test_admin_can_edit_service(): void
    {
        $service = Service::factory()->create([
            'title' => 'Initial Service Title',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.services.update', $service), [
            'title' => 'Updated Service Title',
            'slug' => 'updated-service-title',
            'description' => 'Updated description text.',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.services.index'));
        $this->assertEquals('Updated Service Title', $service->fresh()->title);
    }

    public function test_admin_can_delete_service(): void
    {
        $service = Service::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.services.destroy', $service));

        $response->assertRedirect(route('admin.services.index'));
        $this->assertDatabaseMissing('services', ['id' => $service->id]);
    }

    public function test_admin_can_toggle_service_status(): void
    {
        $service = Service::factory()->create(['is_active' => true]);

        $this->actingAs($this->admin)->patch(route('admin.services.toggle', $service));
        $this->assertFalse($service->fresh()->is_active);

        $this->actingAs($this->admin)->patch(route('admin.services.toggle', $service));
        $this->assertTrue($service->fresh()->is_active);
    }

    public function test_service_validation_rejects_missing_title(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.services.store'), [
            'title' => '',
        ]);

        $response->assertSessionHasErrors('title');
    }

    // ==========================================
    // 5. GALLERY CRUD TESTS & FILE UPLOADS
    // ==========================================

    public function test_admin_can_create_gallery_item_with_valid_image(): void
    {
        Storage::fake('public');

        $image = UploadedFile::fake()->image('festival.jpg', 800, 600);

        $response = $this->actingAs($this->admin)->post(route('admin.gallery.store'), [
            'title' => 'Summer Music Gathering 2026',
            'description' => 'Main stage live performance lighting.',
            'image' => $image,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.gallery.index'));

        $item = GalleryItem::where('title', 'Summer Music Gathering 2026')->first();
        $this->assertNotNull($item);
        $this->assertNotNull($item->image_path);
        Storage::disk('public')->assertExists($item->image_path);
    }

    public function test_invalid_gallery_image_upload_is_rejected(): void
    {
        Storage::fake('public');

        $fakePdf = UploadedFile::fake()->create('malicious.pdf', 200, 'application/pdf');

        $response = $this->actingAs($this->admin)->post(route('admin.gallery.store'), [
            'title' => 'Invalid Media File',
            'image' => $fakePdf,
        ]);

        $response->assertSessionHasErrors('image');
        $this->assertDatabaseMissing('gallery_items', ['title' => 'Invalid Media File']);
    }

    public function test_admin_can_edit_gallery_item_and_replace_image(): void
    {
        Storage::fake('public');

        $oldImage = UploadedFile::fake()->image('old_pic.jpg');
        $oldPath = $oldImage->store('gallery', 'public');

        $gallery = GalleryItem::factory()->create([
            'title' => 'Old Title',
            'image_path' => $oldPath,
        ]);

        $newImage = UploadedFile::fake()->image('new_pic.png');

        $response = $this->actingAs($this->admin)->put(route('admin.gallery.update', $gallery), [
            'title' => 'New Gallery Title',
            'description' => 'Updated media description',
            'image' => $newImage,
            'sort_order' => 3,
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.gallery.index'));

        $gallery->refresh();
        $this->assertEquals('New Gallery Title', $gallery->title);
        $this->assertNotEquals($oldPath, $gallery->image_path);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($gallery->image_path);
    }

    public function test_admin_can_delete_gallery_item_and_removes_file(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('delete_me.webp');
        $filePath = $file->store('gallery', 'public');

        $gallery = GalleryItem::factory()->create([
            'image_path' => $filePath,
        ]);

        Storage::disk('public')->assertExists($filePath);

        $response = $this->actingAs($this->admin)->delete(route('admin.gallery.destroy', $gallery));

        $response->assertRedirect(route('admin.gallery.index'));
        $this->assertDatabaseMissing('gallery_items', ['id' => $gallery->id]);
        Storage::disk('public')->assertMissing($filePath);
    }

    // ==========================================
    // 6. TESTIMONIALS CRUD TESTS
    // ==========================================

    public function test_admin_can_create_testimonial(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.testimonials.store'), [
            'client_name' => 'Rebecca Vance',
            'position' => 'Marketing VP, Lumon Tech',
            'message' => 'RK Vibes produced an extraordinary product launch experience.',
            'rating' => 5,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.testimonials.index'));
        $this->assertDatabaseHas('testimonials', [
            'client_name' => 'Rebecca Vance',
            'rating' => 5,
        ]);
    }

    public function test_testimonial_rating_must_be_between_1_and_5(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.testimonials.store'), [
            'client_name' => 'Bad Rating User',
            'message' => 'Some feedback',
            'rating' => 6, // Invalid rating!
        ]);

        $response->assertSessionHasErrors('rating');

        $response2 = $this->actingAs($this->admin)->post(route('admin.testimonials.store'), [
            'client_name' => 'Zero Rating User',
            'message' => 'Some feedback',
            'rating' => 0, // Invalid rating!
        ]);

        $response2->assertSessionHasErrors('rating');
    }

    public function test_admin_can_edit_testimonial(): void
    {
        $testimonial = Testimonial::factory()->create([
            'client_name' => 'Original Name',
            'rating' => 4,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.testimonials.update', $testimonial), [
            'client_name' => 'Updated Client Name',
            'position' => 'Founder',
            'message' => 'Updated testimonial message body.',
            'rating' => 5,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.testimonials.index'));
        $this->assertEquals('Updated Client Name', $testimonial->fresh()->client_name);
        $this->assertEquals(5, $testimonial->fresh()->rating);
    }

    public function test_admin_can_delete_testimonial(): void
    {
        $testimonial = Testimonial::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.testimonials.destroy', $testimonial));

        $response->assertRedirect(route('admin.testimonials.index'));
        $this->assertDatabaseMissing('testimonials', ['id' => $testimonial->id]);
    }
}
