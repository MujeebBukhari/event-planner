<?php

namespace Tests\Feature;

use App\Mail\EventBookingSubmitted;
use App\Models\EventBooking;
use App\Services\StripeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Mockery;
use Stripe\Event;
use Stripe\Exception\SignatureVerificationException;
use Tests\TestCase;

class StripeWebhookTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 1. Webhook returns 400 when Stripe signature header is missing.
     */
    public function test_webhook_rejects_request_without_signature(): void
    {
        $response = $this->postJson(route('stripe.webhook'), [
            'id' => 'evt_test_123',
            'type' => 'checkout.session.completed',
        ]);

        $response->assertStatus(400);
        $response->assertJson(['error' => 'Missing Stripe-Signature header']);
    }

    /**
     * 2. Webhook returns 400 when Stripe signature verification fails.
     */
    public function test_webhook_rejects_invalid_signature(): void
    {
        $mockStripe = Mockery::mock(StripeService::class);
        $mockStripe->shouldReceive('constructWebhookEvent')
            ->once()
            ->andThrow(new SignatureVerificationException('Signature mismatch'));

        $this->app->instance(StripeService::class, $mockStripe);

        $response = $this->post(
            route('stripe.webhook'),
            ['id' => 'evt_fake'],
            ['Stripe-Signature' => 'invalid_signature_header']
        );

        $response->assertStatus(400);
        $response->assertJson(['error' => 'Invalid signature']);
    }

    /**
     * 3. checkout.session.completed updates payment status, marks booking confirmed, and sends email.
     */
    public function test_checkout_session_completed_marks_booking_as_paid_and_dispatches_email(): void
    {
        Mail::fake();

        $booking = EventBooking::factory()->create([
            'payment_status' => 'unpaid',
            'status' => 'pending',
            'stripe_session_id' => 'cs_test_success_999',
            'stripe_payment_intent_id' => null,
            'paid_at' => null,
        ]);

        $eventPayload = [
            'id' => 'evt_test_checkout_complete',
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'cs_test_success_999',
                    'payment_intent' => 'pi_test_intent_777',
                    'payment_method_types' => ['card'],
                    'client_reference_id' => (string) $booking->id,
                    'metadata' => [
                        'booking_id' => (string) $booking->id,
                    ],
                ],
            ],
        ];

        $event = Event::constructFrom($eventPayload);

        $mockStripe = Mockery::mock(StripeService::class);
        $mockStripe->shouldReceive('constructWebhookEvent')
            ->once()
            ->andReturn($event);

        $this->app->instance(StripeService::class, $mockStripe);

        $response = $this->post(
            route('stripe.webhook'),
            $eventPayload,
            ['Stripe-Signature' => 'valid_sig_test']
        );

        $response->assertOk();
        $response->assertJson(['status' => 'success']);

        $booking->refresh();
        $this->assertEquals('paid', $booking->payment_status);
        $this->assertEquals('confirmed', $booking->status);
        $this->assertEquals('pi_test_intent_777', $booking->stripe_payment_intent_id);
        $this->assertNotNull($booking->paid_at);
        $this->assertTrue($booking->isPaid());

        Mail::assertSent(EventBookingSubmitted::class, function (EventBookingSubmitted $mail) use ($booking) {
            return $mail->booking->id === $booking->id && $mail->booking->isPaid();
        });
    }

    /**
     * 4. Idempotency: Duplicate checkout.session.completed events do not duplicate processing or emails.
     */
    public function test_webhook_idempotency_prevents_duplicate_processing(): void
    {
        Mail::fake();

        // Already paid booking
        $booking = EventBooking::factory()->paid()->create([
            'stripe_session_id' => 'cs_test_idempotent_123',
            'stripe_payment_intent_id' => 'pi_test_already_processed',
        ]);

        $eventPayload = [
            'id' => 'evt_duplicate_checkout',
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'cs_test_idempotent_123',
                    'payment_intent' => 'pi_test_already_processed',
                    'client_reference_id' => (string) $booking->id,
                    'metadata' => [
                        'booking_id' => (string) $booking->id,
                    ],
                ],
            ],
        ];

        $event = Event::constructFrom($eventPayload);

        $mockStripe = Mockery::mock(StripeService::class);
        $mockStripe->shouldReceive('constructWebhookEvent')
            ->once()
            ->andReturn($event);

        $this->app->instance(StripeService::class, $mockStripe);

        $response = $this->post(
            route('stripe.webhook'),
            $eventPayload,
            ['Stripe-Signature' => 'valid_sig_test']
        );

        $response->assertOk();
        $response->assertJson(['status' => 'success']);

        // Assert no new email was queued or sent because booking was already paid
        Mail::assertNothingSent();
    }

    /**
     * 5. payment_intent.payment_failed marks booking as failed and cancelled.
     */
    public function test_payment_intent_failed_marks_booking_as_failed(): void
    {
        $booking = EventBooking::factory()->create([
            'payment_status' => 'unpaid',
            'status' => 'pending',
            'stripe_payment_intent_id' => 'pi_test_failed_intent',
        ]);

        $eventPayload = [
            'id' => 'evt_test_payment_failed',
            'type' => 'payment_intent.payment_failed',
            'data' => [
                'object' => [
                    'id' => 'pi_test_failed_intent',
                    'metadata' => [
                        'booking_id' => (string) $booking->id,
                    ],
                ],
            ],
        ];

        $event = Event::constructFrom($eventPayload);

        $mockStripe = Mockery::mock(StripeService::class);
        $mockStripe->shouldReceive('constructWebhookEvent')
            ->once()
            ->andReturn($event);

        $this->app->instance(StripeService::class, $mockStripe);

        $response = $this->post(
            route('stripe.webhook'),
            $eventPayload,
            ['Stripe-Signature' => 'valid_sig_test']
        );

        $response->assertOk();
        $response->assertJson(['status' => 'success']);

        $booking->refresh();
        $this->assertEquals('failed', $booking->payment_status);
        $this->assertEquals('cancelled', $booking->status);
    }

    /**
     * 6. Webhook route is exempt from CSRF token verification.
     */
    public function test_webhook_endpoint_bypasses_csrf(): void
    {
        // An external webhook POST without CSRF token must not return 419 (CSRF Token Mismatch)
        $response = $this->post(route('stripe.webhook'), [], []);
        $this->assertNotEquals(419, $response->getStatusCode());
    }

    /**
     * 7. checkout.session.expired marks unpaid booking as failed and cancelled.
     */
    public function test_checkout_session_expired_marks_booking_as_failed(): void
    {
        $booking = EventBooking::factory()->create([
            'payment_status' => 'unpaid',
            'status' => 'pending',
            'stripe_session_id' => 'cs_test_expired_session',
        ]);

        $eventPayload = [
            'id' => 'evt_test_checkout_expired',
            'type' => 'checkout.session.expired',
            'data' => [
                'object' => [
                    'id' => 'cs_test_expired_session',
                    'client_reference_id' => (string) $booking->id,
                    'metadata' => [
                        'booking_id' => (string) $booking->id,
                    ],
                ],
            ],
        ];

        $event = Event::constructFrom($eventPayload);

        $mockStripe = Mockery::mock(StripeService::class);
        $mockStripe->shouldReceive('constructWebhookEvent')
            ->once()
            ->andReturn($event);

        $this->app->instance(StripeService::class, $mockStripe);

        $response = $this->post(
            route('stripe.webhook'),
            $eventPayload,
            ['Stripe-Signature' => 'valid_sig_test']
        );

        $response->assertOk();
        $response->assertJson(['status' => 'success']);

        $booking->refresh();
        $this->assertEquals('failed', $booking->payment_status);
        $this->assertEquals('cancelled', $booking->status);
    }
}
