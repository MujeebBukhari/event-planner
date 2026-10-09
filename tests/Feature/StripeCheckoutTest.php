<?php

namespace Tests\Feature;

use App\Models\EventBooking;
use App\Services\StripeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Mockery;
use Stripe\Checkout\Session as StripeSession;
use Tests\TestCase;

class StripeCheckoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 1. Booking creation initiates Stripe Checkout and redirects to the checkout session URL.
     */
    public function test_booking_creation_initiates_stripe_checkout_and_redirects(): void
    {
        $mockStripe = Mockery::mock(StripeService::class);
        $mockSession = new StripeSession(['id' => 'cs_test_session_abc123']);
        $mockSession->url = 'https://checkout.stripe.com/c/pay/cs_test_session_abc123';

        $mockStripe->shouldReceive('createCheckoutSession')
            ->once()
            ->withArgs(function ($booking, $successUrl, $cancelUrl) {
                return $booking->email === 'jordan@example.com'
                    && $booking->guest_count === 2
                    && $booking->total_amount == 50.00
                    && str_contains($successUrl, 'tickets/success')
                    && str_contains($cancelUrl, 'tickets/cancel');
            })
            ->andReturn($mockSession);

        $this->app->instance(StripeService::class, $mockStripe);

        $response = $this->post(route('tickets.store'), [
            'name' => 'Jordan Smith',
            'email' => 'jordan@example.com',
            'phone' => '(612) 555-0199',
            'event_type' => 'General Admission',
            'guest_count' => 2,
            'payment_method' => 'stripe',
        ]);

        $response->assertRedirect('https://checkout.stripe.com/c/pay/cs_test_session_abc123');

        $this->assertDatabaseHas('event_bookings', [
            'name' => 'Jordan Smith',
            'email' => 'jordan@example.com',
            'guest_count' => 2,
            'total_amount' => 50.00,
            'payment_status' => 'unpaid',
            'status' => 'pending',
            'stripe_session_id' => 'cs_test_session_abc123',
        ]);
    }

    /**
     * 2. The server authoritatively calculates the booking amount for General Admission.
     */
    public function test_server_authoritatively_calculates_price_for_general_admission(): void
    {
        $mockStripe = Mockery::mock(StripeService::class);
        $mockSession = new StripeSession(['id' => 'cs_test_ga']);
        $mockSession->url = 'https://checkout.stripe.com/c/pay/cs_test_ga';

        $mockStripe->shouldReceive('createCheckoutSession')->andReturn($mockSession);
        $this->app->instance(StripeService::class, $mockStripe);

        // Client attempts to submit a tampered price of $5.00 for 4 General Admission tickets ($25 each = $100.00)
        $response = $this->post(route('tickets.store'), [
            'name' => 'Client Price Tamper',
            'email' => 'tamper@example.com',
            'event_type' => 'General Admission',
            'guest_count' => 4,
            'total_amount' => 5.00,
            'payment_method' => 'card',
        ]);

        $response->assertRedirect('https://checkout.stripe.com/c/pay/cs_test_ga');

        $booking = EventBooking::where('email', 'tamper@example.com')->first();
        $this->assertNotNull($booking);
        $this->assertEquals(100.00, $booking->total_amount);
        $this->assertEquals('unpaid', $booking->payment_status);
    }

    /**
     * 3. The server authoritatively calculates the booking amount for VIP Access.
     */
    public function test_server_authoritatively_calculates_price_for_vip_access(): void
    {
        $mockStripe = Mockery::mock(StripeService::class);
        $mockSession = new StripeSession(['id' => 'cs_test_vip']);
        $mockSession->url = 'https://checkout.stripe.com/c/pay/cs_test_vip';

        $mockStripe->shouldReceive('createCheckoutSession')->andReturn($mockSession);
        $this->app->instance(StripeService::class, $mockStripe);

        // 3 VIP tickets ($60 each = $180.00)
        $response = $this->post(route('tickets.store'), [
            'name' => 'VIP Patron',
            'email' => 'vip@example.com',
            'event_type' => 'VIP Access',
            'guest_count' => 3,
            'total_amount' => 10.00,
            'payment_method' => 'stripe',
        ]);

        $response->assertRedirect('https://checkout.stripe.com/c/pay/cs_test_vip');

        $booking = EventBooking::where('email', 'vip@example.com')->first();
        $this->assertNotNull($booking);
        $this->assertEquals(180.00, $booking->total_amount);
    }

    /**
     * 4. Zero card data is stored in the database (PCI-DSS compliance).
     */
    public function test_card_details_are_never_saved_to_database(): void
    {
        $columns = Schema::getColumnListing('event_bookings');
        $this->assertNotContains('card_number', $columns);
        $this->assertNotContains('card_expiry', $columns);
        $this->assertNotContains('card_cvc', $columns);
        $this->assertNotContains('card_cvv', $columns);
        $this->assertNotContains('billing_zip', $columns);

        $booking = EventBooking::factory()->create([
            'payment_method' => 'stripe',
            'payment_status' => 'unpaid',
            'stripe_session_id' => 'cs_test_sec_check',
        ]);

        $attributes = $booking->toArray();
        $this->assertArrayNotHasKey('card_number', $attributes);
        $this->assertArrayNotHasKey('card_cvc', $attributes);
    }

    /**
     * 5. Success page renders booking info but NEVER marks booking as paid from the URL.
     */
    public function test_success_page_does_not_mark_booking_as_paid(): void
    {
        $booking = EventBooking::factory()->create([
            'name' => 'Pending Customer',
            'email' => 'pending@example.com',
            'payment_status' => 'unpaid',
            'status' => 'pending',
            'stripe_session_id' => 'cs_test_unpaid_123',
            'total_amount' => 50.00,
        ]);

        $response = $this->get(route('tickets.success', ['session_id' => 'cs_test_unpaid_123']));

        $response->assertOk();
        $response->assertSee('Thank You, Pending Customer!');
        $response->assertSee('Pending Verification');

        // Verify database state was NOT changed to paid
        $booking->refresh();
        $this->assertEquals('unpaid', $booking->payment_status);
        $this->assertEquals('pending', $booking->status);
        $this->assertNull($booking->paid_at);
        $this->assertFalse($booking->isPaid());
    }

    /**
     * 6. Success page for a paid booking shows the verified badge.
     */
    public function test_success_page_displays_verified_badge_for_paid_booking(): void
    {
        $booking = EventBooking::factory()->paid()->create([
            'name' => 'Paid Customer',
            'email' => 'paid@example.com',
            'stripe_session_id' => 'cs_test_paid_456',
        ]);

        $response = $this->get(route('tickets.success', ['session_id' => 'cs_test_paid_456']));

        $response->assertOk();
        $response->assertSee('Thank You, Paid Customer!');
        $response->assertSee('Payment Verified');
    }

    /**
     * 7. Success page redirects if booking session is not found.
     */
    public function test_success_page_redirects_if_session_not_found(): void
    {
        $response = $this->get(route('tickets.success', ['session_id' => 'cs_test_nonexistent']));

        $response->assertRedirect(route('tickets'));
        $response->assertSessionHas('error');
    }

    /**
     * 8. Cancel page renders properly without altering booking data.
     */
    public function test_cancel_page_renders_cleanly(): void
    {
        $booking = EventBooking::factory()->create([
            'name' => 'Cancelled Customer',
            'email' => 'cancel@example.com',
            'payment_status' => 'unpaid',
        ]);

        $response = $this->get(route('tickets.cancel', ['booking_id' => $booking->id]));

        $response->assertOk();
        $response->assertSee('Payment Was Not Completed');
        $response->assertSee('#'.$booking->id);

        $booking->refresh();
        $this->assertEquals('unpaid', $booking->payment_status);
    }

    /**
     * 9. Tickets view contains Stripe Checkout messaging and does not include raw card inputs.
     */
    public function test_tickets_view_contains_stripe_redirect_elements(): void
    {
        $response = $this->get(route('tickets'));

        $response->assertOk();
        $response->assertSee('Stripe Checkout');
        $response->assertSee('Proceed to Stripe Checkout');
        $response->assertDontSee('id="card_number"', false);
        $response->assertDontSee('name="card_number"', false);
        $response->assertDontSee('name="card_cvc"', false);
    }

    /**
     * 10. Success page verifies paid session directly with Stripe and marks booking as paid.
     */
    public function test_success_page_verifies_paid_session_with_stripe_and_marks_booking_paid(): void
    {
        $booking = EventBooking::factory()->create([
            'name' => 'Verified Customer',
            'email' => 'verified@example.com',
            'payment_status' => 'unpaid',
            'status' => 'pending',
            'stripe_session_id' => 'cs_test_verify_paid_999',
            'total_amount' => 50.00,
        ]);

        $mockSession = new StripeSession(['id' => 'cs_test_verify_paid_999']);
        $mockSession->payment_status = 'paid';
        $mockSession->status = 'complete';
        $mockSession->payment_intent = 'pi_verified_test_123';
        $mockSession->payment_method_types = ['card'];

        $mockStripe = Mockery::mock(StripeService::class);
        $mockStripe->shouldReceive('isConfigured')->andReturn(true);
        $mockStripe->shouldReceive('retrieveCheckoutSession')
            ->with('cs_test_verify_paid_999')
            ->once()
            ->andReturn($mockSession);

        $this->app->instance(StripeService::class, $mockStripe);

        $response = $this->get(route('tickets.success', ['session_id' => 'cs_test_verify_paid_999']));

        $response->assertOk();
        $response->assertSee('Payment Verified');
        $response->assertSee('pi_verified_test_123');

        $booking->refresh();
        $this->assertEquals('paid', $booking->payment_status);
        $this->assertEquals('confirmed', $booking->status);
        $this->assertEquals('pi_verified_test_123', $booking->stripe_payment_intent_id);
        $this->assertTrue($booking->isPaid());
    }

    /**
     * 11. Stripe checkout failure marks booking failed and redirects back with error.
     */
    public function test_stripe_checkout_failure_redirects_back_with_error(): void
    {
        $mockStripe = Mockery::mock(StripeService::class);
        $mockStripe->shouldReceive('isConfigured')->andReturn(true);
        $mockStripe->shouldReceive('createCheckoutSession')
            ->once()
            ->andThrow(new \Exception('Your card was declined'));

        $this->app->instance(StripeService::class, $mockStripe);

        $response = $this->from(route('tickets'))->post(route('tickets.store'), [
            'name' => 'Failing Customer',
            'email' => 'failing@example.com',
            'phone' => '(612) 555-0100',
            'event_type' => 'General Admission',
            'guest_count' => 1,
            'payment_method' => 'stripe',
        ]);

        $response->assertRedirect(route('tickets'));
        $response->assertSessionHas('error');

        $booking = EventBooking::where('email', 'failing@example.com')->first();
        $this->assertNotNull($booking);
        $this->assertEquals('failed', $booking->payment_status);
        $this->assertEquals('cancelled', $booking->status);
    }
}
