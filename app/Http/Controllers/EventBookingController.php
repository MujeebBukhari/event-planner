<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventBookingRequest;
use App\Mail\EventBookingSubmitted;
use App\Models\EventBooking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class EventBookingController extends Controller
{
    /**
     * Ticket prices mapped by ticket tier.
     *
     * @var array<string, float>
     */
    public const TICKET_PRICES = [
        'General Admission' => 25.00,
        'VIP Access' => 60.00,
    ];

    /**
     * Store a newly created event booking and dispatch notification.
     */
    public function store(StoreEventBookingRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $ticketType = $validated['event_type'];
        $guestCount = (int) $validated['guest_count'];
        $unitPrice = self::TICKET_PRICES[$ticketType] ?? 0.00;
        $totalAmount = $unitPrice * $guestCount;

        $booking = EventBooking::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'event_type' => $ticketType,
            'guest_count' => $guestCount,
            'total_amount' => $totalAmount,
            'payment_method' => $validated['payment_method'],
            'status' => 'pending',
        ]);

        $adminEmail = config('mail.admin_email');
        if ($adminEmail) {
            try {
                Mail::to($adminEmail)->send(new EventBookingSubmitted($booking));
            } catch (Throwable $e) {
                Log::error('Failed to send booking notification email: '.$e->getMessage(), [
                    'booking_id' => $booking->id,
                ]);
            }
        }

        return redirect()
            ->route('tickets')
            ->with('success', 'Your ticket booking has been submitted successfully.');
    }
}
