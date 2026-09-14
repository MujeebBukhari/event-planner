<?php

namespace Tests\Feature;

use App\Models\EventBooking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class EventBookingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 1. Valid ticket booking is stored.
     */
    public function test_valid_ticket_booking_is_stored(): void
    {
        $response = $this->post(route('tickets.store'), [
            'name' => 'Jordan Smith',
            'email' => 'jordan@email.com',
            'phone' => '(612) 555-0199',
            'event_type' => 'General Admission',
            'guest_count' => 2,
            'payment_method' => 'card',
        ]);

        $response->assertRedirect(route('tickets'));
        $response->assertSessionHas('success', 'Your ticket booking has been submitted successfully.');

        $this->assertDatabaseHas('event_bookings', [
            'name' => 'Jordan Smith',
            'email' => 'jordan@email.com',
            'phone' => '(612) 555-0199',
            'event_type' => 'General Admission',
            'guest_count' => 2,
            'total_amount' => 50.00,
            'payment_method' => 'card',
            'status' => 'pending',
        ]);
    }

    /**
     * 2. General Admission calculates the correct server-side total.
     */
    public function test_general_admission_calculates_correct_server_side_total(): void
    {
        // Client maliciously attempts to send a total of $1.00 for 3 GA tickets ($25 each = $75)
        $response = $this->post(route('tickets.store'), [
            'name' => 'Alice GA',
            'email' => 'alice@example.com',
            'event_type' => 'General Admission',
            'guest_count' => 3,
            'total_amount' => 1.00,
            'payment_method' => 'card',
        ]);

        $response->assertRedirect(route('tickets'));

        $booking = EventBooking::where('email', 'alice@example.com')->first();
        $this->assertNotNull($booking);
        $this->assertEquals(75.00, $booking->total_amount);
    }

    /**
     * 3. VIP Access calculates the correct server-side total.
     */
    public function test_vip_access_calculates_correct_server_side_total(): void
    {
        // 2 VIP tickets ($60 each = $120)
        $response = $this->post(route('tickets.store'), [
            'name' => 'Bob VIP',
            'email' => 'bob@example.com',
            'event_type' => 'VIP Access',
            'guest_count' => 2,
            'total_amount' => 5.00,
            'payment_method' => 'apple_pay',
        ]);

        $response->assertRedirect(route('tickets'));

        $booking = EventBooking::where('email', 'bob@example.com')->first();
        $this->assertNotNull($booking);
        $this->assertEquals(120.00, $booking->total_amount);
        $this->assertEquals('apple_pay', $booking->payment_method);
    }

    /**
     * 4. Invalid ticket type is rejected.
     */
    public function test_invalid_ticket_type_is_rejected(): void
    {
        $response = $this->post(route('tickets.store'), [
            'name' => 'Bad Tier User',
            'email' => 'badtier@example.com',
            'event_type' => 'Free Pass',
            'guest_count' => 1,
            'payment_method' => 'card',
        ]);

        $response->assertSessionHasErrors('event_type');
        $this->assertDatabaseMissing('event_bookings', ['email' => 'badtier@example.com']);
    }

    /**
     * 5. Invalid quantity is rejected.
     */
    public function test_invalid_quantity_is_rejected(): void
    {
        // Zero quantity
        $responseZero = $this->post(route('tickets.store'), [
            'name' => 'Zero Qty',
            'email' => 'zero@example.com',
            'event_type' => 'General Admission',
            'guest_count' => 0,
            'payment_method' => 'card',
        ]);
        $responseZero->assertSessionHasErrors('guest_count');

        // Negative quantity
        $responseNegative = $this->post(route('tickets.store'), [
            'name' => 'Negative Qty',
            'email' => 'negative@example.com',
            'event_type' => 'General Admission',
            'guest_count' => -2,
            'payment_method' => 'card',
        ]);
        $responseNegative->assertSessionHasErrors('guest_count');

        // Over 20 quantity
        $responseOver = $this->post(route('tickets.store'), [
            'name' => 'Over Qty',
            'email' => 'over@example.com',
            'event_type' => 'General Admission',
            'guest_count' => 25,
            'payment_method' => 'card',
        ]);
        $responseOver->assertSessionHasErrors('guest_count');

        $this->assertEquals(0, EventBooking::count());
    }

    /**
     * 6. Invalid email is rejected.
     */
    public function test_invalid_email_is_rejected(): void
    {
        $response = $this->post(route('tickets.store'), [
            'name' => 'Bad Email User',
            'email' => 'not-an-email',
            'event_type' => 'General Admission',
            'guest_count' => 1,
            'payment_method' => 'card',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertEquals(0, EventBooking::count());
    }

    /**
     * 7. Missing name is rejected.
     */
    public function test_missing_name_is_rejected(): void
    {
        $response = $this->post(route('tickets.store'), [
            'name' => '',
            'email' => 'noname@example.com',
            'event_type' => 'General Admission',
            'guest_count' => 1,
            'payment_method' => 'card',
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertEquals(0, EventBooking::count());
    }

    /**
     * 8. Payment method validation works.
     */
    public function test_payment_method_validation_works(): void
    {
        // Invalid payment method
        $responseInvalid = $this->post(route('tickets.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'event_type' => 'General Admission',
            'guest_count' => 1,
            'payment_method' => 'bitcoin',
        ]);
        $responseInvalid->assertSessionHasErrors('payment_method');

        // Valid google_pay
        $responseValid = $this->post(route('tickets.store'), [
            'name' => 'Valid User',
            'email' => 'valid@example.com',
            'event_type' => 'General Admission',
            'guest_count' => 1,
            'payment_method' => 'google_pay',
        ]);
        $responseValid->assertSessionHasNoErrors();
        $this->assertDatabaseHas('event_bookings', [
            'email' => 'valid@example.com',
            'payment_method' => 'google_pay',
        ]);
    }

    /**
     * 9. Card information is not stored.
     */
    public function test_card_information_is_not_stored(): void
    {
        $sensitiveCardData = [
            'card_number' => '4242424242424242',
            'card_expiry' => '12/28',
            'card_cvc' => '999',
            'billing_zip' => '55401',
        ];

        $response = $this->post(route('tickets.store'), array_merge([
            'name' => 'Card Privacy User',
            'email' => 'privacy@example.com',
            'event_type' => 'General Admission',
            'guest_count' => 1,
            'payment_method' => 'card',
        ], $sensitiveCardData));

        $response->assertRedirect(route('tickets'));

        $booking = EventBooking::where('email', 'privacy@example.com')->first();
        $this->assertNotNull($booking);

        // Ensure database table columns do not include sensitive card fields
        $columns = Schema::getColumnListing('event_bookings');
        $this->assertNotContains('card_number', $columns);
        $this->assertNotContains('card_expiry', $columns);
        $this->assertNotContains('card_cvc', $columns);
        $this->assertNotContains('billing_zip', $columns);

        // Ensure attributes do not leak card data
        $attributes = $booking->toArray();
        $this->assertArrayNotHasKey('card_number', $attributes);
        $this->assertArrayNotHasKey('card_cvc', $attributes);
        $this->assertArrayNotHasKey('card_expiry', $attributes);
        $this->assertArrayNotHasKey('billing_zip', $attributes);
    }
}
