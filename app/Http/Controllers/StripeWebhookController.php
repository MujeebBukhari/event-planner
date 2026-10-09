<?php

namespace App\Http\Controllers;

use App\Mail\EventBookingSubmitted;
use App\Models\EventBooking;
use App\Services\StripeService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Event;
use Throwable;

class StripeWebhookController extends Controller
{
    /**
     * Handle incoming Stripe webhook notifications.
     */
    public function handle(Request $request, StripeService $stripeService): JsonResponse
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        if (empty($sigHeader)) {
            return response()->json(['error' => 'Missing Stripe-Signature header'], 400);
        }

        try {
            $event = $stripeService->constructWebhookEvent($payload, $sigHeader);
        } catch (Exception $e) {
            Log::warning('Stripe webhook signature verification failed: '.$e->getMessage());

            return response()->json(['error' => 'Invalid signature'], 400);
        }

        switch ($event->type) {
            case 'checkout.session.completed':
                $this->handleCheckoutSessionCompleted($event);
                break;

            case 'payment_intent.payment_failed':
                $this->handlePaymentIntentFailed($event);
                break;

            case 'checkout.session.expired':
                $this->handleCheckoutSessionExpired($event);
                break;

            default:
                Log::info('Unhandled Stripe webhook event: '.$event->type);
                break;
        }

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Handle checkout.session.completed event.
     */
    protected function handleCheckoutSessionCompleted(Event $event): void
    {
        $session = $event->data->object;
        $bookingId = $session->client_reference_id ?? $session->metadata->booking_id ?? null;
        $sessionId = $session->id ?? null;
        $paymentIntentId = $session->payment_intent ?? null;

        $booking = null;
        if ($bookingId) {
            $booking = EventBooking::find($bookingId);
        }
        if (! $booking && $sessionId) {
            $booking = EventBooking::where('stripe_session_id', $sessionId)->first();
        }

        if (! $booking) {
            Log::error('Stripe webhook: booking not found for completed session', [
                'session_id' => $sessionId,
                'booking_id' => $bookingId,
            ]);

            return;
        }

        // Idempotency check: If already marked paid, return early to prevent duplicate processing
        if ($booking->isPaid()) {
            Log::info('Stripe webhook: booking already marked paid (idempotent)', [
                'booking_id' => $booking->id,
            ]);

            return;
        }

        // Determine payment method if available from session
        $paymentMethodType = 'card';
        if (! empty($session->payment_method_types) && is_array($session->payment_method_types)) {
            $paymentMethodType = $session->payment_method_types[0] ?? 'card';
        }

        // Update booking state
        $booking->markAsPaid($paymentIntentId, $paymentMethodType);

        // Dispatch admin notification email with confirmed paid status
        $adminEmail = config('mail.admin_email');
        if ($adminEmail) {
            try {
                Mail::to($adminEmail)->send(new EventBookingSubmitted($booking));
            } catch (Throwable $e) {
                Log::error('Failed to send booking notification email on payment: '.$e->getMessage(), [
                    'booking_id' => $booking->id,
                ]);
            }
        }

        Log::info("Booking #{$booking->id} successfully marked as PAID via Stripe Checkout session {$sessionId}");
    }

    /**
     * Handle payment_intent.payment_failed event.
     */
    protected function handlePaymentIntentFailed(Event $event): void
    {
        $paymentIntent = $event->data->object;
        $bookingId = $paymentIntent->metadata->booking_id ?? null;
        $paymentIntentId = $paymentIntent->id ?? null;

        $booking = null;
        if ($bookingId) {
            $booking = EventBooking::find($bookingId);
        }
        if (! $booking && $paymentIntentId) {
            $booking = EventBooking::where('stripe_payment_intent_id', $paymentIntentId)->first();
        }

        if ($booking) {
            $booking->markAsFailed();
            Log::warning("Booking #{$booking->id} payment marked as FAILED for PaymentIntent {$paymentIntentId}");
        }
    }

    /**
     * Handle checkout.session.expired event.
     */
    protected function handleCheckoutSessionExpired(Event $event): void
    {
        $session = $event->data->object;
        $bookingId = $session->client_reference_id ?? $session->metadata->booking_id ?? null;
        $sessionId = $session->id ?? null;

        $booking = null;
        if ($bookingId) {
            $booking = EventBooking::find($bookingId);
        }
        if (! $booking && $sessionId) {
            $booking = EventBooking::where('stripe_session_id', $sessionId)->first();
        }

        if ($booking && ! $booking->isPaid()) {
            $booking->markAsFailed();
            Log::info("Booking #{$booking->id} marked as cancelled/failed due to expired Stripe session {$sessionId}");
        }
    }
}
