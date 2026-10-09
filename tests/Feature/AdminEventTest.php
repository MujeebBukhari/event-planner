<?php

namespace Tests\Feature;

use App\Models\EventBooking;
use App\Models\EventSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminEventTest extends TestCase
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

    /**
     * 1. Guest cannot access the admin event editor.
     */
    public function test_guest_cannot_access_admin_event_editor(): void
    {
        $response = $this->get(route('admin.event.edit'));

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * 2. Admin can view event editor page with current settings.
     */
    public function test_admin_can_view_event_editor(): void
    {
        $event = EventSetting::current();

        $response = $this->actingAs($this->admin)->get(route('admin.event.edit'));

        $response->assertOk();
        $response->assertSee('Featured Event & Ticket Pricing');
        $response->assertSee($event->title);
        $response->assertSee('25.00');
        $response->assertSee('60.00');
    }

    /**
     * 3. Admin can update event details and ticket prices.
     */
    public function test_admin_can_update_event_details_and_prices(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.event.update'), [
            'tag' => 'Grand Opening',
            'title' => 'RK Vibes — Summer Festival 2026',
            'date_location' => 'Chicago, IL · July 2026',
            'doors_time' => 'Doors 5:00 PM',
            'general_admission_price' => 35.00,
            'vip_access_price' => 85.00,
            'general_admission_desc' => 'General lawn & main stage access',
            'vip_access_desc' => 'VIP pit, lounge & fast pass',
            'cta_text' => 'Get Tickets Today',
            'note' => 'All sales final. Stripe secured.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('event_settings', [
            'tag' => 'Grand Opening',
            'title' => 'RK Vibes — Summer Festival 2026',
            'date_location' => 'Chicago, IL · July 2026',
            'general_admission_price' => 35.00,
            'vip_access_price' => 85.00,
        ]);
    }

    /**
     * 4. Home page displays updated event details and prices.
     */
    public function test_home_page_displays_updated_event_details_and_prices(): void
    {
        EventSetting::current()->update([
            'tag' => 'Winter Gala',
            'title' => 'RK Vibes Presents — Winter Gala 2026',
            'date_location' => 'St. Paul, MN · Dec 2026',
            'general_admission_price' => 45.00,
            'vip_access_price' => 110.00,
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Winter Gala');
        $response->assertSee('St. Paul, MN · Dec 2026');
        $response->assertSee('$45');
        $response->assertSee('$110');
    }

    /**
     * 5. Tickets booking page displays updated event details and prices.
     */
    public function test_tickets_page_displays_updated_event_details_and_prices(): void
    {
        EventSetting::current()->update([
            'title' => 'RK Vibes Presents — Winter Gala 2026',
            'date_location' => 'St. Paul, MN · Dec 2026',
            'doors_time' => 'Doors 7:00 PM',
            'general_admission_price' => 45.00,
            'vip_access_price' => 110.00,
            'general_admission_desc' => 'Includes 1 complimentary drink',
            'vip_access_desc' => 'All-inclusive banquet & front seating',
        ]);

        $response = $this->get(route('tickets'));

        $response->assertOk();
        $response->assertSee('RK Vibes Presents — Winter Gala 2026');
        $response->assertSee('St. Paul, MN · Dec 2026');
        $response->assertSee('Doors 7:00 PM');
        $response->assertSee('$45');
        $response->assertSee('$110');
        $response->assertSee('Includes 1 complimentary drink');
        $response->assertSee('All-inclusive banquet & front seating');
    }

    /**
     * 6. Ticket booking calculates total using updated dynamic prices.
     */
    public function test_ticket_booking_calculates_total_using_updated_prices(): void
    {
        EventSetting::current()->update([
            'general_admission_price' => 40.00,
            'vip_access_price' => 95.00,
        ]);

        // Book 3 General Admission tickets ($40 * 3 = $120.00)
        $this->post(route('tickets.store'), [
            'name' => 'Jane Dynamic GA',
            'email' => 'jane@example.com',
            'phone' => '612-555-0100',
            'event_type' => 'General Admission',
            'guest_count' => 3,
            'payment_method' => 'card',
        ]);

        $gaBooking = EventBooking::where('email', 'jane@example.com')->first();
        $this->assertNotNull($gaBooking);
        $this->assertEquals(120.00, $gaBooking->total_amount);

        // Book 2 VIP Access tickets ($95 * 2 = $190.00)
        $this->post(route('tickets.store'), [
            'name' => 'John Dynamic VIP',
            'email' => 'john@example.com',
            'phone' => '612-555-0200',
            'event_type' => 'VIP Access',
            'guest_count' => 2,
            'payment_method' => 'card',
        ]);

        $vipBooking = EventBooking::where('email', 'john@example.com')->first();
        $this->assertNotNull($vipBooking);
        $this->assertEquals(190.00, $vipBooking->total_amount);
    }

    /**
     * 7. Admin dashboard displays featured event controls and syncs.
     */
    public function test_admin_dashboard_displays_featured_event_card(): void
    {
        $event = EventSetting::current();

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Featured Event &amp; Ticket Pricing', false);
        $response->assertSee($event->title);
        $response->assertSee((string) $event->general_admission_price);
        $response->assertSee((string) $event->vip_access_price);
    }
}
