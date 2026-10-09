<?php

namespace App\Services;

use App\Models\EventBooking;
use Exception;
use Stripe\Checkout\Session;
use Stripe\Event;
use Stripe\StripeClient;
use Stripe\Webhook;

class StripeService
{
    protected ?StripeClient $client = null;

    protected ?string $secretKey;

    protected ?string $webhookSecret;

    public function __construct(?string $secretKey = null, ?string $webhookSecret = null)
    {
        $this->secretKey = $secretKey ?? config('services.stripe.secret');
        $this->webhookSecret = $webhookSecret ?? config('services.stripe.webhook_secret');

        if (! empty($this->secretKey) && $this->secretKey !== 'sk_test_placeholder') {
            $this->client = new StripeClient($this->secretKey);
        }
    }

    /**
     * Get the StripeClient instance.
     */
    public function getClient(): ?StripeClient
    {
        return $this->client;
    }

    /**
     * Create a Stripe Checkout Session for the event booking.
     *
     * @throws Exception
     */
    public function createCheckoutSession(EventBooking $booking, string $successUrl, string $cancelUrl): Session
    {
        if (! $this->client) {
            throw new Exception('Stripe API key is not configured. Please set STRIPE_SECRET in your environment.');
        }

        $unitAmount = (int) round(($booking->total_amount / max(1, $booking->guest_count)) * 100);

        return $this->client->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => "RK Vibes — {$booking->event_type}",
                        'description' => "Event Ticket Booking #{$booking->id} for {$booking->guest_count} ticket(s)",
                    ],
                    'unit_amount' => $unitAmount,
                ],
                'quantity' => $booking->guest_count,
            ]],
            'mode' => 'payment',
            'customer_email' => $booking->email,
            'client_reference_id' => (string) $booking->id,
            'metadata' => [
                'booking_id' => (string) $booking->id,
                'event_type' => (string) $booking->event_type,
                'guest_count' => (string) $booking->guest_count,
            ],
            'payment_intent_data' => [
                'metadata' => [
                    'booking_id' => (string) $booking->id,
                ],
            ],
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
        ]);
    }

    /**
     * Determine whether the Stripe client is configured with a valid secret key.
     */
    public function isConfigured(): bool
    {
        return $this->client !== null;
    }

    /**
     * Retrieve a Stripe Checkout Session by ID.
     *
     * @throws Exception
     */
    public function retrieveCheckoutSession(string $sessionId): ?Session
    {
        if (! $this->client) {
            throw new Exception('Stripe API key is not configured. Please set STRIPE_SECRET in your environment.');
        }

        return $this->client->checkout->sessions->retrieve($sessionId, [
            'expand' => ['payment_intent'],
        ]);
    }

    /**
     * Verify and construct a Stripe webhook event from the raw payload and signature header.
     *
     * @throws Exception
     */
    public function constructWebhookEvent(string $payload, string $signatureHeader): Event
    {
        if (empty($this->webhookSecret) || $this->webhookSecret === 'whsec_placeholder') {
            throw new Exception('Stripe webhook secret is not configured.');
        }

        return Webhook::constructEvent($payload, $signatureHeader, $this->webhookSecret);
    }
}
