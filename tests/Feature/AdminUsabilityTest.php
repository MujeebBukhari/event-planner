<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\EventBooking;
use App\Models\GalleryItem;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUsabilityTest extends TestCase
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

    // 1. DASHBOARD USABILITY

    public function test_dashboard_shows_recent_bookings(): void
    {
        EventBooking::factory()->create([
            'name' => 'Recent Customer Alpha',
            'event_type' => 'VIP Concert',
            'status' => 'confirmed',
            'total_amount' => 350.00,
            'created_at' => now(),
        ]);

        $this->actingAs($this->admin)->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Recent Bookings')
            ->assertSee('Recent Customer Alpha')
            ->assertSee('View All Bookings');
    }

    public function test_dashboard_shows_recent_contact_messages(): void
    {
        ContactMessage::factory()->create([
            'name' => 'Recent Inquirer Beta',
            'subject' => 'Corporate Partnership Inquiry',
            'status' => 'unread',
            'created_at' => now(),
        ]);

        $this->actingAs($this->admin)->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Recent Contact Messages')
            ->assertSee('Recent Inquirer Beta')
            ->assertSee('Corporate Partnership Inquiry')
            ->assertSee('View All Messages');
    }

    public function test_dashboard_shows_quick_action_links(): void
    {
        $this->actingAs($this->admin)->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Quick Actions')
            ->assertSee(route('admin.services.create'))
            ->assertSee(route('admin.gallery.create'))
            ->assertSee(route('admin.testimonials.create'))
            ->assertSee(route('admin.bookings.index'))
            ->assertSee(route('admin.contacts.index'));
    }

    // 2. BOOKINGS SEARCH, STATUS & DATE FILTERS

    public function test_booking_search_by_name_works(): void
    {
        EventBooking::factory()->create(['name' => 'Target Customer Name']);
        EventBooking::factory()->create(['name' => 'Other Random Person']);

        $response = $this->actingAs($this->admin)->get(route('admin.bookings.index', ['search' => 'Target Customer']));

        $response->assertOk()
            ->assertSee('Target Customer Name')
            ->assertDontSee('Other Random Person')
            ->assertSee('Clear Filters');
    }

    public function test_booking_search_by_email_works(): void
    {
        EventBooking::factory()->create(['name' => 'User One', 'email' => 'special_booking@example.com']);
        EventBooking::factory()->create(['name' => 'User Two', 'email' => 'ignored@example.com']);

        $response = $this->actingAs($this->admin)->get(route('admin.bookings.index', ['search' => 'special_booking']));

        $response->assertOk()
            ->assertSee('User One')
            ->assertDontSee('User Two');
    }

    public function test_booking_search_by_phone_works(): void
    {
        EventBooking::factory()->create(['name' => 'Phone User', 'phone' => '612-555-9876']);
        EventBooking::factory()->create(['name' => 'Other Phone User', 'phone' => '212-555-1111']);

        $response = $this->actingAs($this->admin)->get(route('admin.bookings.index', ['search' => '555-9876']));

        $response->assertOk()
            ->assertSee('Phone User')
            ->assertDontSee('Other Phone User');
    }

    public function test_booking_status_filter_works(): void
    {
        EventBooking::factory()->create(['name' => 'Pending Person', 'status' => 'pending']);
        EventBooking::factory()->create(['name' => 'Confirmed Person', 'status' => 'confirmed']);

        $response = $this->actingAs($this->admin)->get(route('admin.bookings.index', ['status' => 'pending']));

        $response->assertOk()
            ->assertSee('Pending Person')
            ->assertDontSee('Confirmed Person');
    }

    public function test_booking_date_filter_works(): void
    {
        EventBooking::factory()->create([
            'name' => 'October Concert Booking',
            'event_date' => '2026-10-15',
        ]);
        EventBooking::factory()->create([
            'name' => 'December Gala Booking',
            'event_date' => '2026-12-20',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.bookings.index', [
            'date_from' => '2026-10-01',
            'date_to' => '2026-10-31',
        ]));

        $response->assertOk()
            ->assertSee('October Concert Booking')
            ->assertDontSee('December Gala Booking');
    }

    public function test_booking_pagination_preserves_query_parameters(): void
    {
        EventBooking::factory()->count(25)->create([
            'event_type' => 'Festival VIP',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.bookings.index', [
            'status' => 'pending',
            'page' => 2,
        ]));

        $response->assertOk()
            ->assertSee('page=1')
            ->assertSee('status=pending');
    }

    // 3. CONTACT MESSAGES SEARCH, STATUS & PAGINATION

    public function test_contact_messages_search_works(): void
    {
        ContactMessage::factory()->create([
            'name' => 'Specific Inquirer',
            'subject' => 'Sponsorship Proposal 2026',
        ]);
        ContactMessage::factory()->create([
            'name' => 'Unrelated Inquirer',
            'subject' => 'General Question',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.contacts.index', ['search' => 'Sponsorship']));

        $response->assertOk()
            ->assertSee('Specific Inquirer')
            ->assertDontSee('Unrelated Inquirer')
            ->assertSee('Clear Filters');
    }

    public function test_contact_messages_status_filter_works(): void
    {
        ContactMessage::factory()->create(['name' => 'Unread Client', 'status' => 'unread']);
        ContactMessage::factory()->create(['name' => 'Read Client', 'status' => 'read']);

        $response = $this->actingAs($this->admin)->get(route('admin.contacts.index', ['status' => 'unread']));

        $response->assertOk()
            ->assertSee('Unread Client')
            ->assertDontSee('Read Client');
    }

    public function test_contact_messages_pagination_preserves_query(): void
    {
        ContactMessage::factory()->count(25)->create(['status' => 'unread']);

        $response = $this->actingAs($this->admin)->get(route('admin.contacts.index', [
            'status' => 'unread',
            'page' => 2,
        ]));

        $response->assertOk()
            ->assertSee('status=unread');
    }

    // 4. SERVICES SEARCH, STATUS & PAGINATION

    public function test_services_search_and_status_filter_work(): void
    {
        Service::factory()->create([
            'title' => 'VIP Concierge Service',
            'slug' => 'vip-concierge',
            'is_active' => true,
        ]);
        Service::factory()->create([
            'title' => 'VIP Backstage Tour',
            'slug' => 'vip-backstage',
            'is_active' => false,
        ]);
        Service::factory()->create([
            'title' => 'Standard Sound Setup',
            'slug' => 'standard-sound',
            'is_active' => true,
        ]);

        // Search by title
        $this->actingAs($this->admin)->get(route('admin.services.index', ['search' => 'Concierge']))
            ->assertOk()
            ->assertSee('VIP Concierge Service')
            ->assertDontSee('Standard Sound Setup')
            ->assertSee('Clear Filters');

        // Filter active only
        $this->actingAs($this->admin)->get(route('admin.services.index', ['search' => 'VIP', 'status' => 'active']))
            ->assertOk()
            ->assertSee('VIP Concierge Service')
            ->assertDontSee('VIP Backstage Tour');
    }

    // 5. GALLERY SEARCH, STATUS & PAGINATION

    public function test_gallery_search_and_status_filter_work(): void
    {
        GalleryItem::factory()->create([
            'title' => 'Electric Carnival Stage',
            'description' => 'Night stage with lasers and pyrotechnics',
            'is_active' => true,
        ]);
        GalleryItem::factory()->create([
            'title' => 'Acoustic Morning Session',
            'description' => 'Intimate coffee house session',
            'is_active' => false,
        ]);

        $this->actingAs($this->admin)->get(route('admin.gallery.index', ['search' => 'Carnival']))
            ->assertOk()
            ->assertSee('Electric Carnival Stage')
            ->assertDontSee('Acoustic Morning Session')
            ->assertSee('Clear Filters');

        $this->actingAs($this->admin)->get(route('admin.gallery.index', ['status' => 'inactive']))
            ->assertOk()
            ->assertSee('Acoustic Morning Session')
            ->assertDontSee('Electric Carnival Stage');
    }

    // 6. TESTIMONIALS SEARCH, STATUS & PAGINATION

    public function test_testimonials_search_and_status_filter_work(): void
    {
        Testimonial::factory()->create([
            'client_name' => 'Elena Rostova',
            'position' => 'Opera Gala Director',
            'is_active' => true,
        ]);
        Testimonial::factory()->create([
            'client_name' => 'Marcus Aurelius',
            'position' => 'Festival Founder',
            'is_active' => false,
        ]);

        $this->actingAs($this->admin)->get(route('admin.testimonials.index', ['search' => 'Elena']))
            ->assertOk()
            ->assertSee('Elena Rostova')
            ->assertDontSee('Marcus Aurelius')
            ->assertSee('Clear Filters');

        $this->actingAs($this->admin)->get(route('admin.testimonials.index', ['status' => 'inactive']))
            ->assertOk()
            ->assertSee('Marcus Aurelius')
            ->assertDontSee('Elena Rostova');
    }

    // 7. EMPTY STATES

    public function test_empty_states_render_cleanly_when_filters_return_no_records(): void
    {
        $this->actingAs($this->admin)->get(route('admin.bookings.index', ['search' => 'NonExistentCustomerName']))
            ->assertOk()
            ->assertSee('No bookings match the selected search or filter criteria.');

        $this->actingAs($this->admin)->get(route('admin.contacts.index', ['search' => 'NonExistentSenderName']))
            ->assertOk()
            ->assertSee('No messages match your search or filter criteria.');

        $this->actingAs($this->admin)->get(route('admin.services.index', ['search' => 'NonExistentServiceTitle']))
            ->assertOk()
            ->assertSee('No services match your search or filter criteria.');

        $this->actingAs($this->admin)->get(route('admin.gallery.index', ['search' => 'NonExistentGalleryTitle']))
            ->assertOk()
            ->assertSee('No gallery items match your search or filter criteria.');

        $this->actingAs($this->admin)->get(route('admin.testimonials.index', ['search' => 'NonExistentTestimonialClient']))
            ->assertOk()
            ->assertSee('No testimonials match your search or filter criteria.');
    }
}
